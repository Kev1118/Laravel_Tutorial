<x-layout doctitle="Editing: {{$post->title}}">
    <div class="container psy-md-5 container-narrow">
        <form action="/post/{{$post->id}}" method="POST">
            <p><small><strong><a href="/post/{{$post->id}}">&laquo; Back to post</a></strong></small></p>
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="post-title" class="text-muted mb-1">
                    <small>Title</small>
                    @error('title')
                        <small class="text-danger">* {{$message}}</small>
                    @enderror
                </label>
                <input value="{{old('title',$post->title)}}" name="title" id="post-title" type="text" class="form-control form-control-lg form-control-title" placeholder="" autocomplete="off">
            </div>
            <div class="form-group">
                <label for="post-body" class="text-muted mb-1">
                    <small>Body Content</small>
                    @error('body')
                    <small class="text-danger">* {{$message}}</small>
                    @enderror
                </label>
                <textarea name="body" id="post-body" type="text" class="body-content tall-textarea form-control">{{old('body', $post->body)}}</textarea>
            </div>
            <button class="btn btn-primary">Save Changes</button>
        </form>
    </div>
</x-layout>
