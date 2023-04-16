<?php

namespace App\Http\Controllers;

use App\Events\OurExampleEvent;
use Illuminate\Http\Request;
use App\Models\Follow;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Illuminate\Validation\Rule;
use Intervention\Image\Facades\Image;

class UserController extends Controller
{
    public function storeAvatar(Request $request){
        $request->validate([
            'avatar' => 'required|image|max:3000'
        ]);
        $user = auth()->user();
        $filename = $user->id . '-' . uniqid() . '.jpg';

        $imgData = Image::make($request->file('avatar'))->fit(120)->encode('jpg');
        Storage::put('public/avatars/'.$filename,$imgData);

        $oldAvatar = $user->avatar;

        $user->avatar = $filename;
        $user->save();

        if($oldAvatar != "/fallback-avatar.jpeg"){
            Storage::delete(str_replace("/storage/","public/",$oldAvatar));
        }
        return back()->with("success", "New Avatar Uploaded");
        // $request->file('avatar')->store('public/avatars');
    }
    public function showAvatarForm(){
        return view('avatar-form');
    }

    private function getSharedData($user){
        $currentlyFollowing = 0;
        if(auth()->check()){
            $currentlyFollowing = Follow::where([['user_id','=',auth()->user()->id],['followeduser','=',$user->id]])->count();
        }

        View::share('sharedData',[
            'currentlyFollowing' => $currentlyFollowing,
            'avatar'=>$user->avatar,
            'username' => $user->username,
            'postCount' => $user->posts()->count(),
            'followerCount' => $user->followers()->count(),
            'followingCount' => $user->followingTheseUsers()->count()
        ]);
    }

    public function profile(User $user){
        $this->getSharedData($user);
        return view('profile-posts',[
            'posts' => $user->posts()->latest()->get(),
        ]);
    }

    public function profileFollowing(User $user){
        $this->getSharedData($user);
        return view('profile-following',[
            'following' => $user->followingTheseUsers()->latest()->get()
        ]);
    }

    public function profileFollowers(User $user){
        $this->getSharedData($user);

        return view('profile-followers',[
            'followers' => $user->followers()->latest()->get()
        ]);
    }

    public function showCorrectHomepage(){
       if(auth()->check()){
        return view('homepage-feed', ['posts' => auth()->user()->feedPosts()->latest()->paginate(4)]);
       }else{
        return view('homepage');
       }
    }

    public function login(Request $request){
        $incommingFields = $request->validate([
            'loginusername' => 'required',
            'loginpassword' => 'required'
        ]);

        if (auth()->attempt([
            'username' => $incommingFields['loginusername'],
            'password' => $incommingFields['loginpassword']
            ])){
                $request->session()->regenerate();
                event(new OurExampleEvent(['username' => auth()->user()->username,'action'=>'login']));
                return redirect('/')->with('success', 'You have successfully logged in.');
        }else{
            return redirect('/')->with('failure','Invalid credentials');
        }
    }

    public function register(Request $request){
        $incommingFields = $request->validate([
            'username' => ['required', 'min:3','max:20', Rule::unique('users','username')],
            'email' => ['required','email', Rule::unique('users', 'email')],
            'password' => ['required','min:8', 'confirmed']
        ]);

        $incommingFields['password'] = bcrypt($incommingFields['password']);
        $user = User::create($incommingFields);
        auth()->login($user);
        return redirect('/')->with('success','Account has been successfully created!');
    }

    public function logout(){
        event(new OurExampleEvent(['username' => auth()->user()->username,'action'=>'logout']));
        auth()->logout();
        return redirect('/')->with('success', 'You are now logged out.');
    }
}
