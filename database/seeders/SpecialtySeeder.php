<?php

namespace Database\Seeders;

use App\Models\Specialty;
use Illuminate\Database\Seeder;

class SpecialtySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $specialties = [
            [
                'name' => 'Pediatria',
                'description' => 'Cuidados de saúde dedicados ao desenvolvimento físico, mental e emocional de bebés, crianças e adolescentes, desde o nascimento até à idade adulta jovem.',
                'icon' => 'fa-baby',
                'color' => '#3b82f6',
                'is_active' => true,
            ],
            [
                'name' => 'Dermatologia',
                'description' => 'Diagnóstico e tratamento de doenças relacionadas com a pele, cabelo e unhas, incluindo condições como acne, eczema, psoríase e rastreio preventivo.',
                'icon' => 'fa-hand-sparkles',
                'color' => '#f472b6',
                'is_active' => true,
            ],
            [
                'name' => 'Terapia da Fala',
                'description' => 'Avaliação e reabilitação de perturbações da comunicação, linguagem, fala, voz e deglutição, ajudando pacientes de todas as idades a melhorar a sua capacidade de comunicação.',
                'icon' => 'fa-comments',
                'color' => '#f59e0b',
                'is_active' => true,
            ],
            [
                'name' => 'Terapia Ocupacional',
                'description' => 'Apoio a pacientes com dificuldades físicas, sensoriais ou cognitivas para promover a sua autonomia e independência nas atividades diárias, laborais e de lazer.',
                'icon' => 'fa-hands-helping',
                'color' => '#10b981',
                'is_active' => true,
            ],
            [
                'name' => 'Clínica Geral',
                'description' => 'Primeira linha de atendimento médico, focada no diagnóstico, tratamento e prevenção de doenças comuns, bem como no encaminhamento para especialistas quando necessário.',
                'icon' => 'fa-stethoscope',
                'color' => '#6d28d9',
                'is_active' => true,
            ],
            [
                'name' => 'Medicina Interna',
                'description' => 'Especialidade dedicada ao diagnóstico e tratamento não cirúrgico de doenças complexas e crónicas em adultos, abrangendo múltiplos sistemas do organismo.',
                'icon' => 'fa-user-md',
                'color' => '#4b5563',
                'is_active' => true,
            ],
            [
                'name' => 'Psicologia',
                'description' => 'Avaliação e intervenção no comportamento e processos mentais, oferecendo suporte para lidar com ansiedade, depressão, stress, traumas e outros desafios emocionais.',
                'icon' => 'fa-brain',
                'color' => '#8b5cf6',
                'is_active' => true,
            ],
            [
                'name' => 'Nutrição',
                'description' => 'Orientação alimentar personalizada para promover a saúde, prevenir doenças, gerir condições como diabetes ou obesidade e melhorar o bem-estar geral.',
                'icon' => 'fa-apple-alt',
                'color' => '#84cc16',
                'is_active' => true,
            ],
            [
                'name' => 'Ginecologia e Obstetrícia',
                'description' => 'Cuidados de saúde especializados para a mulher, abrangendo a saúde reprodutiva, planeamento familiar, acompanhamento da gravidez, parto e pós-parto.',
                'icon' => 'fa-female',
                'color' => '#ec4899',
                'is_active' => true,
            ],
            [
                'name' => 'Cardiologia',
                'description' => 'Prevenção, diagnóstico e tratamento de doenças do coração e do sistema circulatório, como hipertensão, arritmias e doenças coronárias.',
                'icon' => 'fa-heartbeat',
                'color' => '#ef4444',
                'is_active' => true,
            ],
            [
                'name' => 'Psiquiatria',
                'description' => 'Especialidade médica focada no diagnóstico, tratamento e prevenção de perturbações mentais, emocionais e comportamentais, incluindo gestão de medicação.',
                'icon' => 'fa-head-side-virus',
                'color' => '#6366f1',
                'is_active' => true,
            ],
            [
                'name' => 'Medicina Ocupacional',
                'description' => 'Promoção e manutenção do bem-estar físico, mental e social dos trabalhadores, com foco na prevenção de doenças profissionais e acidentes de trabalho.',
                'icon' => 'fa-briefcase-medical',
                'color' => '#0ea5e9',
                'is_active' => true,
            ],
            [
                'name' => 'Consultas de Cirurgia',
                'description' => 'Avaliação pré-operatória, realização de procedimentos cirúrgicos e acompanhamento pós-operatório para garantir uma recuperação segura e eficaz.',
                'icon' => 'fa-procedures',
                'color' => '#64748b',
                'is_active' => true,
            ],
            [
                'name' => 'Urologia',
                'description' => 'Diagnóstico e tratamento de doenças do sistema urinário (rins, bexiga, uretra) em homens e mulheres, e do sistema reprodutor masculino.',
                'icon' => 'fa-notes-medical',
                'color' => '#06b6d4',
                'is_active' => true,
            ],
        ];

        foreach ($specialties as $specialty) {
            Specialty::firstOrCreate(
                ['name' => $specialty['name']],
                $specialty
            );
        }

        $this->command->info('✅ Especialidades criadas com sucesso!');
    }
}