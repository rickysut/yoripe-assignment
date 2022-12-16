<?php

namespace App\Http\Controllers\Api;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class PostsController extends Controller
{
    
    public function index()
    {
        $user = Auth::user();
        if ($user){
            if (($user->role == 'admin') || ($user->role == 'manager')){
                return  response()->json([
                    'message' => 'Posts list',
                    'data' => Post::with('user')->select()->get()],
                    200
                );  
            } else 
            if ($user->role == 'user'){
                return  response()->json([
                    'message' => 'Posts list',
                    'data' => Post::with('user')->where('user_id', $user->id)->select()->get()],
                    200
                ); 
            }
                   
        } else return response()->json([
            'message' => 'Unauthorized'
        ], 401);
    }

    

    
    public function create(Request $request)
    {
        
        if (Auth::user()){

            $regdata = [
                'title'         => $request->title,
                'body'          => $request->body,
                'user_id'       => Auth::user()->id,
            ];
            $pic_path = '';
            if  ($request->picture !=null){
                $postid = Carbon::now()->format('dmYHis');
                $file_name = 'post_'.$postid.'_'. Auth::user()->id . '.' .$request->picture->getClientOriginalExtension();
                $file_path = $request->picture->storeAs('uploads', $file_name, 'public');
                $pic_path = $file_path;
                $regdata += array('picture' => $pic_path);
            };

            $post = Post::create($regdata);

            return  response()->json([
                'message' => 'Post Created',
                'data' => $post->load([])->where('id', $post->id)->with('user')->get()],
                200
            ); 
        } else return response()->json([
            'message' => 'Unauthorized'
        ], 401);
    }

    
    public function show($id)
    {
        $user = Auth::user();
        if ($user){
            $post=Post::find($id);
            if ($post){
                if (($post->user_id == $user->id) || (Auth::user()->role == 'admin') || (Auth::user()->role == 'manager')){
                    return  response()->json([
                        'message' => 'Post show',
                        'data' => $post->load([])->where('id', $post->id)->with('user')->get()],
                        200
                    ); 
                } else {
                    return response()->json([
                        'message' => 'Not allow to view post'
                    ], 401);    
                }
            } else return response()->json([
                'message' => 'Post not found'
            ], 404);
        } else return response()->json([
            'message' => 'Unauthorized'
        ], 401);
        
    }

    
    
    public function update(Request $request,  $postId)
    {
        $user = Auth::user();
        if ($user){
            $post = Post::find($postId) ;
            if ($post) {
                if (($post->user_id == $user->id) || (Auth::user()->role == 'admin') || (Auth::user()->role == 'manager')){
                    $regdata = [
                        'title'         => $request->title,
                        'body'          => $request->body,
                    ];
                    $pic_path = '';
                    if  ($request->picture !=null){
                        $pid = Carbon::now()->format('dmYHis');
                        $file_name = 'post_'.$pid.'_'. Auth::user()->id . '.' .$request->picture->getClientOriginalExtension();
                        $file_path = $request->picture->storeAs('uploads', $file_name, 'public');
                        $pic_path = $file_path;
                        $regdata += array('picture' => $pic_path);
                    };
                    $post->update($regdata);

                    return response()->json([
                        'message' => 'Post updated',
                        'data' => $post->load([])->where('id', $post->id)->with('user')->get()], 
                        200
                    );
                } else {
                    return response()->json([
                        'message' => 'Not allow to update post'
                    ], 401);    
                }
            } else {
                return response()->json([
                    'message' => 'Post not found'
                ], 404);
            }
        } else return response()->json([
                        'message' => 'Unauthorized'
                    ], 401);
        
    }

    
    public function delete($id)
    {
        $user = Auth::user();
        if ($user){
            $post = Post::find($id) ;
            if ($post) {
                if (($post->user_id == $user->id) || (Auth::user()->role == 'admin') || (Auth::user()->role == 'manager')){
                    $post->delete();
                    return response()->json([
                        'message' => 'Post deleted'
                    ], 200);
                }  else {
                    return response()->json([
                        'message' => 'Not allow to delete post'
                    ], 401);    
                }
                
            } else {
                return response()->json([
                    'message' => 'Post not found'
                ], 404);
            }
        }
        else return response()->json([
                        'message' => 'Unauthorized'
                    ], 401);
    }
}
