<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use App\User;
use App\Libraries\Custom;

class PagesController extends Controller
{
	protected $custom;
    protected $transactions;

    protected $site_name;
    protected $live_server;

    public function __construct()
    {
        // $this->middleware('login')->except('help');
        $this->custom = new Custom();

        $this->live_server = env('APP_URL');
        $this->site_name = env('SITE_NAME');
    }

    public function add_post()
    {
        $categories = DB::table('categories')
            ->where('deleted', 0)
            ->get();
        return view('new-topic', ['categories' => $categories]);
    }

    public function hide_comment($comment_id)
    {
        $custom = $this->custom;
        $user_id = \Session::get('uid');
        $time = $custom->time_now();

        $check_comment = DB::table('comments')
            ->where('id', $comment_id)
            ->where('user_id', $user_id)
            ->where('deleted', 0)
            ->first();

        if (empty($check_comment))
        {
            \Session::flash('flash_message', 'Comment could not be found');
            return redirect()->back();
        }
        $data = ['hidden' => 1, 'updated_at' => $time];
        $comments_upd = DB::table('comments')
            ->where('id', $comment_id)
            ->update($data);

        \Session::flash('flash_message_success', 'Success');
        return redirect()->back();
    }

    public function like_post($post_number)
    {
        $custom = $this->custom;
        $user_id = \Session::get('uid');
        $like = 1;
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

        $posts_likes = DB::table('posts_likes')
            ->where('post_id', $check_post->id)
            ->where('user_id', $user_id)
            ->first();

        if (empty($posts_likes))
        {
            $data = [
                'user_id' => $user_id, 'post_id' => $check_post->id, 'like' => $like, 'created_at' => $time
            ];
            DB::transaction(function() use($data, $check_post, &$output) {
                $likes_ins = DB::table('posts_likes')
                    ->insert($data);

                $posts_upd = DB::table('posts')
                    ->where('id', $check_post->id)
                    ->increment('likes_count');

                $output = 1;
            });
        }
        else if (!empty($posts_likes))
        {
            $data = [
                'like' => $like, 'updated_at' => $time
            ];
            DB::transaction(function() use($data, $user_id, $check_post, &$output) {
                $likes_upd = DB::table('posts_likes')
                    ->where('post_id', $check_post->id)
                    ->where('user_id', $user_id)
                    ->update($data);

                $posts_upd = DB::table('posts')
                    ->where('id', $check_post->id)
                    ->increment('likes_count');

                $output = 1;
            });
        }
        
        if ($output == 0)
        {
            \Session::flash('flash_message', 'An error occurred');
            return redirect()->back();
        }

        // $likes_count = DB::table('posts')
        //     ->where('post_number', $post_number)
        //     ->where('deleted', 0)
        //     ->first(['likes_count']);

        // $resp = [
        //     'status' => 1,
        //     'likes_count' => $likes_count
        // ];

        \Session::flash('flash_message_success', 'Success');
        return redirect()->back();
    }

    public function recent_topics()
    {
        $posts = DB::table('posts')
            ->where('deleted', 0)
            ->orderBy('id', 'DESC')
            ->take(5)
            ->get();
    }

    public function reply_post(Request $request, $post_number)
    {
        $custom = $this->custom;
        $user_id = \Session::get('uid');
        // $title = $request->title;
        $comment = $request->comment;
        // $category = $request->category;
        $attachment = $request->attachment;
        $time = $custom->time_now();

        $file_path = '';

        $this->validate($request, [
            'message' => 'required'
        ]);

        $check_post = DB::table('posts')
            ->where('post_number', $post_number)
            ->first();

        if (empty($check_post))
        {
            \Session::flash('flash_message', 'Post could not be found');
            return redirect()->back();
        }

        // process file
        if(! empty($attachment))
        {
            $file_response = $this->upload($request);
            // $file_id = 0;
            if ($file_response->status == 0)
            {
                \Session::flash('flash_message', $file_response->details);
                return redirect()->back();
            }
            // $file_id = $file_response->file_id;
            $file_path = $file_response->file_path;
        }
        
        $post_number = $custom->generate_post_number('posts', 'post_number');
        $title_url = str_replace(' ', '-', $title);

        $data_comments = [
            'user_id' => $user_id, 'post_id' => $check_post->id, 'comment' => $comment, 'file_url' => $file_path, 'created_at' => $time
        ];
        $comments_ins = DB::table('comments')
            ->insert($data_comments);

        \Session::flash('flash_message_success', 'Comments added');
        return redirect()->back();
    }

    public function search(Request $request)
    {
        $custom = $this->custom;
        $query = $request->query;

        $queryStr = '%'.$query.'%';
        $posts = DB::table('posts')
            ->where('title', 'like', $queryStr)
            ->where('deleted', 0)
            ->paginate(10);

        return view('search-results', ['posts' => $posts]);
    }

    public function show_post($post_number)
    {
        $post = DB::table('posts')
            ->where('post_number', $post_number)
            ->where('deleted', 0)
            ->first();

        if (empty($post))
        {
            \Session::flash('flash_message', 'Post does not exist');
            return redirect()->back();
        }

        $comments = DB::table('comments')
            ->where('post_id', $post->id)
            ->where('deleted', 0)
            ->paginate(10);

        return view('post', ['post' => $post, 'comments' => $comments]);
    }

    public function show_posts_by_alpha($alpha_letter = 'a')
    {
        $queryStr = $alpha_letter.'%';
        $posts = DB::table('posts')
            ->where('title', 'like', $queryStr)
            ->where('deleted', 0)
            ->get();

        return view('feeds-alphabetically', ['posts' => $posts]);
    }

    public function show_posts_by_category($category)
    {
        $posts = DB::table('posts')
            ->where('category_id', $category)
            ->where('deleted', 0)
            ->paginate(10);

        return view('posts-by-category', ['posts' => $posts]);
    }

    public function trending_topics()
    {

        $trending = DB::table('posts')
            ->where('deleted', 0)
            ->orderBy('comments_count', 'DESC')
            ->take(10)
            ->get();

        return view('hot-topics', ['trending' => $trending]);
    }

    public function help()
    {
        return view('help');
    }

    public function unhide_comment($comment_id)
    {
        $custom = $this->custom;
        $user_id = \Session::get('uid');
        $time = $custom->time_now();

        $check_comment = DB::table('comments')
            ->where('id', $comment_id)
            ->where('user_id', $user_id)
            ->where('deleted', 0)
            ->first();

        if (empty($check_comment))
        {
            \Session::flash('flash_message', 'Comment could not be found');
            return redirect()->back();
        }
        $data = ['hidden' => 0, 'updated_at' => $time];
        $comments_upd = DB::table('comments')
            ->where('id', $comment_id)
            ->update($data);
        
        \Session::flash('flash_message_success', 'Success');
        return redirect()->back();
    }

    public function unlike_post($post_number)
    {
        $custom = $this->custom;
        $user_id = \Session::get('uid');
        // $like = $request->like;
        $like = 0;
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

        $posts_likes = DB::table('posts_likes')
            ->where('post_id', $check_post->id)
            ->where('user_id', $user_id)
            ->first();

        if (empty($posts_likes))
        {
            \Session::flash('flash_message', 'Record not found');
            return redirect()->back();
        }

        $data = [
            'like' => $like, 'updated_at' => $time
        ];
        DB::transaction(function() use($data, $user_id, $check_post, &$output) {
            $likes_upd = DB::table('posts_likes')
                ->where('post_id', $check_post->id)
                ->where('user_id', $user_id)
                ->update($data);

            $posts_upd = DB::table('posts')
                ->where('id', $check_post->id)
                ->decrement('likes_count');

            $output = 1;
        });
        if ($output == 0)
        {
            \Session::flash('flash_message', 'An error occurred');
            return redirect()->back();
        }

        // $likes_count = DB::table('posts')
        //     ->where('post_number', $post_number)
        //     ->where('deleted', 0)
        //     ->first(['likes_count']);

        // $resp = [
        //     'status' => 1,
        //     'likes_count' => $likes_count
        // ];

        \Session::flash('flash_message_success', 'Success');
        return redirect()->back();
    }


    public function upload(Request $request)
    {
        $custom = $this->custom;
        $file_recipients = $request->file('attachment');
        $user_id = \Session::get('uid');
        $time = $custom->time_now();
        $output = 0;
        $file_id = 0;

        $mime_arr = ['video/x-flv','video/mp4', 'application/x-mpegURL', 'video/MP2T', 'video/3gpp', 
            'video/quicktime', 'video/x-msvideo', 'video/x-ms-wmv', 'video/mp4v-es', 'video/mpeg', 
            'video/mpeg-2', 'video/mpeg4', 'video/ogg', 'video/ogm', 'video/avi', 'application/octet-stream'];

        // var_dump($file_recipients->getSize());exit;

        if ($file_recipients)
        {
            // handle file size
            $size = $file_recipients->getSize();
            if ($size > 8000000)
            {
                $resp = [
                    'status' => 0,
                    'details' => 'File too large. File should not exceed 8MB'
                ];
                return (object) $resp;
            }

            $mime = $file_recipients->getClientMimeType();
//             var_dump($mime);exit;
            if (!in_array($mime, $mime_arr))
            {
                $resp = [
                    'status' => 0,
                    'details' => 'Only media files are allowed(mp4/mp3/avi/3gp)'
                ];
                return (object) $resp;
            }

            $path = '/uploads/';
            $asset_path = $_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR.'blogger-files'.DIRECTORY_SEPARATOR;
            $destination_path = $this->live_server.DIRECTORY_SEPARATOR.'blogger-files'.DIRECTORY_SEPARATOR;
            // $destination_path = $asset_path;
            // $destination_path = public_path().$path;
            $filename = $custom->hashh($file_recipients->getClientOriginalName(), $time);
            $file_path = $destination_path.$filename;
            // $file_path = $path.$filename;

            // $asset_path = $_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR.'krysta'.DIRECTORY_SEPARATOR;
            // $destination_path = $_SERVER['SERVER_NAME'].DIRECTORY_SEPARATOR.'krysta'.DIRECTORY_SEPARATOR;

            $upload_success = $file_recipients->move($asset_path, $filename);
            // var_dump($upload_success);exit;

            // if file was successfully uploaded
            if ($upload_success)
            {
                // $file_path = $upload_success['url'];
                DB::transaction(function() use($file_path, $user_id, $mime, $time, &$file_id, &$output) {

                    $data_file = [
                        'user_id' => $user_id, 'file_type' => $mime, 'file_path' => $file_path, 'created_at' => $time
                    ];

                    $file_id = DB::table('files')
                        ->insertGetId($data_file);

                    $output = 1;
                });
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
                'file_id' => $file_id,
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
