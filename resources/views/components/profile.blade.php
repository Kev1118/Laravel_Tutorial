<x-layout :doctitle="$doctitle">
    <div class="container">
        <h2>
            <img class="avatar-small" src="{{$sharedData['avatar']}}" alt=""> {{$sharedData['username']}}
            @auth
            @if (!$sharedData['currentlyFollowing'] AND auth()->user()->username != $sharedData['username'])
            <form action="/create-follow/{{$sharedData['username']}}" method="POST" class="ml-2 d-inline">
                @csrf
                <button class="btn btn-primary btn-sm">Follow <i class="fas fa-user-plus"></i></button>
            </form>
            @endif
            @if ($sharedData['currentlyFollowing'])
            <form action="/remove-follow/{{$sharedData['username']}}" method="POST" class="ml-2 d-inline">
                @csrf
                <button class="btn btn-danger btn-sm">Unfollow <i class="fas fa-user-times"></i></button>
            </form>
            @endif
            @if (auth()->user()->username == $sharedData['username'])
                    <a href="/manage-avatar" class="btn btn-secondary btn-sm">Manage Avatar</a>
                @endif
            @endauth
        </h2>

        <div class="profile-nav nav nav-tabs pt-2 mb-4">
            <a href="/profile/{{$sharedData['username']}}" class="profile-nav-link nav-item nav-link {{ Request::segment(3)  == "" ? "active" : ""}}">Post: {{$sharedData['postCount']}}</a>
            <a href="/profile/{{$sharedData['username']}}/followers" class="profile-nav-link nav-item nav-link {{ Request::segment(3)  == "followers" ? "active" : ""}}">Followers: {{$sharedData['followerCount']}}</a>
            <a href="/profile/{{$sharedData['username']}}/following" class="profile-nav-link nav-item nav-link {{ Request::segment(3)  == "following" ? "active" : ""}}">Following: {{$sharedData['followingCount']}}</a>
        </div>

        <div class="profile-slot-content">
            {{$slot}}
        </div>
    </div>
</x-layout>
