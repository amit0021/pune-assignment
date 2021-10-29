<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use Image;

use App\Models\User;
use App\Models\Post;
use Illuminate\Support\Facades\Validator;
use Auth;
use App\Http\Resources\PostResource;

class PostController extends Controller
{
    public function create(Request $request){
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'slug' => 'required|unique:posts',
            'image' => 'required',
            'description'=>'required'
        ]);
       
        if($validator->fails()) {
            // return redirect()->back()->withErrors($validator)->withInput();
             $data = [
                'message'=>$validator->errors(),
                ];
            return response($data,400);
        }
        $unique_id = Str::random(11);
        Image::make($request->image)->fit(720, 480)->save(storage_path().'/app/public/posts/'.$unique_id.".png");
        $data = [
            'title'=>$request->title,
            'slug'=>Str::of($request->slug)->slug('-'),
            'body'=>$request->description,
            'image_path'=> 'posts/'.$unique_id.'.png',
            'user_id'=>auth()->user()->id
        ];

        $post = Post::create($data);

      

        return response([
            'success'=>true,
            'message'=>'posted',
            'post'=>$post
        ]);


    }


    public function edit(Request $request){
        $validator = Validator::make($request->all(), [
            'post_id'=>'required',
            'title' => 'sometimes',
            'slug' => 'sometimes|unique:posts',
            'image' => 'sometimes',
            'description'=>'sometimes'
        ]);
       
        if($validator->fails()) {
            // return redirect()->back()->withErrors($validator)->withInput();
             $data = [
                'message'=>$validator->errors(),
                ];
            return response($data,400);
        }

        $data = [];
        if($request->title){
            $data += ['title'=>$request->title];
        }

        if($request->slug){
            $data += ['slug'=>Str::of($request->slug)->slug('-')];
        }

        $unique_id = Str::random(11);
        Image::make($request->image)->fit(720, 480)->save(storage_path().'/app/public/posts/'.$unique_id.".png");

        if($request->image){
            $data += [ 'image_path'=> 'posts/'.$unique_id.'.png'];
        }

        if($request->description){
            $data += [ 'body'=> $request->description];

        }

        $post = Post::find($request->post_id)->update($data);

        return response('Post Updated Successfully',200);

    }

    public function delete(Request $request){
        $validator = Validator::make($request->all(), [
            'post_id'=>'required',
        ]);
       
        if($validator->fails()) {
            // return redirect()->back()->withErrors($validator)->withInput();
             $data = [
                'message'=>$validator->errors(),
                ];
            return response($data,400);
        }
        
        $post = Post::findOrFail($request->post_id)->delete();
        return response('Post deleted Successfully',200);

    }

    public function viewAllPosts(Request $request){
        return PostResource::collection(Post::all());
    }
}
