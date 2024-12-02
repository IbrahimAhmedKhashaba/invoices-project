<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class CreateAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $user = User::create([
            'name' => 'Ibrahim Khashaba',
            'email' => 'hematrezeguet1@gmail.com',
            'password' => Hash::make('12345678'),
            'roles_name' => ['owner'],
            'status' => 'مفعل',
        ]);

        $role = Role::create(['name' => 'owner']);
        $permission = Permission::pluck('id', 'id')->all();
        $role->syncPermissions($permission);
        $user->assignRole([$role->id]);

    }
}
