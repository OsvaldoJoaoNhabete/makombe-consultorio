<x-layouts.admin title="Gestão de Conteúdo">

    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">📋 Gestão de Conteúdo</h1>
        <p class="text-gray-600">Administre todo o conteúdo da landing page</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <a href="{{ route('admin.content.carousel') }}" class="bg-white p-6 rounded-xl shadow-sm border hover:shadow-lg transition group">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center group-hover:bg-blue-200 transition">
                    <i class="fas fa-images text-blue-600 text-2xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['carousel_images'] }}</p>
                    <p class="text-sm text-gray-600">Imagens do Carrossel</p>
                </div>
            </div>
        </a>

        <a href="{{ route('admin.content.team') }}" class="bg-white p-6 rounded-xl shadow-sm border hover:shadow-lg transition group">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-purple-100 rounded-xl flex items-center justify-center group-hover:bg-purple-200 transition">
                    <i class="fas fa-users text-purple-600 text-2xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['team_members'] }}</p>
                    <p class="text-sm text-gray-600">Membros da Equipa</p>
                </div>
            </div>
        </a>

        <a href="{{ route('admin.content.services') }}" class="bg-white p-6 rounded-xl shadow-sm border hover:shadow-lg transition group">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-emerald-100 rounded-xl flex items-center justify-center group-hover:bg-emerald-200 transition">
                    <i class="fas fa-stethoscope text-emerald-600 text-2xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['services'] }}</p>
                    <p class="text-sm text-gray-600">Serviços</p>
                </div>
            </div>
        </a>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
    <!-- Cards existentes... -->
    
    <!-- Novo Card: Sobre Nós -->
    <a href="{{ route('admin.content.about') }}" class="bg-white p-6 rounded-xl shadow-sm border hover:shadow-lg transition group">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 bg-indigo-100 rounded-xl flex items-center justify-center group-hover:bg-indigo-200 transition">
                <i class="fas fa-info-circle text-indigo-600 text-2xl"></i>
            </div>
            <div>
                <p class="text-sm font-bold text-gray-900">Sobre Nós</p>
                <p class="text-xs text-gray-600">Editar secção</p>
            </div>
        </div>
    </a>
</div>

        <a href="{{ route('admin.content.contacts') }}" class="bg-white p-6 rounded-xl shadow-sm border hover:shadow-lg transition group">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-amber-100 rounded-xl flex items-center justify-center group-hover:bg-amber-200 transition">
                    <i class="fas fa-address-book text-amber-600 text-2xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['contact_infos'] }}</p>
                    <p class="text-sm text-gray-600">Informações de Contacto</p>
                </div>
            </div>
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4">📌 Instruções</h2>
        <div class="space-y-3 text-gray-700">
            <p><strong>Carrossel:</strong> Adicione, edite ou remova imagens do carrossel da landing page. As imagens devem ter boa resolução (recomendado 1920x1080px).</p>
            <p><strong>Equipa Médica:</strong> Gerencie os perfis dos médicos e profissionais de saúde que aparecem na landing page.</p>
            <p><strong>Serviços:</strong> Adicione ou edite os serviços oferecidos pelo consultório.</p>
            <p><strong>Contactos:</strong> Atualize endereços, telefones, emails e horários de atendimento.</p>
            <p><strong>Configurações:</strong> Defina informações gerais do site como nome, slogan e redes sociais.</p>
        </div>
    </div>

</x-layouts.admin>