<?php

namespace Database\Seeders;

use App\Models\User;

use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name'=>'test',
            'email'=>'test@example.com',
            'password'=>Hash::make('password'),
        ]);

        $faker = Faker::create('ja_JP'); // 日本語ロケール
        for ($i = 0; $i < 5; $i++) {
        User::create([
            'name' => $faker->name,
            'email' => $faker->unique()->safeEmail,
            'password' => Hash::make('password'), // 全員同じパスワードにしておくとテストしやすい
        ]);
        }
    }
}