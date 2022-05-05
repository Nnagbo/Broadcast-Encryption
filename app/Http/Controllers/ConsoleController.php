<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use App\Http\Requests;

use App\User;

use DB;
use App\Libraries\Custom;
use App\Libraries\Transactions;

class ConsoleController extends Controller
{
    protected $custom;
    protected $transactions;

    protected $cipher;
    public function __construct()
    {
        $this->custom = new Custom();
        $this->transactions = new Transactions();

        $this->cipher = "aes-128-gcm";
        $this->middleware('login');
        $priority = User::where('id', \Session::get('uid'))
            ->value('priority');

        \Session::put('priority', $priority);

        if ($priority < 4)
        {
            \Session::flush();
            return redirect('login');
        }
        // var_dump($priority);exit;
    }

    public function bad_keys()
    {
        $bad_keys = DB::table('bad_keys')
            ->join('users', 'bad_keys.user_id', '=', 'users.id')
            ->where('bad_keys.deleted', 0)
            ->groupBy('bad_keys.user_id')
            ->paginate(10);

        return view('bad-keys', ['bad_keys' => $bad_keys]);
    }

    public function broadcast_message(Request $request)
    {
        $custom = $this->custom;
        $subject = $request->subject;
        $message = $request->message;
        // $receiver = $request->ssh;
        $time = $custom->time_now();

        $user_id = \Session::get('uid');

        if (empty($subject) || empty($message))
        {
            \Session::flash('flash_message', 'All fields are required');
            return redirect()->back()->withInput();
        }

       $subscribers = DB::table('users')
            ->where('priority', '<>', '44')
            ->where('subscription', 1)
            ->where('deleted', 0)
            ->get();

        if ($subscribers->isEmpty() )
        {
            \Session::flash('flash_message', 'No subscriber on mailing list');
            return redirect()->back()->withInput();
        }

        foreach ($subscribers as $key => $subscriber)
        {
            $encryption_key = $subscriber->mobile;
            $encrypted_message = encrypt($message, $this->cipher, $encryption_key, '');

            $data = [
                'user_id' => $subscriber->id, 'subject' => $subject, 'message' => $message, 'encrypted_message' => $encrypted_message, 'encryption_key' => $encryption_key, 'created_at' => $time
            ];
            $message_ins = DB::table('messages')
                ->insert($data);
            
            $link = 'You have a new message on '.env('SITE_URL').'. <br /> Use your private key to decrypt and read.';
            $custom->send_generic_email($subscriber->email, $link, 'New message');
        }

        \Session::flash('flash_message_success', 'Message sent');
        return redirect()->back();
    }


    public function index()
    {
        $custom = $this->custom;
        $transactions = $this->transactions;
        $user_id = \Session::get('uid');
        $stats = [];
        // print_r($result);exit;

        $profile = DB::table('users')
            ->where('id', $user_id)
            ->where('deleted', 0)
            ->first();


        $users = DB::table('users')
            ->where('priority', '<>', '44')
            ->where('deleted', 0)
            ->paginate(10);

        $subscribers = DB::table('users')
            ->where('priority', '<>', '44')
            ->where('subscription', 1)
            ->where('deleted', 0)
            ->paginate(10);

        return view('admin', ['users' => $users, 'profile' => $profile, 'subscribers' => $subscribers]);

    }

    public function get_users()
    {
        $custom = $this->custom;
        $transactions = $this->transactions;
        $user_id = \Session::get('uid');
        // print_r($result);exit;

        $profile = DB::table('users')
            ->where('id', $user_id)
            ->where('deleted', 0)
            ->first();

        $users = DB::table('users')
            ->paginate(10);


        return view('console', ['users' => $users, 'profile' => $profile]);
    }

    public function delete_user($email)
    {
        $custom = $this->custom;

        if (empty($email))
        {
            \Session::flash('flash_message', 'An error occurred');
            return redirect('console')->back();
        }

        $check_user = DB::table('users')
            ->where('email', $email)
            ->first();

        if (empty($check_user) )
        {
            \Session::flash('flash_message', 'Email could not be found');
            return redirect('console');
        }
        $custom->user_delete($check_user->id);
        \Session::flash('flash_message_success', 'User deleted');
        return redirect('console');
    }

    public function send_message(Request $request)
    {
        $custom = $this->custom;
        $subject = $request->subject;
        $message = $request->message;
        $receiver = $request->ssh;
        $time = $custom->time_now();

        $user_id = \Session::get('uid');

        if (empty($subject) || empty($message) || empty($receiver))
        {
            \Session::flash('flash_message', 'All fields are required');
            return redirect()->back()->withInput();
        }

        $check_user = DB::table('users')
            ->where('id', $receiver)
            ->where('deleted', 0)
            ->first();

        if (empty($check_user) )
        {
            \Session::flash('flash_message', 'Receiver not found');
            return redirect()->back()->withInput();
        }

        $encryption_key = $check_user->mobile;
        $encrypted_message = encrypt($message, $this->cipher, $encryption_key, '');

        $data = [
            'user_id' => $receiver, 'subject' => $subject, 'message' => $message, 'encrypted_message' => $encrypted_message, 'encryption_key' => $encryption_key, 'created_at' => $time
        ];
        $message_ins = DB::table('messages')
            ->insert($data);

        \Session::flash('flash_message_success', 'Message sent');
        return redirect()->back();
    }

    public function encrypt($plain_msg, $cipher, $key, $tag)
    {
        // $plaintext = "message to be encrypted";
        // $cipher = "aes-128-gcm";
        if (in_array($cipher, openssl_get_cipher_methods()))
        {
            $ivlen = openssl_cipher_iv_length($cipher);
            $iv = openssl_random_pseudo_bytes($ivlen);
            $cipher_msg = openssl_encrypt($plain_msg, $cipher, $key, $options=0, $iv, $tag);
            //store $cipher, $iv, and $tag for decryption later
            // $original_plaintext = openssl_decrypt($ciphertext, $cipher, $key, $options=0, $iv, $tag);
            // echo $original_plaintext."\n";

            return $cipher_msg;
        }
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
