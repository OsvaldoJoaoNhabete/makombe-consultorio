<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Criar permissões básicas
        $permissions = [
            'view-dashboard',
            'manage-users',
            'manage-patients',
            'manage-consultations',
            'manage-financial',
            'view-reports',
            'manage-content',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Criar Roles
        $roles = [
            'Administrador' => $permissions,
            'Gerente' => $permissions,
            'Proprietária' => $permissions,
            'Financeiro' => ['view-dashboard', 'manage-financial', 'view-reports'],
            'Contabilista' => ['view-dashboard', 'manage-financial', 'view-reports'],
            'Recepcionista' => ['view-dashboard', 'manage-patients', 'manage-consultations'],
            'Medico' => ['view-dashboard', 'manage-consultations'],
            'Enfermeiro' => ['view-dashboard', 'manage-consultations'],
            'Atendente' => ['view-dashboard', 'manage-patients'],
        ];

        foreach ($roles as $roleName => $rolePermissions) {
            $role = Role::firstOrCreate(['name' => $roleName]);
            $role->syncPermissions($rolePermissions);
        }

        $this->command->info('✅ Roles e permissões criadas com sucesso!');
    }
}