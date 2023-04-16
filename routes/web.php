<?php

use App\Http\Controllers\FollowController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use Illuminate\Auth\Access\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Events\ChatMessage;


Route::get('/admin-only',function () {
    // if(Gate::allows('visitAdminPages')){
    //      return 'Only admin should be able to see this text';
    // }
    // return 'You cannot view this page';
    return 'Only admin should be able to see this text';
})->middleware('can:visitAdminPages');
// User Related routes
Route::get('/', [UserController::class, 'showCorrectHomepage'])->name('login');
Route::post('/register', [UserController::class, 'register'])->middleware('guest');
Route::post('/login', [UserController::class, 'login'])->middleware('guest');
Route::post('/logout', [UserController::class, 'logout'])->middleware('mustBeLoggedIn');
Route::get('/manage-avatar',[UserController::class, 'showAvatarForm'])->middleware('mustBeLoggedIn');
Route::post('/manage-avatar',[UserController::class, 'storeAvatar'])->middleware('mustBeLoggedIn');

//Follow related Routes
Route::post('/create-follow/{user:username}', [FollowController::class, 'createFollow'])->middleware('mustBeLoggedIn');
Route::post('/remove-follow/{user:username}', [FollowController::class, 'removeFollow'])->middleware('mustBeLoggedIn');

//Blog Post related routes
Route::get('/create-post', [PostController::class, 'showCreateForm'])->middleware('mustBeLoggedIn');
Route::post('/create-post', [PostController::class, 'storeNewPost'])->middleware('mustBeLoggedIn');
Route::get('/post/{post}', [PostController::class, 'viewSinglePost']);
Route::delete('/post/{post}', [PostController::class, 'delete'])->middleware('can:delete,post');
Route::get('/post/{post}/edit',[PostController::class, 'showEditForm'])->middleware('can:update,post');
Route::put('/post/{post}',[PostController::class,'actuallyUpdate'])->middleware('can:update,post');
Route::get('/search/{term}', [PostController::class, 'search']);


// PROFILE RELATE ROUTES
Route::get('/profile/{user:username}',[UserController::class, 'profile']);
Route::get('/profile/{user:username}/followers',[UserController::class, 'profileFollowers']);
Route::get('/profile/{user:username}/following',[UserController::class, 'profileFollowing']);

Route::middleware('cache.headers:public;max_age=20;etag')->group(function (){
    Route::get('/profile/{user:username}/raw',[UserController::class, 'profileRaw']);
    Route::get('/profile/{user:username}/followers/raw',[UserController::class, 'profileFollowersRaw']);
    Route::get('/profile/{user:username}/following/raw',[UserController::class, 'profileFollowingRaw']);
});



// CHAR ROUTE
Route::post('/send-chat-message', function(Request $request){
 $formFields = $request->validate([
    'textvalue' => 'required'
 ]);
 if(!trim(strip_tags($formFields['textvalue']))){
    return response()->noContent();
 }

 broadcast(new ChatMessage([
    'username' =>auth()->user()->username,
    'textvalue' => strip_tags($request->textvalue),
    'avatar' => auth()->user()->avatar
    ]))->toOthers();

 return response()->noContent();

})->middleware('mustBeLoggedIn');
