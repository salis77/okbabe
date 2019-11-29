<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Post;
use Illuminate\Http\Request;
use Validator;
class BlogController extends Controller
{
    public $successStatus = 200;

    public function show_posts(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'post_id' => 'required',
        ]);

        if (isset($request->post_id)){
            $posts=Post::find($request->post_id);
            $posts['categories']=$posts->categories()->get();
        }else{
            $show_posts=Post::paginate(10);
            $posts=array();
            foreach ($show_posts as $post){
                $p=$post;
                $p['categories']=$post->categories();
                array_push($posts,$p);
            }
        }
        return response()->json(['success' => $posts], $this-> successStatus);
    }
}
