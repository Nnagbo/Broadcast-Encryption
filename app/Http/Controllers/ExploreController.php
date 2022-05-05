<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use App\User;
use App\Libraries\Custom;
use App\Libraries\Transactions;

use Paystack;

use SoapClient;
use SoapHeader;

class ExploreController extends Controller
{
	protected $custom;
    protected $transactions;

    protected $site_name;
    protected $live_server;

    protected $cipher;

    public function __construct()
    {
        $this->cipher = "aes-128-gcm";
        $this->middleware('login')->except('help');
        $this->custom = new Custom();
        $this->transactions = new Transactions();

        $this->live_server = env('APP_URL');
        $this->site_name = env('SITE_NAME');
    }

    public function account_upgrade(Request $request)
    {
        $custom = $this->custom;
        $user_id = \Session::get('uid');
        // $business_name = $request->business_name;
        $identification_mode = $request->identification_mode;
        $identification_number = $request->identification_number;
        $mobile = $request->mobile;
        $password = $request->password;
        $password_confirmation = $request->password_confirmation;
        $time = $custom->time_now();

        $this->validate($request, [
            'mobile' => 'required|numeric',
            'identification_mode' => 'required',
            'identification_number' => 'required',
            'password' => 'required|min:6|max:32|confirmed',
            'password_confirmation' => 'required|min:6|max:32',
        ]);

        if (empty($identification_mode) || empty($identification_number) || empty($mobile) || empty($password) || empty($password_confirmation) )
        {
            \Session::flash('flash_message', 'One or more compulsory fields omitted');
            return redirect()->back()->withInput();
        }

        if ($request->password !== $request->password_confirmation)
        {
          $request->session()->flash('flash_message', 'Password Mismatch');
          return redirect()->back()->withInput();
        }

        // $mobile = $custom->process_mobile($request->mobile);

        // if (empty($mobile))
        // {
        //   \Session::flash('flash_message', 'Enter a Valid Mobile Number (e.g 23480312...)');
        //   return redirect()->back()->withInput();
        // }

        $password_hashh = password_hash($password, PASSWORD_DEFAULT);

        $user = User::find($user_id);
        // $user->business_name = ucwords($request->business_name);
        $user->mobile = $mobile;
        $user->password = $password_hashh;
        $user->identification_mode = $request->identification_mode;
        $user->identification_number = $request->identification_number;

        $user->account_type = 1;

        $user->updated_at = $time;

        $user->save();
        \Session::flash('flash_message_success', 'Account Upgraded!');
        return redirect('dashboard');
    }

    public function account_upgrade_page()
    {
        $custom = $this->custom;
        $user_id = \Session::get('uid');
        $time = $custom->time_now();

        $user = DB::table('users')
            ->where('id', $user_id)
            ->first(['id', 'email', 'mobile', 'business_name']);

        if (empty($user))
        {
            \Session::flash('flash_message', 'No record found');
            return redirect()->back();
        }

        return view('account-upgrade', ['details' => $user]);
    }

    public function change_password(Request $request)
    {
        $custom = $this->custom;
        $transactions = $this->transactions;
        $old_password = $request->old_password;
        $new_password = $request->password;
        $repeat = $request->repeat;
        $user_id = \Session::get('uid');
        $time = $custom->time_now();
        $output = 0;

        $this->validate($request, [
            'password' => 'required|min:4|max:32'
        ]);

        if (empty($old_password) || empty($new_password) || empty($repeat) )
        {
            \Session::flash('flash_message', 'All fields are required');
            return redirect()->back();
        }

        if ($new_password !== $repeat )
        {
            \Session::flash('flash_message', 'Password mismatched');
            return redirect()->back();
        }
        $user =DB::table('users')
            ->where('id', $user_id)
            ->first();
        if (!$user)
        {
            \Session::flash('flash_message', 'An error occurred');
            return redirect()->back();
        }
        if ($user)
        {
            $match = password_verify($old_password, $user->password);
            if (!$match)
            {
                \Session::flash('flash_message', 'Enter your correct password!');
                return redirect()->back();
            }
            if ($match)
            {
                $password_hashh = password_hash($new_password, PASSWORD_DEFAULT);
                DB::transaction(function() use($user, $user_id, $password_hashh, $custom, $time, &$output) {

                    $data = [
                        'user_id' => $user_id, 'password' => $password_hashh, 'created_at' => $time
                    ];
                    $pwdh_ins = DB::table('password_history')
                        ->insert($data);

                    $data_user = ['password' => $password_hashh, 'updated_at' => $time];
                    $user_upd = DB::table('users')
                        ->where('id', $user_id)
                        ->update($data_user);

                    $output = 1;

                    $message = 'Your '.$this->site_name.' account password was successfully updated';
                    $custom->send_generic_email($user->email, $message, 'Account Update');
                });

                if ($output == 0)
                {
                    \Session::flash('flash_message', 'An error occurred');
                    return redirect()->back();
                }

                \Session::flash('flash_message_success', 'Password successfully updated');
                return redirect()->back();
                
            }
        }

    }

    public function change_password_page()
    {
        return view('account');
    }

    public function dashboard()
    {
    	$custom = $this->custom;
    	$user_id = \Session::get('uid');


        $profile = DB::table('users')
        	// ->join('debts', 'users.id', '=', 'debts.user_id')
            ->where('users.id', \Session::get('uid'))
            // ->where('debts.deleted', 0)
            ->select('users.firstname as firstname', 'users.email_verified', 'users.email', 'users.subscription')
            ->first();

        $messages = DB::table('messages')
            ->where('user_id', $user_id)
            ->where('deleted', 0)
            ->paginate(5);
            // var_dump(\Session::get('uid'));exit;

		return view('dashboard', ['messages' => $messages, 'profile' => $profile]);
    }

    public function help()
    {
        return view('help');
    }

    public function topup_page()
    {
    	$custom = $this->custom;
    	$user_id = \Session::get('uid');
    	$email = $custom->get_user_email($user_id);
    	return view('recharge', ['email' => $email]);
    }

    public function topup(Request $request)
    {
    	$custom = $this->custom;
    	$user_id = \Session::get('uid');
    	$email = $request->email;
    	$amount = $request->amount;
    	$reference = $request->reference;
    	$key = $request->key;

        if (empty($amount) )
        {
            \Session::flash('flash_message', 'Enter an amount');
            return redirect()->back();
        }
        if (!ctype_digit($amount) )
        {
            \Session::flash('flash_message', 'Please enter a valid amount');
            return redirect()->back();
        }
        if ($amount < 100 )
        {
            \Session::flash('flash_message', 'Amount cannot be less than N100');
            return redirect()->back();
        }

        $email = $custom->process_email($email);

        if (empty($email) || empty($reference) || empty($key) )
        {
            \Session::flash('flash_message', 'An error occurred');
            return redirect()->back();
        }
        // to be stored on payment tb
        $original = $amount;
        // pass gateway fee to user
        $amount = (($amount * 0.019) + 10 + $amount) * 100;

        return view('recharge-confirm', ['email' => $email, 'amount' => $amount, 'original' => $original, 'reference' => $reference, 'key' => $key]);
    }

    public function view_message($id)
    {
        $custom = $this->custom;
        $check_message = DB::table('messages')
            ->where('id', $id)
            ->where('deleted', 0)
            ->first();

        if (empty($check_message))
        {
            \Session::flash('flash_message', 'Record not found');
            return redirect()->back();
        }

        return view('message', ['message' => $check_message]);
    }

    public function show_original_msg(Request $request, $id)
    {
        $custom = $this->custom;
        $user_id = \Session::get('uid');
        $encryption_key = $request->encryption_key;
        $time = $custom->time_now();
        $output = 0;
        // $reference = $request->reference;
        // $key = $request->key;

        if (empty($encryption_key) )
        {
            \Session::flash('flash_message', 'Enter Your Encryption Key');
            return redirect()->back();
        }
        $message = DB::table('messages')
            ->where('id', $id)
            ->where('deleted', 0)
            ->first();

        if ($message->encryption_key != $encryption_key)
        {
            $data = [
                'user_id' => $user_id, 'message_id' => $message->id, 'bad_key' => $encryption_key, 'created_at' => $time
            ];
            DB::transaction(function() use($data, $message, &$output) {
                $bad_keys_ins = DB::table('bad_keys')
                    ->insert($data);

                $users_upd = DB::table('users')
                    ->where('id', $message->user_id)
                    ->increment('bad_keys_count');

                $output = 1;
            });
            if ($output == 0)
            {
                \Session::flash('flash_message', 'An error occurred');
                return redirect()->back();
            }

            \Session::flash('flash_message', 'Wrong Encryption Key! Message Decryption Failed');
            return redirect()->back();
        }
        $original_msg = decrypt($message->encrypted_message, $this->cipher, $message->encryption_key, '');

        return view('message', ['message' => $message, 'original_msg' => $original_msg]);
    }

    public function subscribe(Request $request)
    {
        $custom = $this->custom;
        $user_id = \Session::get('uid');
        $ssh = $request->ssh;
        $time = $custom->time_now();
        $output = 0;

        $data = [
            'subscription' => 1, 'updated_at' => $time
        ];
        $message = 'Done! You have been added to our mailing list';
        if ($ssh == 1)
        {
            $data = [
                'subscription' => 0, 'updated_at' => $time
            ];
            $message = 'Done! You will no longer receive messages from us';
        }
        $users_upd = DB::table('users')
            ->where('id', $user_id)
            ->update($data);

        \Session::flash('flash_message_success', $message);
        return redirect()->back();
    }

    public function decrypt($encrypted_msg, $cipher, $key, $tag)
    {
        if (in_array($cipher, openssl_get_cipher_methods()))
        {
            $ivlen = openssl_cipher_iv_length($cipher);
            $iv = openssl_random_pseudo_bytes($ivlen);
            // $ciphertext = openssl_encrypt($plain_msg, $cipher, $key, $options=0, $iv, $tag);
            //store $cipher, $iv, and $tag for decryption later
            $original_msg = openssl_decrypt($encrypted_msg, $cipher, $key, $options=0, $iv, $tag);
            
            return $original_msg;
        }
    }

}
