<x-layout doctitle="Create New Post">
    <div class="container psy-md-5 container-narrow">
        <form action="/create-post" method="POST">
            @csrf
            <div class="form-group">
                <label for="post-title" class="text-muted mb-1">
                    <small>Title</small>
                    @error('title')
                        <small class="text-danger">* {{$message}}</small>
                    @enderror
                </label>
                <input value="{{old('title')}}" name="title" id="post-title" type="text" class="form-control form-control-lg form-control-title" placeholder="" autocomplete="off">
            </div>
            <div class="form-group">
                <label for="post-body" class="text-muted mb-1">
                    <small>Body Content</small>
                    @error('body')
                    <small class="text-danger">* {{$message}}</small>
                    @enderror
                </label>
                <textarea name="body" id="post-body" type="text" class="body-content tall-textarea form-control">{{old('body')}}</textarea>
            </div>
            <button class="btn btn-primary">Save New Post</button>
        </form>
    </div>
</x-layout>
