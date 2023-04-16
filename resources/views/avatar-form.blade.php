<x-layout doctitle="Manage Your Avatar">
<div class="container container--narrow py-md-5">
    <h2 class="test-center mb-3">Update a New Avatar</h2>
    <form action="/manage-avatar" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="mb-3">
        <input type="file" name="avatar" required>
        @error('avatar')
            <p class="alert alert-danger small shadow-sm">{{$message}}</p>
        @enderror
    </div>
    <button class="btn btn-primary">Save</button>
    </form>

</div>
</x-layout>
