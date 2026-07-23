<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Criar as funções (roles) padrão do sistema
        $roles = [
            'Administrador',
            'Gerente',
            'Medico',
            'Enfermeiro',
            'Atendente',
            'Financeiro',
        ];

        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName]);
        }

        $this->command->info("✅ " . count($roles) . " funções criadas com sucesso!");

        // 2. Criar o utilizador administrador padrão
        $admin = User::firstOrCreate(
            ['email' => 'admin@makombe.com'],
            [
                'name' => 'Osvaldo Administrador',
                'password' => bcrypt('password123'),
                'email_verified_at' => now(),
                'is_active' => true,
            ]
        );

        // 3. Atribuir a função de Administrador
        $admin->assignRole('Administrador');

        $this->command->info("✅ Utilizador administrador criado com sucesso!");
        $this->command->info("📧 Email: admin@makombe.com");
        $this->command->info("🔑 Senha: password123");
    }
}