<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Specialty;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // ==========================================
        // ADMINISTRADOR PRINCIPAL
        // ==========================================
        $admin = User::firstOrCreate(
            ['email' => 'osvaldo.nhabete@makombe.com'],
            [
                'name' => 'Osvaldo João Nhabete',
                'phone' => '841178857', // Número principal do WhatsApp (Sugestão 12)
                'password' => Hash::make('admin123'),
                'type' => 'staff',
                'is_active' => true,
            ]
        );
        $admin->assignRole('Administrador');

        // ==========================================
        // OUTROS UTILIZADORES DE TESTE
        // ==========================================
        
        // Gerente
        $gerente = User::firstOrCreate(
            ['email' => 'gerente@makombe.com'],
            [
                'name' => 'Maria Gerente',
                'phone' => '841234567',
                'password' => Hash::make('gerente123'),
                'type' => 'staff',
                'is_active' => true,
            ]
        );
        $gerente->assignRole('Gerente');

        // Médico Cardiologista
        $cardio = Specialty::where('name', 'Cardiologia')->first();
        $medico1 = User::firstOrCreate(
            ['email' => 'dr.silva@makombe.com'],
            [
                'name' => 'Dr. João Silva',
                'phone' => '842345678',
                'password' => Hash::make('medico123'),
                'type' => 'staff',
                'specialty_id' => $cardio?->id,
                'is_active' => true,
            ]
        );
        $medico1->assignRole('Medico');

        // Médico Pediatra
        $pediatria = Specialty::where('name', 'Pediatria')->first();
        $medico2 = User::firstOrCreate(
            ['email' => 'dr.santos@makombe.com'],
            [
                'name' => 'Dra. Ana Santos',
                'phone' => '843456789',
                'password' => Hash::make('medico123'),
                'type' => 'staff',
                'specialty_id' => $pediatria?->id,
                'is_active' => true,
            ]
        );
        $medico2->assignRole('Medico');

        // Enfermeiro
        $enfermeiro = User::firstOrCreate(
            ['email' => 'enf.costa@makombe.com'],
            [
                'name' => 'Enf. Carlos Costa',
                'phone' => '844567890',
                'password' => Hash::make('enfermeiro123'),
                'type' => 'staff',
                'is_active' => true,
            ]
        );
        $enfermeiro->assignRole('Enfermeiro');

        // Atendente/Recepcionista
        $atendente = User::firstOrCreate(
            ['email' => 'rececao@makombe.com'],
            [
                'name' => 'Sofia Receção',
                'phone' => '845678901',
                'password' => Hash::make('atendente123'),
                'type' => 'staff',
                'is_active' => true,
            ]
        );
        $atendente->assignRole('Atendente');

        $this->command->info('✅ Utilizadores criados/atualizados com sucesso!');
        $this->command->info(' Admin: osvaldo.nhabete@makombe.com | PIN/Senha: admin123');
    }
}