<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        \App\Models\User::factory()->create([
            'name' => 'Test User',
            'email' => 'hoge@hoge.com',
            'password' => Hash::make('hogehoge'),
        ]);

        DB::table('categories')->insert([
            ['name' => '食費'],
            ['name' => '交通費'],
            ['name' => '家賃'],
            ['name' => '電気代'],
            ['name' => 'ガス代'],
            ['name' => '水道代'],
            ['name' => 'その他'],
        ]);
    }
}
