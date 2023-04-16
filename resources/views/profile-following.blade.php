<x-profile :sharedData="$sharedData" doctitle="Who {{$sharedData['username']}}'s Following">
    @include('profile-following-only')
</x-profile>
