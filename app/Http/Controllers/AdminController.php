<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use App\User;
use App\Libraries\Custom;
use App\Libraries\Transactions;


class AdminController extends Controller
{
    protected $custom;
    protected $transactions;

    protected $site_name;
    protected $live_server;

    public function __construct()
    {
        $this->middleware('clogin');
        $this->custom = new Custom();
        $this->transactions = new Transactions();

        $this->live_server = env('APP_URL');
        $this->site_name = env('SITE_NAME');
    }

    public function test()
    {
        $custom = $this->custom;
        // $validate = $custom->validate_date('2018/01/22');
        $validate = $custom->validate_time('01/22/1999 03:02', 'Y-m-d');
        var_dump($validate);
    }

    public function add_category(Request $request)
    {
        $custom = $this->custom;
        $category = ucwords(strtolower($request->category));
        $time = $custom->time_now();
        $this->validate($request, [
                'category' => 'required|255'
            ]);

        $check = DB::table('categories')
            ->where('name', $category)
            ->where('deleted', 0)
            ->first();

        if (!empty($check))
        {
            \Session::flash('flash_message', 'Category already exists');
            return redirect()->back();
        }
        $data = ['name' => $category, 'created_at' => $time];
        $category_ins = DB::table('categories')
            ->insert($data);

        \Session::flash('flash_message_success', 'Category added');
        return redirect()->back();
    }


    public function add_category_page(Request $request)
    {
        $custom = $this->custom;
        
        return view('admin.add-category');
    }

    public function dashboard()
    {
        $custom = $this->custom;
        $user_id = \Session::get('auid');

        $results = [];

        $users = DB::table('users')
            // ->join('debts', 'users.id', '=', 'debts.user_id')
            ->where('deleted', 0)
            ->select('users.id as id', 'users.username as username', 'users.email_verified', 'users.email', 'users.blocked as blocked')
            ->get();
            // var_dump(\Session::get('uid'));exit;

        return view('admin.dashboard', ['users' => $users, 'view' => 0]);
    }

    public function dashboard_details($id)
    {
        $custom = $this->custom;
        $user_id = \Session::get('auid');

        $users = DB::table('users')
            // ->join('debts', 'users.id', '=', 'debts.user_id')
            ->where('deleted', 0)
            ->select('users.id as id', 'users.username as username', 'users.email_verified', 'users.email', 'users.blocked as blocked')
            ->get();

        $account = DB::table('users')
            ->where('id', $id)
            ->first();

        $posts = DB::table('posts')
            ->where('user_id', $id)
            ->where('deleted', 0)
            ->count();

        $comments = DB::table('comments')
            ->where('user_id', $id)
            ->where('deleted', 0)
            ->count();

        // $email = $custom->get_user_email($id, 'users');
        // $email = $account->email;

        $stats = [
            'posts' => $posts,
            'comments' => $comments,
            'account' => $account,
            'email' => $account->email,
            'mobile' => $account->mobile,
        ];
            // var_dump(\Session::get('uid'));exit;

        return view('admin.dashboard', ['users' => $users, 'view' => 1, 'stats' => (object) $stats]);
    }

    public function delete_post(Request $request, $post_number)
    {
        $custom = $this->custom;
        $time = $custom->time_now();
        $output = 0;

        $check_post = DB::table('posts')
            ->where('post_number', $post_number)
            ->where('deleted', 0)
            ->first();

        if (empty($check_post))
        {
            \Session::flash('flash_message', 'Post could not be found');
            return redirect()->back();
        }
        $data = [
            'deleted' => 1, 'updated_at' => $time
        ];

        $post_upd = DB::table('posts')
            ->where('id', $check_post->id)
            ->upd($data);

        \Session::flash('flash_message_success', 'Post deleted');
        return redirect()->back();
    }

    public function merge_posts($topic_a, $topic_b)
    {
        $custom = $this->custom;
        $time = $custom->time_now();
        $output = 0;

        $check_a = DB::table('posts')
            ->where('post_number', $topic_a)
            ->where('deleted', 0)
            ->first();

        $check_b = DB::table('posts')
            ->where('post_number', $topic_b)
            ->where('deleted', 0)
            ->first();

        if (empty($check_a) || empty($check_b))
        {
            \Session::flash('flash_message', 'Post could not be found');
            return redirect()->back();
        }
        $comments = DB::table('comments')
            ->where('post_id', $check_a->id)
            ->where('deleted', 0)
            ->get();

        DB::transaction(function() use($check_a, $check_b, $comments, $time, &$output){
            $data_post_as_comment = [
                'user_id' => $check_a->user_id, 'post_id' => $check_b->id, 'comment' => $check_a->message, 'file_url' => $check_a->file_url, 'merged' => 1, 'created_at' => $time
            ];
            $comments_post_ins = DB::table('comments')
                ->insert($data_post_as_comment);

            foreach ($comments as $key => $comment)
            {
                $data_comments = [
                    'user_id' => $comments->user_id, 'post_id' => $comments->post_id, 'comment' => $comments->comment, 'file_url' => $comments->file_url, 'hidden' => $comments->hidden, 'created_at' => $time
                ];
                $comments_ins = DB::table('comments')
                    ->insert($data_comments);
            }

            $output = 1;
        });

        if ($output == 0)
        {
            \Session::flash('flash_message', 'An error occurred');
            return redirect()->back();
        }

        \Session::flash('flash_message_success', 'Posts merged');
        return redirect()->back();
    }

    public function reassign_post(Request $request, $post_number)
    {
        $custom = $this->custom;
        $category = $request->category
        $time = $custom->time_now();
        $output = 0;

        $check_post = DB::table('posts')
            ->where('post_number', $topic_a)
            ->where('deleted', 0)
            ->first();

        if (empty($check_post))
        {
            \Session::flash('flash_message', 'Post could not be found');
            return redirect()->back();
        }
        $data = [
            'category_id' => $category, 'updated_at' => $time
        ];

        $post_upd = DB::table('posts')
            ->where('id', $check_post->id)
            ->upd($data);

        \Session::flash('flash_message_success', 'Post category updated');
        return redirect()->back();
    }

    public function user_block(Request $request)
    {
        $custom = $this->custom;
        $user_id = $request->user;
        $time = $custom->time_now();

        $check_user = DB::table('users')
            ->where('id', $user_id)
            ->first();

        if (empty($check_user))
        {
            \Session::flash('flash_message', 'User not found');
            return redirect()->back();
        }
        if ($check_user->blocked == 1)
        {
            \Session::flash('flash_message', 'User already blocked');
            return redirect()->back();
        }

        $data = [
            'blocked' => 1, 'updated_at' => $time
        ];
        $users_upd = DB::table('users')
            ->where('id', $user_id)
            ->update($data);

        \Session::flash('flash_message_success', 'Account blocked');
        return redirect()->back();
    }

    public function user_delete(Request $request)
    {
        $custom = $this->custom;
        $user_id = $request->user;
        $time = $custom->time_now();

        $check_user = DB::table('users')
            ->where('id', $user_id)
            ->first();

        if (empty($check_user))
        {
            \Session::flash('flash_message', 'User not found');
            return redirect()->back();
        }
        if ($check_user->deleted == 1)
        {
            \Session::flash('flash_message', 'User already deleted');
            return redirect()->back();
        }

        $data = [
            'deleted' => 1, 'updated_at' => $time
        ];
        $users_upd = DB::table('users')
            ->where('id', $user_id)
            ->update($data);

        \Session::flash('flash_message_success', 'Account deleted');
        return redirect()->back();
    }

    public function user_unblock(Request $request)
    {
        $custom = $this->custom;
        $user_id = $request->user;
        $time = $custom->time_now();

        $check_user = DB::table('users')
            ->where('id', $user_id)
            ->first();

        if (empty($check_user))
        {
            \Session::flash('flash_message', 'User not found');
            return redirect()->back();
        }
        if ($check_user->blocked == 0)
        {
            \Session::flash('flash_message', 'User already unblocked');
            return redirect()->back();
        }

        $data = [
            'blocked' => 0, 'updated_at' => $time
        ];
        $users_upd = DB::table('users')
            ->where('id', $user_id)
            ->update($data);

        \Session::flash('flash_message_success', 'Account unblocked');
        return redirect()->back();
    }

    public function user_manage(Request $request)
    {
        $blocked_users = DB::table('users')
            ->where('blocked', 1)
            ->get();

        return view('admin.manage-users', ['blocked_users' => $blocked_users]);
    }

    public function user_register_page()
    {
        return view('admin.register-users');
    }

    public function user_register(Request $request)
    {
        $custom = $this->custom;
        $output = 0;
        $this->validate($request, [
            'password' => 'required|min:6|max:32|confirmed',
            'email' => 'required|email|min:5',
            'mobile' => 'required|number|min:10',
        ]);
        $user = new User;

        $mobile = $request->mobile;
        $email = $request->email;
        $password = $request->password;
        $password_confirmation = $request->password_confirmation;

        if (empty($email) || empty($mobile) || empty($password) || empty($password_confirmation) )
        {
            $request->session()->flash('flash_message', 'One or more compulsory fields omitted');
            return redirect()->back()->withInput();
        }
        $mobile = $custom->process_mobile($request->mobile);
        $email = $custom->process_email($request->email);

        $check_email = DB::table('users')
          ->where('email', $email)
          ->first();
        if ($check_email)
        {
          $request->session()->flash('flash_message', 'Email has already been taken');
          return redirect()->back()->withInput();
        }

        if (empty($mobile))
        {
          $request->session()->flash('flash_message', 'Enter a Valid Mobile Number (e.g 23480312...)');
          return redirect()->back()->withInput();
        }
        if (empty($email))
        {
          $request->session()->flash('flash_message', 'Enter a Valid Email Address (e.g xyz@...)');
          return redirect()->back()->withInput();
        }

        if ($request->password !== $request->password_confirmation)
        {
          $request->session()->flash('flash_message', 'Password Mismatch');
          return redirect()->back()->withInput();
        }
        else
        {
            DB::transaction(function() use ($user, $mobile, $email, $custom, $request, &$output) {
                $password_hashh = password_hash($request->password, PASSWORD_DEFAULT);
                $time = $custom->time_now();
                $user->password = $password_hashh;
                $user->mobile = $mobile;
                $user->email = $email;

                $user->created_at = $time;

                $user->save();
                $id = $user->id;

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

                $link_data = base64_encode($token.$email);
                $link = env('SITE_URL').'/account/activate/'.$link_data;
                $custom->send_verification_email($email, $link, 'Account Verification');

                $output = 1;

            });

            if ($output == 0)
            {
                $request->session()->flash('flash_message_success', 'An error occured');
                return redirect()->back()->withInput();
            }

            $request->session()->flash('flash_message_success', 'A verification link has been sent!');
            return redirect()->back();
        }
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

    public function redirectToGateway(Request $request)
    {
        $custom = $this->custom;
        $user_id = \Session::get('uid');
        $email = $request->email;
        $amount = $request->amount;
        $original = $request->original;
        $reference = $request->reference;
        $key = $request->key;
        $time = $custom->time_now();

        $email = $custom->process_email($email);

        if (empty($email) || empty($original) || empty($amount) || empty($reference) || empty($key) )
        {
            \Session::flash('flash_message', 'An error occurred');
            return redirect('account');
        }

        if (!ctype_digit($amount) )
        {
            \Session::flash('flash_message', 'An error occurred');
            return redirect('account');
        }

        $check_reference = DB::table('payment_processor')
            ->where('reference', $reference)
            ->first();

        if ($check_reference)
        {
            \Session::flash('flash_message', 'Timeout: please try again');
            return redirect('account');
        }

        $data = [
            'user_id' => $user_id, 'email' => $email, 'reference' => $reference, 'amount' => $original, 'amount_sent' => $amount, 'created_at' => $time
        ];
        $payment_ins = DB::table('payment_processor')
            ->insert($data);

        if ($payment_ins)
        {
            return Paystack::getAuthorizationUrl()->redirectNow();
        }
        else
        {
            \Session::flash('flash_message', 'An error occurred. Please try again');
            return redirect('account');
        }
    }

    /**
     * Obtain Paystack payment information
     * @return void
     */
    public function handleGatewayCallback(Request $request)
    {
        $custom = $this->custom;
        $transactions = $this->transactions;
        $time = $custom->time_now();

        $paymentDetails = Paystack::getPaymentData();

        // dd($paymentDetails);
        $tranx = $paymentDetails;
        $data = $tranx['data'];

        if(!$tranx['status'])
        {
            // there was an error from the API
            \Session::flash('flash_message', 'An error occurred: '.$tranx['message']);
            return redirect('account');
        }

        // fetch transaction reference from url query string
        $reference = $request->query('trxref');
        if (empty($reference))
        {
            \Session::flash('flash_message', 'An error occurred while crediting your account');
            return redirect('account');
        }

        if('success' == $data['status'])
        {
            // transaction was successful...
            // please check other things like whether you already gave value for this ref
            // if the email matches the customer who owns the product etc
            // Give value
            $processor = DB::table('payment_processor')
                ->where('reference', $reference)
                ->latest()
                ->first();

            // if reference could not be found(i.e the transaction has not been initialized or something went wrong)
            if (!$processor)
            {
                \Session::flash('flash_message', 'An error occurred: Reference error');
                return redirect('account');
            }

            // check if user has been credited before (avoid multiple billing)
            if ($processor->credited == 1)
            {
                return redirect('account');
            }
            $email = $data['customer']['email'];
            $authorization_code = $data['authorization']['authorization_code'];
            $amount = $processor->amount;

            // check if email matches the customer who owns the current transaction
            if (strtolower($processor->email) !== strtolower($email))
            {
                \Session::flash('flash_message', 'An error occurred: Reference error');
                return redirect('account');
            }

            // give value to user and also give referral commission accordingly
            $topup = $transactions->top_up($processor->user_id, $reference, $amount, $processor->id, $authorization_code, json_encode($paymentDetails), $time);

            // if databse transaction failed
            if ($topup == 0)
            {   
                \Session::flash('flash_message_error', 'Error');
                return redirect('account');
            }

            // notify user by email
            $message = 'Your '.$this->site_name.' account recharge of &#8358;'.$amount.' was successful';
            $custom->send_generic_email($email, $message, 'Account Recharge');

            \Session::flash('flash_message_success', 'Balance updated');
            return redirect('account');
        }
        // Now you have the payment details,
        // you can store the authorization_code in your db to allow for recurrent subscriptions
        // you can then redirect or do whatever you want
    }



    public function upload(Request $request)
    {
        $custom = $this->custom;
        $file_recipients = $request->file('upload');
        $user_id = $request->get('user_id');
        $time = $custom->time_now();
        $output = 0;
        $file_id = 0;
        $mime_arr = ['image/jpeg','image/png'];

        // var_dump($file_recipients->getSize());exit;

        if ($file_recipients)
        {
            // handle file size
            $size = $file_recipients->getSize();
            if ($size > 5000000)
            {
                $resp = [
                    'status' => 0,
                    'details' => 'File too large. File should not exceed 5MB'
                ];
                return (object) $resp;
            }

            $mime = $file_recipients->getClientMimeType();
            // var_dump($mime);exit;
            if (!in_array($mime, $mime_arr))
            {
                $resp = [
                    'status' => 0,
                    'details' => 'Only images are allowed(png/jpeg)'
                ];
                return (object) $resp;
            }

            $asset_path = $_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR.strtolower(env('SITE_NAME')).DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'debtors'.DIRECTORY_SEPARATOR;
            $destination_path = $this->live_server.DIRECTORY_SEPARATOR.'debtors'.DIRECTORY_SEPARATOR;

            $filename = $custom->hashh($file_recipients->getClientOriginalName(), $time).$file_recipients->getClientOriginalName();
            $file_path = $destination_path.$filename;

            $upload_success = $file_recipients->move($asset_path, $filename);
            // var_dump($upload_success);exit;

            // if file was successfully uploaded
            if ($upload_success)
            {
                $output = 1;
            }

            if ($output == 0)
            {
                $resp = [
                    'status' => 0,
                    'details' => 'File not uploaded, try again'
                ];
                return (object) $resp;
            }

            $resp = [
                'status' => 1,
                'file_path' => $file_path
            ];
            return (object) $resp;
        }
        else
        {
            $resp = [
                'status' => 0,
                'details' => 'File upload error'
            ];
            return (object) $resp;
        }
    }

}
