<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

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

        \App\Models\User::factory(3)->create();

        \App\Models\Token::factory()->create([
            'ayuntamiento' => 1,
        ]);

        \App\Models\Token::factory()->create([
            'ayuntamiento' => 0,
        ]);
        
        //Creacion de roles y permisos
        $roleAdmin = Role::create(['name' => 'admin']);
        $roleAyuntamiento = Role::create(['name' => 'ayuntamiento']);
        $roleShop = Role::create(['name' => 'empresa']);
        $roleCustomer = Role::create(['name' => 'cliente']);

        $verPost = Permission::create(['name'=>'SeePost']);
        $editarPost = Permission::create(['name'=>'EditPost']);
  
        $verCustomer = Permission::create(['name'=>'SeeCustomer']);
        $editarCustomer = Permission::create(['name'=>'EditCustomer']);

        $verShop = Permission::create(['name'=>'SeeShop']);
        $editarShop = Permission::create(['name'=>'EditShop']);


        $permisosAdmin = Permission::whereIn('name', [
            'SeePost', 'EditPost', 'SeeCustomer', 'EditCustomer', 'SeeShop', 'EditShop', 
        ])->get();
        $roleAdmin -> syncPermissions($permisosAdmin);

        $permisosCliente = Permission::whereIn('name', [
            'SeePost', 'EditCustomer', 
        ])->get();
        $roleCustomer -> syncPermissions($permisosCliente);

        $permisosEmpresa = Permission::whereIn('name', [
            'SeePost', 'EditPost', 'SeeShop', 'EditShop', 
        ])->get();
        $roleShop -> syncPermissions($permisosEmpresa);

        $userAdmin = User::find(1);
        $userAdmin->assignRole('admin');

        $userCustomer = User::find(2);
        $userCustomer->assignRole('cliente');

        $userShop = User::find(3);
        $userShop->assignRole('empresa');
    }
}
