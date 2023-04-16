<?php

namespace Database\Seeders;

use App\Models\Follow;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // DB::table('users')->insert([
        //     'username' => Str::random(8),
        //     'email' => Str::random(10).'@gmail.com',
        //     'password' => Hash::make('password')
        // ]);
        User::factory(10)
            ->has(Post::factory()->count(2),'posts')
            ->has(Follow::factory()->count(3),'followers')
                ->create();
    }
}
