<?php

namespace App\Http\Controllers;

use App\Jobs\SendNewPostEmail;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function search($term){
        $posts = Post::search($term)->get();
        $posts->load('user:id,username,avatar');
        return $posts;
    }

    public function actuallyUpdate(Post $post, Request $request){
        $incommingField = $request->validate([
            'title' => 'required',
            'body' => 'required'
        ]);
        $incommingField['title'] = strip_tags($incommingField['title']);
        $incommingField['body'] = strip_tags($incommingField['body']);

        $post->update($incommingField);
        return back()->with('success', 'Post successfully updated!');
    }
    public function showEditForm(Post $post){
        return view('edit-post',['post' => $post]);
    }

    public function delete(Post $post){
        // if(auth()->user()->cannot('delete',$post)){
        //     return 'You cannot do that';
        // }
        $post->delete();
        return redirect('/profile/'. auth()->user()->username)->with('success','Post successfully deleted!');
    }

    public function viewSinglePost(Post $post){
        $post['body'] = strip_tags(Str::markdown($post->body), '<p><ul><ol><li><strong><em><h3><br>');
        return view('single-post', ['post' => $post]);
    }

    public function storeNewPost(Request $request){
        $incommingField = $request->validate([
            'title' => 'required',
            'body' => 'required'
        ]);
        $incommingField['title'] = strip_tags($incommingField['title']);
        $incommingField['body'] = strip_tags($incommingField['body']);
        $incommingField['user_id'] = auth()->id();

        $newPost = Post::create($incommingField);

        dispatch(new SendNewPostEmail([
            'sendTo' => auth()->user()->email,
            'title' => $newPost->title,
            'name' => auth()->user()->username
        ]));



        return redirect("/post/{$newPost->id}")->with('success', 'New Post successfully created!');
    }
    public function showCreateForm(){
        return view('create-post');
    }
}
