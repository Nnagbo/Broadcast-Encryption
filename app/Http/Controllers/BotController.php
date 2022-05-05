<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use App\User;
use App\Libraries\Custom;
use App\Libraries\Transactions;


class BotController extends Controller
{
    protected $custom;
    protected $transactions;

    protected $site_name;
    protected $live_server;

    public function __construct()
    {
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

    public function notifications_transaction()
    {
        $custom = $this->custom;
        $time = $custom->time_now();
        
        $start = $custom->time_now();
        $timeout = $custom->timeout($start, 20);
        // var_dump($timeout);exit;
//         if runtime is less than x seconds, keep running
        while($custom->time_now() < $timeout)
        {
            $debts = DB::table('debts')
                ->where('start_date', '<', $time)
                ->where('notified', '<', 1)
                ->where('deleted', 0)
                ->get();

            if ($debts->isEmpty())
            {
                exit;
            }
            foreach ($debts as $key => $debt)
            {
                $debtor_email = $debt->email;
                $debtor_mobile = $debt->mobile;

                // $creditor = DB::table('users')
                //     ->where('id', $debt->user_id)
                //     ->first();

                // $creditor_email = $creditor->email;
                // $creditor_mobile = $creditor->mobile;

                $password = 'abcdEF';

                $link = env('SITE_URL').'/my-debts';
                $link_data = '<p>Please follow the URL below to settle a transaction you are involved in<br />'.
                
                $link.'</p>
                <p>Your login details are:<br />
                Email: '.$debtor_email.'<br />
                Password: '.$password.'<br />
                </p><br /><br />
                <p>If clicking the URL above does not work, copy and paste the URL into a browser window.</p>';
                $custom->send_generic_email($debtor_email, $link_data, 'You have a transaction on Debtorsbook');

                $message = 'You have a transaction on Debtorsbook. Check your email for more details';
                $deverloper_id = '22152363';
                $password = 'ChangeMe123';

                // do not send SMS if mobile is non nigerian
                $mobile = $custom->process_mobile($debtor_mobile);
                // var_dump($mobile);exit;

                if (!empty($mobile))
                {
                    $send_mobile = $custom->send_mobile_notification($deverloper_id, $password, 0, $debtor_mobile, 'Debtorsbook', $message);
                }

                $data = ['notified' => 1, 'updated_at' => $time];
                $debts_upd = DB::table('debts')
                    ->where('id', $debt->id)
                    ->update($data);
            }
        }
    }

    public function notifications_transaction_warning()
    {
        $custom = $this->custom;
        $time = $custom->time_now();
        
        $start = $custom->time_now();
        $timeout = $custom->timeout($start, 20);

        $mins = 3 * 24 * 60;
        $mins2 = 2 * 24 * 60;

        $time_diff = $custom->minute_add($custom->time_now(), $mins);
        $time_diff2 = $custom->minute_add($custom->time_now(), $mins2);
        // var_dump($timeout);exit;
//         if runtime is less than x seconds, keep running
        while($custom->time_now() < $timeout)
        {
            $debts = DB::table('debts')
                ->where('payment_date', '<', $time_diff)
                //->where('payment_date', '>=', $time_diff2)
                ->where('notified', '<', 2)
                ->where('deleted', 0)
                ->get();

            if ($debts->isEmpty())
            {
                exit;
            }
            foreach ($debts as $key => $debt)
            {
                $debtor_email = $debt->email;
                $debtor_mobile = $debt->mobile;

                $password = 'abcdEF';

                $link = env('SITE_URL').'/my-debts';
                $link_data = '<p>Please follow the URL below to settle a transaction you are involved in<br />'.
                
                $link.'</p>
                <p>Your login details are:<br />
                Email: '.$debtor_email.'<br />
                Password: '.$password.'<br />
                </p><br /><br />
                <p>If clicking the URL above does not work, copy and paste the URL into a browser window.</p>';
                $custom->send_generic_email($debtor_email, $link_data, 'You have a transaction to settle within 3 days on Debtorsbook');

                $message = 'You have a transaction to settle within 3 days on Debtorsbook. Check your email for more details';
                $deverloper_id = '22152363';
                $password = 'ChangeMe123';

                // do not send SMS if mobile is non nigerian
                $mobile = $custom->process_mobile($debtor_mobile);

                if (!empty($mobile))
                {
                    $send_mobile = $custom->send_mobile_notification($deverloper_id, $password, 0, $mobile, 'Debtorsbook', $message);
                }

                $data = ['notified' => 2, 'updated_at' => $time];
                $debts_upd = DB::table('debts')
                    ->where('id', $debt->id)
                    ->update($data);
                var_dump($send_mobile);exit;
            }
        }
    }

}
