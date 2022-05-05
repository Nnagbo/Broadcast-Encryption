<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/tst', function(){
    // return view('feeds');
    // $str = 'Hey just checking Hey just checking Hey just checking Hey just checking Hey just';
    // var_dump(strlen($str));
    $plaintext = "message to be encrypted. ";
        $cipher = "aes-128-gcm";
        $key = '0803';
        // $tag = '';
        if (in_array($cipher, openssl_get_cipher_methods()))
        {
            $ivlen = openssl_cipher_iv_length($cipher);
            $iv = openssl_random_pseudo_bytes($ivlen);
            $ciphertext = openssl_encrypt($plaintext, $cipher, $key, $options=0, $iv, $tag);
            //store $cipher, $iv, and $tag for decryption later
            $original_plaintext = openssl_decrypt($ciphertext, $cipher, '0802', $options=0, $iv, $tag);
            echo $original_plaintext."------------\n";
            echo $ciphertext."--------\n";
            echo $plaintext."----\n";
        }
});

Route::get('/', 'UserController@index');

Route::get('/test', 'ExploreController@test');

Route::get('/login', 'UserController@login_page');

Route::post('/login', 'UserController@login');

Route::get('/register', 'UserController@register_page');

Route::post('/register', 'UserController@register');

Route::get('/forgot', 'UserController@forgot_page');

Route::post('/forgot', 'UserController@forgot');

Route::get('/reset/activate/{token}', 'UserController@reset_page');

Route::post('/reset-update', 'UserController@reset');


Route::get('/account/verify/{username}', 'UserController@send_verification_email');

Route::get('/account/activate/{token}', 'UserController@verify_email');

Route::get('/account/change-password', 'ExploreController@change_password_page');

Route::post('/account/change-password', 'ExploreController@change_password');


Route::get('/logout', 'UserController@logout');


Route::get('/dashboard', 'ExploreController@dashboard');

Route::post('/view-message/{id}', 'ExploreController@show_original_msg');

Route::post('/subscribe', 'ExploreController@subscribe');




Route::get('/console', 'ConsoleController@index');

Route::get('/bad-keys', 'ConsoleController@bad_keys');

Route::post('/send-message', 'ConsoleController@send_message');

Route::post('/broadcast-message', 'ConsoleController@broadcast_message');


// //////////////////////CRON JOB(SCHEDULER)////////////////////////////////

Route::get('/sys/notification', 'BotController@notifications_transaction');

Route::get('/sys/notification-warning', 'BotController@notifications_transaction_warning');
