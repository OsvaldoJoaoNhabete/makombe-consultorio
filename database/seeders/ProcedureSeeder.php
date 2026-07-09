<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Procedure;

class ProcedureSeeder extends Seeder
{
    public function run(): void
    {
        $procedures = [
            // Consultas
            ['code' => 'CON001', 'name' => 'Consulta Geral', 'category' => 'consulta', 'price' => 1500, 'duration_minutes' => 30],
            ['code' => 'CON002', 'name' => 'Consulta Especializada', 'category' => 'consulta', 'price' => 2500, 'duration_minutes' => 45],
            ['code' => 'CON003', 'name' => 'Consulta Pediátrica', 'category' => 'consulta', 'price' => 1800, 'duration_minutes' => 30],
            ['code' => 'CON004', 'name' => 'Consulta de Cardiologia', 'category' => 'consulta', 'price' => 3000, 'duration_minutes' => 45],
            ['code' => 'CON005', 'name' => 'Consulta de Dermatologia', 'category' => 'consulta', 'price' => 2800, 'duration_minutes' => 30],
            ['code' => 'CON006', 'name' => 'Teleconsulta', 'category' => 'consulta', 'price' => 1200, 'duration_minutes' => 30],
            
            // Exames
            ['code' => 'EXA001', 'name' => 'Análises Clínicas Básicas', 'category' => 'exame', 'price' => 2000, 'duration_minutes' => 15],
            ['code' => 'EXA002', 'name' => 'Hemograma Completo', 'category' => 'exame', 'price' => 1500, 'duration_minutes' => 15],
            ['code' => 'EXA003', 'name' => 'Raio-X', 'category' => 'exame', 'price' => 1800, 'duration_minutes' => 20],
            ['code' => 'EXA004', 'name' => 'Ecografia', 'category' => 'exame', 'price' => 2500, 'duration_minutes' => 30],
            ['code' => 'EXA005', 'name' => 'Electrocardiograma', 'category' => 'exame', 'price' => 1200, 'duration_minutes' => 20],
            ['code' => 'EXA006', 'name' => 'Tomografia', 'category' => 'exame', 'price' => 8000, 'duration_minutes' => 45],
            
            // Vacinas
            ['code' => 'VAC001', 'name' => 'Vacina Gripe', 'category' => 'vacina', 'price' => 800, 'duration_minutes' => 15],
            ['code' => 'VAC002', 'name' => 'Vacina Tétano', 'category' => 'vacina', 'price' => 600, 'duration_minutes' => 15],
            ['code' => 'VAC003', 'name' => 'Vacina Hepatite B', 'category' => 'vacina', 'price' => 1200, 'duration_minutes' => 15],
            
            // Tratamentos
            ['code' => 'TRA001', 'name' => 'Fisioterapia (Sessão)', 'category' => 'tratamento', 'price' => 1500, 'duration_minutes' => 60],
            ['code' => 'TRA002', 'name' => 'Nebulização', 'category' => 'tratamento', 'price' => 800, 'duration_minutes' => 30],
            ['code' => 'TRA003', 'name' => 'Sutura de Ferida', 'category' => 'tratamento', 'price' => 2000, 'duration_minutes' => 45],
            
            // Estética
            ['code' => 'EST001', 'name' => 'Limpeza de Pele', 'category' => 'estetica', 'price' => 2500, 'duration_minutes' => 60],
            ['code' => 'EST002', 'name' => 'Peeling Químico', 'category' => 'estetica', 'price' => 3500, 'duration_minutes' => 45],
        ];

        foreach ($procedures as $procedure) {
            Procedure::firstOrCreate(
                ['code' => $procedure['code']],
                array_merge($procedure, ['is_active' => true])
            );
        }

        $this->command->info('✅ ' . count($procedures) . ' procedimentos cadastrados!');
    }
}