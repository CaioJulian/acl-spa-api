<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;


class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       $permissions = [
           'papel-listar',
           'papel-criar',
           'papel-editar',
           'papel-deletar',
           'usuario-listar',
           'usuario-criar',
           'usuario-editar',
           'usuario-deletar',
        ];

        foreach ($permissions as $permission) {
             Permission::firstOrCreate(['name' => $permission]);
        }
    }
}
