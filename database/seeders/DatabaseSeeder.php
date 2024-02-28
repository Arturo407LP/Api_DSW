<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        \App\Models\Municipality::factory(3)->create();

        \App\Models\Category::factory(3)->create();

        \App\Models\Tag::factory(3)->create();

        \App\Models\Posts_Type::factory(3)->create();


        \App\Models\User::factory()->create([
            'name' => 'Admin',
            'email' => 'test@example.com',
            'password' => bcrypt('admin')
        ]);

        \App\Models\Token::factory()->create([
            'ayuntamiento' => 1,
        ]);

        \App\Models\Token::factory()->create([
            'ayuntamiento' => 0,
        ]);
        
        
    }
}
