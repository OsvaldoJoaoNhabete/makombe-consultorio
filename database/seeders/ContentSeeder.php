<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SiteSetting;
use App\Models\CarouselImage;
use App\Models\TeamMember;
use App\Models\Service;
use App\Models\ContactInfo;

class ContentSeeder extends Seeder
{
    public function run(): void
    {
        // Configurações
        SiteSetting::set('site_name', 'Makombe Consultório Médico');
        SiteSetting::set('site_slogan', 'Aqui você tem saúde');
        SiteSetting::set('phone_main', '+258 84 123 4567');
        SiteSetting::set('email_main', 'info@makombe.co.mz');
        SiteSetting::set('address', 'Av. 25 de Setembro, Nº 1234, Maputo, Moçambique');
        SiteSetting::set('whatsapp_number', '258841234567');

        // Carrossel (Imagens de exemplo do Unsplash)
        $carouselData = [
            ['title' => 'Cuidamos da sua saúde', 'image' => 'https://images.unsplash.com/photo-1631217868264-e5b90bb7e133?w=1920&q=80', 'order' => 1],
            ['title' => 'Equipa Especializada', 'image' => 'https://images.unsplash.com/photo-1579684385127-1ef15d2c44ce?w=1920&q=80', 'order' => 2],
            ['title' => 'Teleconsultas Seguras', 'image' => 'https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?w=1920&q=80', 'order' => 3],
        ];

        foreach ($carouselData as $data) {
            // Baixar imagem e salvar localmente
            $imageContent = file_get_contents($data['image']);
            $imageName = 'carousel_' . time() . '_' . $data['order'] . '.jpg';
            \Illuminate\Support\Facades\Storage::disk('public')->put('carousel/' . $imageName, $imageContent);
            
            CarouselImage::create([
                'title' => $data['title'],
                'image_path' => 'carousel/' . $imageName,
                'order' => $data['order'],
                'is_active' => true,
            ]);
        }

        // Equipa
        $teamData = [
            ['name' => 'Dr. João Makombe', 'position' => 'Medicina Geral', 'description' => '+15 anos de experiência em clínica geral.', 'order' => 1],
            ['name' => 'Dra. Maria Silva', 'position' => 'Cardiologia', 'description' => 'Especialista em doenças cardiovasculares.', 'order' => 2],
            ['name' => 'Dr. Carlos Nhabete', 'position' => 'Pediatria', 'description' => 'Cuidados especializados para crianças.', 'order' => 3],
            ['name' => 'Dra. Ana Mondlane', 'position' => 'Dermatologia', 'description' => 'Especialista em saúde da pele.', 'order' => 4],
        ];

        foreach ($teamData as $data) {
            TeamMember::create(array_merge($data, ['is_active' => true]));
        }

        // Serviços
        $servicesData = [
            ['title' => 'Consultas Presenciais', 'description' => 'Atendimento personalizado em consultório.', 'icon' => 'fa-stethoscope', 'order' => 1],
            ['title' => 'Teleconsultas', 'description' => 'Consulte médicos de qualquer lugar por vídeo.', 'icon' => 'fa-video', 'order' => 2],
            ['title' => 'Visitas Domiciliárias', 'description' => 'Atendimento no conforto da sua casa.', 'icon' => 'fa-house-user', 'order' => 3],
            ['title' => 'Exames Laboratoriais', 'description' => 'Análises clínicas com resultados rápidos.', 'icon' => 'fa-flask', 'order' => 4],
            ['title' => 'Cardiologia', 'description' => 'Cuidados especializados para o coração.', 'icon' => 'fa-heartbeat', 'order' => 5],
            ['title' => 'Pediatria', 'description' => 'Cuidados para crianças e adolescentes.', 'icon' => 'fa-baby', 'order' => 6],
        ];

        foreach ($servicesData as $data) {
            Service::create(array_merge($data, ['is_active' => true]));
        }

        // Contactos
        $contactsData = [
            ['type' => 'address', 'value' => 'Av. 25 de Setembro, Nº 1234, Maputo, Moçambique', 'order' => 1],
            ['type' => 'phone', 'label' => 'Principal', 'value' => '+258 84 123 4567', 'order' => 1],
            ['type' => 'phone', 'label' => 'Secundário', 'value' => '+258 85 987 6543', 'order' => 2],
            ['type' => 'email', 'label' => 'Geral', 'value' => 'info@makombe.co.mz', 'order' => 1],
            ['type' => 'email', 'label' => 'Contacto', 'value' => 'contacto@makombe.co.mz', 'order' => 2],
            ['type' => 'hours', 'label' => 'Semana', 'value' => 'Segunda a Sexta: 07:00 - 19:00', 'order' => 1],
            ['type' => 'hours', 'label' => 'Sábado', 'value' => 'Sábado: 08:00 - 14:00', 'order' => 2],
            ['type' => 'hours', 'label' => 'Urgências', 'value' => '24 horas', 'order' => 3],
        ];

        foreach ($contactsData as $data) {
            ContactInfo::create(array_merge($data, ['is_active' => true]));
        }

        $this->command->info('✅ Conteúdo inicial carregado com sucesso!');
    }
}