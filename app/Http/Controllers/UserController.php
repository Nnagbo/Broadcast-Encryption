<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// use Illuminate\Foundation\Auth\ThrottlesLogins;

use App\Http\Requests;
use App\Libraries\Custom;
use App\Libraries\Helpers;
use App\User;
use App\Plan;
use App\Pairing;
use DB;

use View;
use Input;
use Validator;
use Redirect;
class UserController extends Controller
{

  // use ThrottlesLogins;

  protected $custom;
  protected $helpers;
  
  public function __construct()
  {
    $this->custom = new Custom();
  }

  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
    public function index()
    {
        if(\Session::has('uid'))
        {
            return redirect('dashboard');
        }
        return view('login');
    }

    

    public function forgot(Request $request)
    {
        $custom = $this->custom;
        $email = $request->email;
        $time = $custom->time_now();

        if (empty($email))
        {
            $request->session()->flash('flash_message', 'The email field is required');
            return redirect()->back();
        }

        $email = $custom->process_email($email);
        if (empty($email))
        {
            $request->session()->flash('flash_message', 'Enter a Valid Email Address (e.g xyz@...)');
            return redirect()->back();
        }

        $check_email = DB::table('users')
            ->where('email', $email)
            ->first();

        if (empty($check_email))
        {
            $request->session()->flash('flash_message', 'Please enter your registered email');
            return redirect()->back();
        }

        $check_pwd = DB::table('password_reset')
            ->where('email', $email)
            ->where('verified', 0)
            ->where('status', 0)
            ->where('deleted', 0)
            ->first();

        if (!empty($check_pwd))
        {
            $data = ['deleted' => 1, 'updated_at' => $time];
            $pwd_upd = DB::table('password_reset')
                ->where('id', $check_pwd->id)
                ->update($data);

        }

        $token = $custom->hashh($email, $time);
        $data = [
            'user_id' => $check_email->id, 'email' => $email, 'token' => $token, 'created_at' => $time
        ];
        $reset_ins = DB::table('password_reset')
            ->insert($data);

        $link_data = base64_encode($token.$email);
        $link = env('SITE_URL').'/reset/activate/'.$link_data;
        $custom->send_reset_email($email, $link, 'Password Reset Token');

        $request->session()->flash('flash_message_success', 'Check your email for the password reset link');
        return redirect()->back();

    }

    public function forgot_page()
    {
        return view('forgot');
    }


  public function login_page()
  {
    if(\Session::has('id'))
    {
      return redirect('dashboard');
    }
    return view('login');
  }

  public function login(Request $request)
  {
    // print_r($req_all);exit;
    $this->validate($request, [
      // 'g-recaptcha-response' => 'required|captcha',
      'email' => 'required|max:255',
      'password' => 'required|min:4|max:32'
    ]);
    $user = User::where('email', strtolower($request->email) )
    ->first();
    if (!$user)
    {
      $request->session()->flash('flash_message', 'Login Failed!');
      return redirect('/login');
    }
    if ($user)
    {
      $match = password_verify($request->password, $user->password);
      if (!$match)
      {
        $request->session()->flash('flash_message', 'Wrong Email or Password!');
        return redirect('/login');
      }
      if ($user->deleted == 1)
      {
        $request->session()->flash('flash_message', 'Your account has been suspended. Contact '.env('SITE_EMAIL'));
        return redirect('/login');
      }
      if ($match)
      {
        /*$data_on = ['is_online' => 1, 'updated_at' => $this->custom->time_now()];
        DB::table('users')
          ->where('id', $user->id)
          ->update($data_on);*/
        $ref_link = env('SITE_URL').'/register/'.strtolower($user->username);

        $request->session()->put('uid', $user->id);
        $request->session()->put('username', strtoupper($user->username) );
        $request->session()->put('referral_link', $ref_link );
        $request->session()->put('email', $user->email);
        $request->session()->put('priority', $user->priority);
        $request->session()->put('utype', 0);
      }
      // if ($user->priority > 5)
      // {
      //   return redirect('console');
      // }
    // dd(session()->all());exit;
      return redirect('dashboard');
    }
    else
    {
      $request->session()->flash('flash_message', 'Login Failed!');
      return redirect('/login');
    }
  }


    public function register_page($ref = '')
    {
        if(\Session::has('uid'))
        {
            return redirect('/');
        }

        $user = User::where('username', $ref)
            ->where('deleted', 0)
            ->first();
        if (!$user)
        {
            $ref = '';
        }
        return view('register', ['ref' => $ref]);
    }

  // public function register_page($ref = 'system')
  // {
  //   if(\Session::has('id'))
  //   {
  //     return redirect('/');
  //   }
  //   $plans = Plan::where('deleted', 0)
  //     ->get();
  //   $user = User::where('username', $ref)
  //     ->where('deleted', 0)
  //     ->first();
  //   if (!$user)
  //   {
  //     $ref = 'system';
  //   }
  //   return view('register', ['plans' => $plans, 'ref' => $ref]);
  // }

  public function register(Request $request)
  {
    $custom = $this->custom;
    // $helpers = $this->helpers;
    $req_all = $request->all();
    // var_dump($request->ref);exit;
    $this->validate($request, [
        'username' => 'required|max:255|unique:users',
        'password' => 'required|min:4|max:32',
        'mobile' => 'required|unique:users,mobile',
        'email' => 'required|email|unique:users',
        'fullname' => 'required',
    ]);
    $user = new User;
    $mobile = $custom->process_mobile($request->mobile);
    $email = $custom->process_email($request->email);
    $username = $custom->process_username($request->username);
    
    $split = explode(" ", $request->fullname);
    $name_count = count($split);

    $check_email_users = DB::table('users')
      ->where('email', $email)
      ->first();
    if ($check_email_users)
    {
      $request->session()->flash('flash_message', 'Email already exists');
      return redirect('/register');
    }

    $check_mobile_users = DB::table('users')
      ->where('mobile', $mobile)
      ->first();
    if ($check_mobile_users)
    {
      $request->session()->flash('flash_message', 'Mobile number has already been taken');
      return redirect('/register');
    }

    if ($request->password != $request->repeat)
    {
      $request->session()->flash('flash_message', 'Password Mismatch!');
      return redirect('/register')->withInput($request->all());
    }
    if (empty($mobile))
    {
      $request->session()->flash('flash_message', 'Enter a Valid Mobile Number (e.g 23480312...)');
      return redirect('/register');
    }
    if (empty($email))
    {
      $request->session()->flash('flash_message', 'Enter a Valid Email Address (e.g xyz@...)');
      return redirect()->back();
    }
    if (empty($username))
    {
      $request->session()->flash('flash_message', 'Username should be alphanumeric (no space or special characters e.g username64)');
      return redirect()->back();
    }
    if ($name_count < 2)
    {
      $request->session()->flash('flash_message', 'Enter your full name (e.g Firstname Lastname)');
      return redirect('/register');
    }
    else
    {
      DB::transaction(function() use ($user, $mobile, $email, $username, $custom, $request, &$output) {
        $password_hashh = password_hash($request->password, PASSWORD_DEFAULT);
        $time = $custom->time_now();
        $u_name = strtolower($request->username);
        $ref_name = strtolower($request->ref);
        $user->username = strtolower($username);
        $user->password = $password_hashh;
        $name = $custom->fullname_decouple($request->fullname);
        $user->firstname = $name['first_name'];
        $user->lastname = $name['last_name'];
        $user->middlename = $name['middle_name'];
        $user->mobile = $mobile;
        $user->email = $email;
        // $user->plan_default = $request->plan;
        $user->created_at = $time;
        // $user->priority = 8;
        $time_reg = $custom->time_now();

        $ref_id = 0;

        if (empty($ref_name))
        {
            $ref_name = 'system';
        }
        if (!empty($ref_name))
        {
            $ref = DB::table('users')
                ->where('username', $ref_name)
                ->value('id');
            if ($ref)
            {
                $ref_id = $ref;
            }
            else
            {
                $ref_id = 0;
            }
        }
        // var_dump($ref_id.' test');exit;
        $ref_link = env('SITE_URL').'/register/'.strtolower($username);

        // $user->ref_id = $ref_id;
        // $user->ref_link = $ref_link;

        $user->save();
        $id = $user->id;

        $ref_data = [
            'user_id' => $id, 'ref_user_id' => $ref_id, 'ref_username' => $ref_name, 
            'created_at' => $time
        ];
        $ref_ins = DB::table('referrals')
            ->insert($ref_data);

        $balance_data = [
            'user_id' => $id, 'email' => $email, 'balance' => 0, 'previous_balance' => 0, 
            'created_at' => $time
        ];
        $balance_ins = DB::table('balance')
            ->insert($balance_data);

        $request->session()->put('uid', $id);
        $request->session()->put('utype', 0);
        $request->session()->put('username', strtoupper($username) );
        $request->session()->put('referral_link', env('SITE_URL').'/register/'.strtolower($username) );

        $token = $custom->hashh($email, $time);
        $data = [
            'user_id' => $id, 'email' => $email, 'token' => $token, 'created_at' => $time
        ];
        $reset_ins = DB::table('verifications')
            ->insert($data);

        $data_pwdh = [
                'user_id' => $id, 'password' => $password_hashh, 'created_at' => $time
            ];
        $pwdh_ins = DB::table('password_history')
            ->insert($data_pwdh);

        // $link_data = base64_encode($token.$email);
        // $link = env('SITE_URL').'/account/activate/'.$link_data;
        $link = 'Your encryption key is <b>'.$mobile.'</b>';
        $custom->send_generic_email($email, $link, 'Account Details');

        $output = 1;

      });
        $request->session()->flash('flash_message_success', 'Registration Successful!');
        return redirect('dashboard');
    }
  }


  public function logout(Request $request)
  {
    \Session::flush();
    return redirect('/login');
  }

  

  public function tos()
  {
      return view('terms');
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return Response
   */
  public function create()
  {
    return View::make('users.create');
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function update($id)
  {
    $input = Input::all();
    $validation = Validator::make($input, User::$rules);
    if ($validation->passes())
    {
        $user = User::find($id);
        $user->update($input);
        return Redirect::route('users.show', $id);
    }
  return Redirect::route('users.edit', $id)
          ->withInput()
          ->withErrors($validation)
          ->with('message', 'There were validation errors.');
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function destroy($id)
  {
    User::find($id)->delete();
    return Redirect::route('users.index');
  }

  

    public function reset(Request $request)
    {
        $custom = $this->custom;
        $password = $request->password;
        $repeat = $request->repeat;
        $token = $request->token;
        $time = $custom->time_now();
        $output = 0;

        if (empty($password) || empty($repeat) )
        {
            $request->session()->flash('flash_message', 'Both fields are required');
            return redirect()->back();
        }
        if ($password !== $repeat )
        {
            $request->session()->flash('flash_message', 'Passwords do not match');
            return redirect()->back();
        }
        if (empty($token))
        {
            $request->session()->flash('flash_message', 'An error occurred');
            return redirect('forgot');
        }

        $dec_hash = base64_decode($token);
        $hashh = substr($dec_hash, 0, 32);
        $email = $custom->process_email(substr($dec_hash, 32));

        if (!ctype_alnum($hashh))
        {
            $request->session()->flush();
            $request->session()->flash('flash_message', 'An error occurred');
            return redirect('forgot');
        }

        $pwdh = DB::table('password_reset')
            ->where('email', $email)
            ->where('token', $hashh)
            ->where('deleted', 0)
            // ->where('status', 0)
            // ->where('created_at', '<', $diff)
            ->first();

        if (empty($pwdh))
        {
            $request->session()->flash('flash_message', 'The verification link is invalid, you may request for another link');
            return redirect('forgot');
        }

        if ($pwdh->status == 1)
        {
            $request->session()->flash('flash_message', 'This link is no longer valid, you may request for another link');
            return redirect('forgot');
        }
        
        if ($pwdh->verified == 1)
        {
            $request->session()->flash('flash_message', 'The verification link is invalid, you may request for another link');
            return redirect('forgot');
        }

        $check_email = DB::table('users')
            ->where('email', $email)
            ->first();

        if (empty($check_email))
        {
            $request->session()->flash('flash_message', 'An error occurred, kindly contact our support');
            return redirect('login');
        }




        DB::transaction(function() use($check_email, $pwdh, $email, $password, $time, &$output) {
            // flag status expired
            $data_ver = ['verified' => 1, 'updated_at' => $time];
            $pwd_upd = DB::table('password_reset')
                ->where('id', $pwdh->id)
                ->update($data_ver);

            // add password to history
            $password_hashh = password_hash($password, PASSWORD_DEFAULT);
            $data = [
                'user_id' => $check_email->id, 'password' => $password_hashh, 'created_at' => $time
            ];
            $pwdh = DB::table('password_history')
                ->insert($data);

            // update password column on users table
            $data_users = [
                'password' => $password_hashh, 'updated_at' => $time
            ];
            $users_ins = DB::table('users')
                ->where('email', $email)
                ->update($data_users);

            $output = 1;
        });

        if ($output == 0)
        {
            $request->session()->flash('flash_message', 'An error occurred, kindly contact our support');
            return redirect('login');
        }
        if ($output == 1)
        {
            $request->session()->flash('flash_message_success', 'Password changed');
            return redirect('login');
        }
    }

    public function reset_page(Request $request, $token)
    {
        $custom = $this->custom;
        $time = $custom->time_now();

        if (empty($token))
        {
            $request->session()->flash('flash_message', 'An error occurred');
            return redirect('forgot');
        }

        $dec_hash = base64_decode($token);
        $hashh = substr($dec_hash, 0, 32);
        $email = $custom->process_email(substr($dec_hash, 32));

        if (!ctype_alnum($hashh))
        {
            $request->session()->flush();
            // $request->session()->put('flash_message_verified_error', 'An error occurred');
            $request->session()->flash('flash_message', 'An error occurred');
            return redirect('forgot');
        }

        $interval = 300;
        $diff = $custom->minute_diff($time, $interval);
        $pwdh = DB::table('password_reset')
            ->where('email', $email)
            ->where('token', $hashh)
            ->where('deleted', 0)
            // ->where('status', 0)
            // ->where('created_at', '<', $diff)
            ->first();

        if (empty($pwdh))
        {
            $request->session()->flash('flash_message', 'The verification link is invalid, you may request for another link');
            return redirect('forgot');
        }

        if ($pwdh->status == 1)
        {
            $request->session()->flash('flash_message', 'This link is no longer valid, you may request for another link');
            return redirect('forgot');
        }
        
        if ($pwdh->verified == 1)
        {
            $request->session()->flash('flash_message', 'This link has been used');
            return redirect('forgot');
        }

        
        if ($pwdh->created_at < $diff)
        {
            // flag status expired
            $data = ['status' => 1, 'updated_at' => $time];
            $verifications_upd = DB::table('password_reset')
                ->where('email', $email)
                ->where('token', $hashh)
                ->update($data);
            $request->session()->flash('flash_message', 'This link has expired, you may request for another link');
            return redirect('forgot');
        }

        return view('reset', ['token' => $token]);

    }
  
}