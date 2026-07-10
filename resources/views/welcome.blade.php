<!DOCTYPE html>
<html lang="pt-MZ">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Makombe Consultório Médico - Aqui você tem saúde. Agende consultas online, teleconsultas e muito mais.">
    <meta name="keywords" content="consultório médico, Maputo, Moçambique, teleconsulta, saúde, Makombe">
    <title>{{ $settings['site_name'] ?? 'Makombe Consultório Médico' }} • {{ $settings['site_slogan'] ?? 'Aqui você tem saúde' }}</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#ecfdf5',
                            100: '#d1fae5',
                            500: '#10b981',
                            600: '#059669',
                            700: '#047857',
                            800: '#065f46',
                            900: '#064e3b',
                        }
                    },
                    fontFamily: {
                        sans: ['Lato', 'system-ui', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        * { font-family: 'Lato', sans-serif; scroll-behavior: smooth; }
        
        .gradient-brand { background: linear-gradient(135deg, #10b981 0%, #0d9488 50%, #0891b2 100%); }
        .gradient-brand-dark { background: linear-gradient(135deg, #047857 0%, #065f46 100%); }
        
        .neo-card {
            background: #ffffff;
            box-shadow: 8px 8px 16px #d1d9e6, -8px -8px 16px #ffffff;
            transition: all 0.3s ease;
        }
        .neo-card:hover {
            box-shadow: 12px 12px 24px #d1d9e6, -12px -12px 24px #ffffff;
            transform: translateY(-4px);
        }
        
        .glass-footer {
            background: rgba(6, 95, 70, 0.92);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .fade-in-up { animation: fadeInUp 0.8s ease-out; }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-15px); }
        }
        .float-animation { animation: float 4s ease-in-out infinite; }
        
        @keyframes pulse-ring {
            0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
            70% { transform: scale(1); box-shadow: 0 0 0 15px rgba(16, 185, 129, 0); }
            100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
        }
        .pulse-ring { animation: pulse-ring 2s infinite; }
        
        .hero-overlay {
            background: linear-gradient(135deg, rgba(4, 120, 87, 0.92) 0%, rgba(6, 95, 70, 0.85) 100%);
        }
        
        .carousel-slide { transition: opacity 0.8s ease-in-out; }
        
        .whatsapp-float {
            position: fixed;
            bottom: 24px;
            right: 24px;
            z-index: 999;
            animation: pulse-ring 2s infinite;
        }
        
        .mobile-menu { transition: transform 0.3s ease; }
        .mobile-menu.hidden { transform: translateX(100%); }
        .mobile-menu.open { transform: translateX(0); }
    </style>
</head>
<body class="bg-white text-gray-800">

    <!-- ============================================ -->
    <!-- NAVBAR FIXO -->
    <!-- ============================================ -->
    <nav id="navbar" class="fixed top-0 left-0 right-0 z-50 transition-all duration-300 bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                
                <!-- Logo + Nome + Slogan -->
                <a href="#home" class="flex items-center gap-3">
                    <div class="w-14 h-14 bg-white rounded-xl shadow-md flex items-center justify-center overflow-hidden border border-gray-100 flex-shrink-0">
                        @if(file_exists(public_path('images/logo-mcm.png')))
                            <img src="{{ asset('images/logo-mcm.png') }}" alt="Makombe" class="w-full h-full object-contain p-1">
                        @else
                            <div class="w-full h-full gradient-brand flex items-center justify-center">
                                <i class="fas fa-heartbeat text-white text-2xl"></i>
                            </div>
                        @endif
                    </div>
                    <div class="hidden sm:block">
                        <h1 class="text-xl font-black text-gray-900 leading-none">{{ $settings['site_name'] ?? 'MAKOMBE' }}</h1>
                        <p class="text-xs text-emerald-700 italic leading-none mt-0.5">Consultório Médico</p>
                        <p class="text-[10px] text-gray-500 leading-none mt-0.5">"{{ $settings['site_slogan'] ?? 'Aqui você tem saúde' }}"</p>
                    </div>
                </a>

                <!-- Menu Desktop -->
                <div class="hidden lg:flex items-center gap-8">
                    <a href="#home" class="text-sm font-bold text-gray-800 hover:text-emerald-600 transition">Início</a>
                    <a href="#servicos" class="text-sm font-bold text-gray-800 hover:text-emerald-600 transition">Serviços</a>
                    <a href="#sobre" class="text-sm font-bold text-gray-800 hover:text-emerald-600 transition">Sobre</a>
                    <a href="#equipa" class="text-sm font-bold text-gray-800 hover:text-emerald-600 transition">Equipa</a>
                    <a href="#contactos" class="text-sm font-bold text-gray-800 hover:text-emerald-600 transition">Contactos</a>
                </div>

                <!-- Botões de Ação -->
                <div class="hidden lg:flex items-center gap-3">
                    <a href="{{ route('patient.login') }}" 
                       class="px-5 py-2.5 border-2 border-emerald-600 text-emerald-600 hover:bg-emerald-50 font-bold rounded-xl transition text-sm">
                        <i class="fas fa-user mr-1"></i> Portal
                    </a>
                    <a href="{{ route('login') }}" 
                       class="px-5 py-2.5 gradient-brand text-white hover:opacity-90 font-bold rounded-xl transition text-sm shadow-lg">
                        <i class="fas fa-user-shield mr-1"></i> Staff
                    </a>
                </div>

                <!-- Botão Mobile -->
                <button id="mobileMenuBtn" class="lg:hidden text-gray-800 text-2xl">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>
    </nav>

    <!-- Menu Mobile -->
    <div id="mobileMenu" class="mobile-menu hidden fixed top-0 right-0 h-full w-80 bg-white shadow-2xl z-50 p-6">
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-xl font-black text-gray-900">Menu</h2>
            <button id="closeMobileMenu" class="text-gray-700 text-2xl">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <nav class="space-y-4">
            <a href="#home" class="block py-3 px-4 text-gray-700 hover:bg-emerald-50 hover:text-emerald-600 rounded-lg font-semibold">Início</a>
            <a href="#servicos" class="block py-3 px-4 text-gray-700 hover:bg-emerald-50 hover:text-emerald-600 rounded-lg font-semibold">Serviços</a>
            <a href="#sobre" class="block py-3 px-4 text-gray-700 hover:bg-emerald-50 hover:text-emerald-600 rounded-lg font-semibold">Sobre</a>
            <a href="#equipa" class="block py-3 px-4 text-gray-700 hover:bg-emerald-50 hover:text-emerald-600 rounded-lg font-semibold">Equipa</a>
            <a href="#contactos" class="block py-3 px-4 text-gray-700 hover:bg-emerald-50 hover:text-emerald-600 rounded-lg font-semibold">Contactos</a>
            <hr class="my-4">
            <a href="{{ route('patient.login') }}" class="block py-3 px-4 border-2 border-emerald-600 text-emerald-600 text-center rounded-xl font-semibold">
                <i class="fas fa-user mr-1"></i> Portal do Paciente
            </a>
            <a href="{{ route('login') }}" class="block py-3 px-4 gradient-brand text-white text-center rounded-xl font-semibold">
                <i class="fas fa-user-shield mr-1"></i> Área Staff
            </a>
        </nav>
    </div>

    <!-- ============================================ -->
    <!-- HERO SECTION (CAROUSEL DINÂMICO) -->
    <!-- ============================================ -->
    <section id="home" class="relative min-h-screen flex items-center overflow-hidden pt-20">
        
        <!-- Carousel Background -->
        <div class="absolute inset-0">
            @if($carousel->count() > 0)
                @foreach($carousel as $index => $img)
                    <div class="carousel-slide absolute inset-0 {{ $index === 0 ? 'opacity-100' : 'opacity-0' }}" data-slide="{{ $index }}">
                        <img src="{{ asset('storage/' . $img->image_path) }}" alt="{{ $img->title }}" class="w-full h-full object-cover">
                    </div>
                @endforeach
            @else
                <!-- Fallback se não houver imagens -->
                <div class="carousel-slide absolute inset-0 opacity-100" data-slide="0">
                    <img src="https://images.unsplash.com/photo-1631217868264-e5b90bb7e133?w=1920&q=80" alt="Consultório" class="w-full h-full object-cover">
                </div>
                <div class="carousel-slide absolute inset-0 opacity-0" data-slide="1">
                    <img src="https://images.unsplash.com/photo-1579684385127-1ef15d2c44ce?w=1920&q=80" alt="Equipa" class="w-full h-full object-cover">
                </div>
                <div class="carousel-slide absolute inset-0 opacity-0" data-slide="2">
                    <img src="https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?w=1920&q=80" alt="Teleconsulta" class="w-full h-full object-cover">
                </div>
            @endif
        </div>
        
        <!-- Overlay -->
        <div class="hero-overlay absolute inset-0"></div>

        <!-- Conteúdo -->
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-32 w-full">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                
                <!-- Texto -->
                <div class="text-white fade-in-up">
                    <div class="inline-flex items-center gap-2 px-4 py-2 bg-white/20 backdrop-blur-sm rounded-full mb-6">
                        <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                        <span class="text-sm font-semibold">Aberto agora • Atendimento 24h</span>
                    </div>
                    
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-black mb-6 leading-tight">
                        Cuidamos da sua <span class="text-emerald-300">saúde</span> com excelência
                    </h1>
                    
                    <p class="text-lg md:text-xl text-white/90 mb-8 leading-relaxed">
                        Consultas presenciais, teleconsultas e visitas domiciliárias com os melhores profissionais de Moçambique.
                    </p>

                    <!-- Botões CTA -->
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="{{ route('patient.register') }}" 
                           class="px-8 py-4 bg-white text-emerald-700 hover:bg-gray-100 font-bold rounded-xl shadow-xl transition flex items-center justify-center gap-2 text-lg">
                            <i class="fas fa-user-plus"></i>
                            <span>Criar Conta Grátis</span>
                        </a>
                        <a href="#servicos" 
                           class="px-8 py-4 border-2 border-white text-white hover:bg-white hover:text-emerald-700 font-bold rounded-xl transition flex items-center justify-center gap-2 text-lg">
                            <i class="fas fa-stethoscope"></i>
                            <span>Nossos Serviços</span>
                        </a>
                    </div>

                    <!-- Estatísticas -->
                    <div class="grid grid-cols-3 gap-6 mt-12 pt-8 border-t border-white/20">
                        <div>
                            <p class="text-3xl md:text-4xl font-black">+5k</p>
                            <p class="text-sm text-white/80">Pacientes atendidos</p>
                        </div>
                        <div>
                            <p class="text-3xl md:text-4xl font-black">+20</p>
                            <p class="text-sm text-white/80">Médicos especialistas</p>
                        </div>
                        <div>
                            <p class="text-3xl md:text-4xl font-black">24/7</p>
                            <p class="text-sm text-white/80">Atendimento</p>
                        </div>
                    </div>
                </div>

                <!-- Card Flutuante -->
                <div class="hidden lg:block float-animation">
                    <div class="bg-white rounded-3xl shadow-2xl p-8 max-w-md ml-auto">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-12 h-12 gradient-brand rounded-xl flex items-center justify-center">
                                <i class="fas fa-calendar-check text-white text-xl"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900">Agendamento Rápido</h3>
                                <p class="text-xs text-gray-500">Em menos de 2 minutos</p>
                            </div>
                        </div>
                        
                        <div class="space-y-3 mb-6">
                            <div class="flex items-center gap-3 p-3 bg-emerald-50 rounded-xl">
                                <i class="fas fa-check-circle text-emerald-600"></i>
                                <span class="text-sm font-medium text-gray-700">Escolha o médico</span>
                            </div>
                            <div class="flex items-center gap-3 p-3 bg-blue-50 rounded-xl">
                                <i class="fas fa-check-circle text-blue-600"></i>
                                <span class="text-sm font-medium text-gray-700">Selecione data e hora</span>
                            </div>
                            <div class="flex items-center gap-3 p-3 bg-purple-50 rounded-xl">
                                <i class="fas fa-check-circle text-purple-600"></i>
                                <span class="text-sm font-medium text-gray-700">Receba confirmação</span>
                            </div>
                        </div>

                        <a href="{{ route('patient.register') }}" 
                           class="block w-full py-3 gradient-brand text-white text-center font-bold rounded-xl shadow-lg hover:opacity-90 transition">
                            Começar Agora <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Indicadores do Carousel -->
        @if($carousel->count() > 0)
            <div class="absolute bottom-8 left-1/2 -translate-x-1/2 flex gap-2 z-10">
                @foreach($carousel as $index => $img)
                    <button class="carousel-dot w-10 h-1.5 {{ $index === 0 ? 'bg-white' : 'bg-white/40' }} rounded-full transition" data-slide="{{ $index }}"></button>
                @endforeach
            </div>
        @else
            <div class="absolute bottom-8 left-1/2 -translate-x-1/2 flex gap-2 z-10">
                <button class="carousel-dot w-10 h-1.5 bg-white rounded-full transition" data-slide="0"></button>
                <button class="carousel-dot w-10 h-1.5 bg-white/40 rounded-full transition" data-slide="1"></button>
                <button class="carousel-dot w-10 h-1.5 bg-white/40 rounded-full transition" data-slide="2"></button>
            </div>
        @endif
    </section>

    <!-- ============================================ -->
    <!-- SERVIÇOS (DINÂMICO) -->
    <!-- ============================================ -->
    <section id="servicos" class="py-20 bg-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Cabeçalho -->
            <div class="text-center mb-16">
                <span class="inline-block px-4 py-1 bg-emerald-100 text-emerald-700 text-sm font-semibold rounded-full mb-4">
                    Nossos Serviços
                </span>
                <h2 class="text-4xl md:text-5xl font-black text-gray-900 mb-4">
                    Cuidados completos para <span class="text-emerald-600">toda a família</span>
                </h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Oferecemos uma ampla gama de serviços médicos com profissionais qualificados e tecnologia de ponta.
                </p>
            </div>

            <!-- Grid de Serviços -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @if($services->count() > 0)
                    @foreach($services as $service)
                        <div class="neo-card rounded-2xl p-8">
                            <div class="w-16 h-16 gradient-brand rounded-2xl flex items-center justify-center mb-6">
                                <i class="fas {{ $service->icon }} text-white text-2xl"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-3">{{ $service->title }}</h3>
                            <p class="text-gray-600 mb-4">{{ $service->description }}</p>
                            <a href="{{ route('patient.register') }}" class="text-emerald-600 font-semibold hover:text-emerald-700 inline-flex items-center gap-1">
                                Agendar <i class="fas fa-arrow-right text-xs"></i>
                            </a>
                        </div>
                    @endforeach
                @else
                    <div class="col-span-3 text-center py-12 text-gray-500">
                        <i class="fas fa-stethoscope text-6xl text-gray-300 mb-4"></i>
                        <p class="text-lg">Serviços em breve...</p>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <!-- ============================================ -->
    <!-- SOBRE NÓS (DINÂMICO - ÚNICA SECÇÃO) -->
    <!-- ============================================ -->
    <section id="sobre" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                
                <!-- Imagem -->
                <div class="relative">
                    @php
                        $aboutImagePath = \App\Models\SiteSetting::get('about_image_path');
                    @endphp
                    @if($aboutImagePath && file_exists(storage_path('app/public/' . $aboutImagePath)))
                        <img src="{{ asset('storage/' . $aboutImagePath) }}" alt="Sobre Makombe" class="rounded-3xl shadow-2xl w-full">
                    @else
                        <img src="https://images.unsplash.com/photo-1551190822-a9333d879b1f?w=800&q=80" alt="Equipa Makombe" class="rounded-3xl shadow-2xl w-full">
                    @endif
                    
                    <!-- Card flutuante -->
                    <div class="absolute -bottom-6 -right-6 bg-white rounded-2xl shadow-xl p-6 hidden md:block">
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 gradient-brand rounded-xl flex items-center justify-center">
                                <i class="fas fa-award text-white text-2xl"></i>
                            </div>
                            <div>
                                <p class="text-2xl font-black text-gray-900">{{ $settings['about_card_number'] ?? '10+' }}</p>
                                <p class="text-sm text-gray-600">{{ $settings['about_card_text'] ?? 'Anos de experiência' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Texto -->
                <div>
                    <span class="inline-block px-4 py-1 bg-emerald-100 text-emerald-700 text-sm font-semibold rounded-full mb-4">
                        {{ $settings['about_subtitle'] ?? 'Sobre Nós' }}
                    </span>
                    <h2 class="text-4xl md:text-5xl font-black text-gray-900 mb-6 leading-tight">
                        {{ $settings['about_title'] ?? 'Excelência em cuidados médicos em Moçambique' }}
                    </h2>
                    <p class="text-lg text-gray-600 mb-6 leading-relaxed">
                        {{ $settings['about_paragraph_1'] ?? 'O Makombe Consultório Médico é referência em atendimento de qualidade em Maputo.' }}
                    </p>
                    <p class="text-lg text-gray-600 mb-8 leading-relaxed">
                        {{ $settings['about_paragraph_2'] ?? 'Nossa missão é proporcionar saúde acessível, humana e de excelência.' }}
                    </p>

                    <!-- Características -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-8">
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-check text-emerald-600"></i>
                            </div>
                            <div>
                                <p class="font-bold text-gray-900">{{ $settings['feature_1_title'] ?? 'Profissionais Qualificados' }}</p>
                                <p class="text-sm text-gray-600">{{ $settings['feature_1_desc'] ?? 'Médicos com especialização' }}</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-check text-emerald-600"></i>
                            </div>
                            <div>
                                <p class="font-bold text-gray-900">{{ $settings['feature_2_title'] ?? 'Tecnologia Moderna' }}</p>
                                <p class="text-sm text-gray-600">{{ $settings['feature_2_desc'] ?? 'Equipamentos de ponta' }}</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-check text-emerald-600"></i>
                            </div>
                            <div>
                                <p class="font-bold text-gray-900">{{ $settings['feature_3_title'] ?? 'Atendimento Humanizado' }}</p>
                                <p class="text-sm text-gray-600">{{ $settings['feature_3_desc'] ?? 'Cuidado com o paciente' }}</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-check text-emerald-600"></i>
                            </div>
                            <div>
                                <p class="font-bold text-gray-900">{{ $settings['feature_4_title'] ?? 'Preços Acessíveis' }}</p>
                                <p class="text-sm text-gray-600">{{ $settings['feature_4_desc'] ?? 'Saúde para todos' }}</p>
                            </div>
                        </div>
                    </div>

                    <a href="#contactos" 
                       class="inline-flex items-center gap-2 px-8 py-4 gradient-brand text-white font-bold rounded-xl shadow-lg hover:opacity-90 transition">
                        <i class="fas fa-phone"></i>
                        <span>Fale Connosco</span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================================ -->
    <!-- EQUIPA MÉDICA (DINÂMICA) -->
    <!-- ============================================ -->
    <section id="equipa" class="py-20 bg-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Cabeçalho -->
            <div class="text-center mb-16">
                <span class="inline-block px-4 py-1 bg-emerald-100 text-emerald-700 text-sm font-semibold rounded-full mb-4">
                    Nossa Equipa
                </span>
                <h2 class="text-4xl md:text-5xl font-black text-gray-900 mb-4">
                    Profissionais <span class="text-emerald-600">dedicados</span> à sua saúde
                </h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Conheça alguns dos nossos médicos especialistas, comprometidos em oferecer o melhor atendimento.
                </p>
            </div>

            <!-- Grid de Médicos -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                @if($team->count() > 0)
                    @foreach($team as $member)
                        <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition">
                            <div class="h-64 overflow-hidden bg-gray-200">
                                @if($member->photo_path && file_exists(storage_path('app/public/' . $member->photo_path)))
                                    <img src="{{ asset('storage/' . $member->photo_path) }}" alt="{{ $member->name }}" class="w-full h-full object-cover hover:scale-110 transition duration-500">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                                        <i class="fas fa-user-md text-6xl"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="p-6">
                                <h3 class="text-xl font-bold text-gray-900 mb-1">{{ $member->name }}</h3>
                                <p class="text-emerald-600 font-semibold text-sm mb-3">{{ $member->position }}</p>
                                <p class="text-sm text-gray-600 mb-4">{{ $member->description }}</p>
                                <div class="flex gap-2">
                                    @if($member->facebook)
                                        <a href="{{ $member->facebook }}" target="_blank" class="w-8 h-8 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center hover:bg-blue-600 hover:text-white transition">
                                            <i class="fab fa-facebook-f text-xs"></i>
                                        </a>
                                    @endif
                                    @if($member->linkedin)
                                        <a href="{{ $member->linkedin }}" target="_blank" class="w-8 h-8 bg-sky-100 text-sky-600 rounded-lg flex items-center justify-center hover:bg-sky-600 hover:text-white transition">
                                            <i class="fab fa-linkedin-in text-xs"></i>
                                        </a>
                                    @endif
                                    @if($member->whatsapp)
                                        <a href="https://wa.me/{{ $member->whatsapp }}" target="_blank" class="w-8 h-8 bg-emerald-100 text-emerald-600 rounded-lg flex items-center justify-center hover:bg-emerald-600 hover:text-white transition">
                                            <i class="fab fa-whatsapp text-xs"></i>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="col-span-4 text-center py-12 text-gray-500">
                        <i class="fas fa-users text-6xl text-gray-300 mb-4"></i>
                        <p class="text-lg">Equipa em breve...</p>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <!-- ============================================ -->
    <!-- CONTACTOS (DINÂMICO) -->
    <!-- ============================================ -->
    <section id="contactos" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Cabeçalho -->
            <div class="text-center mb-16">
                <span class="inline-block px-4 py-1 bg-emerald-100 text-emerald-700 text-sm font-semibold rounded-full mb-4">
                    Contactos
                </span>
                <h2 class="text-4xl md:text-5xl font-black text-gray-900 mb-4">
                    Estamos aqui para <span class="text-emerald-600">ajudar</span>
                </h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Entre em contacto connosco. Estamos disponíveis 24 horas por dia, 7 dias por semana.
                </p>
            </div>

            <div class="grid lg:grid-cols-2 gap-12">
                
                <!-- Informações de Contacto (DINÂMICO) -->
                <div class="space-y-6">
                    @php
                        $addresses = $contacts->where('type', 'address');
                        $phones = $contacts->where('type', 'phone');
                        $emails = $contacts->where('type', 'email');
                        $hours = $contacts->where('type', 'hours');
                    @endphp

                    @if($addresses->count() > 0)
                        <div class="flex items-start gap-4 p-6 bg-gray-50 rounded-2xl hover:shadow-lg transition">
                            <div class="w-14 h-14 gradient-brand rounded-xl flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-map-marker-alt text-white text-xl"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900 mb-1">Endereço</h3>
                                @foreach($addresses as $addr)
                                    <p class="text-gray-600">{{ $addr->value }}</p>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if($phones->count() > 0)
                        <div class="flex items-start gap-4 p-6 bg-gray-50 rounded-2xl hover:shadow-lg transition">
                            <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-phone text-white text-xl"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900 mb-1">Telefone</h3>
                                @foreach($phones as $phone)
                                    <p class="text-gray-600">{{ $phone->label ? $phone->label . ': ' : '' }}{{ $phone->value }}</p>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if($emails->count() > 0)
                        <div class="flex items-start gap-4 p-6 bg-gray-50 rounded-2xl hover:shadow-lg transition">
                            <div class="w-14 h-14 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-envelope text-white text-xl"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900 mb-1">Email</h3>
                                @foreach($emails as $email)
                                    <p class="text-gray-600">{{ $email->label ? $email->label . ': ' : '' }}{{ $email->value }}</p>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if($hours->count() > 0)
                        <div class="flex items-start gap-4 p-6 bg-gray-50 rounded-2xl hover:shadow-lg transition">
                            <div class="w-14 h-14 bg-gradient-to-br from-amber-500 to-orange-600 rounded-xl flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-clock text-white text-xl"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900 mb-1">Horário de Atendimento</h3>
                                @foreach($hours as $hour)
                                    <p class="text-gray-600">{{ $hour->label ? $hour->label . ': ' : '' }}{{ $hour->value }}</p>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Mapa -->
                <div class="bg-gray-100 rounded-2xl overflow-hidden shadow-lg h-full min-h-[500px]">
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3589.2284352877!2d32.5713!3d-25.9692!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMjXCsDU4JzA5LjEiUyAzMsKwMzQnMTYuNyJF!5e0!3m2!1spt-PT!2smz!4v1234567890"
                        width="100%" 
                        height="100%" 
                        style="border:0; min-height: 500px;" 
                        allowfullscreen="" 
                        loading="lazy" 
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================================ -->
    <!-- CTA SECTION -->
    <!-- ============================================ -->
    <section class="py-20 gradient-brand relative overflow-hidden">
        <div class="absolute top-0 right-0 w-96 h-96 bg-white opacity-10 rounded-full -mr-48 -mt-48"></div>
        <div class="absolute bottom-0 left-0 w-64 h-64 bg-white opacity-10 rounded-full -ml-32 -mb-32"></div>
        
        <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-white">
            <h2 class="text-4xl md:text-5xl font-black mb-6">
                Pronto para cuidar da sua saúde?
            </h2>
            <p class="text-xl text-white/90 mb-8 max-w-2xl mx-auto">
                Crie sua conta grátis e tenha acesso a consultas, teleconsultas e muito mais.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('patient.register') }}" 
                   class="px-8 py-4 bg-white text-emerald-700 hover:bg-gray-100 font-bold rounded-xl shadow-xl transition flex items-center justify-center gap-2 text-lg">
                    <i class="fas fa-user-plus"></i>
                    <span>Criar Conta Grátis</span>
                </a>
                @if(!empty($settings['whatsapp_number']))
                    <a href="https://wa.me/{{ $settings['whatsapp_number'] }}" target="_blank"
                       class="px-8 py-4 border-2 border-white text-white hover:bg-white hover:text-emerald-700 font-bold rounded-xl transition flex items-center justify-center gap-2 text-lg">
                        <i class="fab fa-whatsapp"></i>
                        <span>Falar no WhatsApp</span>
                    </a>
                @endif
            </div>
        </div>
    </section>

    <!-- ============================================ -->
    <!-- FOOTER (Glassmorphism) -->
    <!-- ============================================ -->
    <footer class="glass-footer text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                
                <!-- Coluna 1: Sobre -->
                <div>
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center overflow-hidden">
                            @if(file_exists(public_path('images/logo-icon.png')))
                                <img src="{{ asset('images/logo-icon.png') }}" alt="Makombe" class="w-full h-full object-contain p-1">
                            @else
                                <i class="fas fa-heartbeat text-emerald-600 text-xl"></i>
                            @endif
                        </div>
                        <div>
                            <h3 class="text-xl font-black">{{ $settings['site_name'] ?? 'MAKOMBE' }}</h3>
                            <p class="text-xs text-emerald-200 italic">"{{ $settings['site_slogan'] ?? 'Aqui você tem saúde' }}"</p>
                        </div>
                    </div>
                    <p class="text-emerald-100 text-sm mb-4 leading-relaxed">
                        Consultório médico de referência em Maputo, oferecendo cuidados de saúde de excelência há mais de 10 anos.
                    </p>
                    <div class="flex gap-2">
                        @if(!empty($settings['facebook_url']))
                            <a href="{{ $settings['facebook_url'] }}" target="_blank" class="w-10 h-10 bg-white/10 hover:bg-white/20 rounded-lg flex items-center justify-center transition">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                        @endif
                        @if(!empty($settings['instagram_url']))
                            <a href="{{ $settings['instagram_url'] }}" target="_blank" class="w-10 h-10 bg-white/10 hover:bg-white/20 rounded-lg flex items-center justify-center transition">
                                <i class="fab fa-instagram"></i>
                            </a>
                        @endif
                        @if(!empty($settings['whatsapp_number']))
                            <a href="https://wa.me/{{ $settings['whatsapp_number'] }}" target="_blank" class="w-10 h-10 bg-white/10 hover:bg-white/20 rounded-lg flex items-center justify-center transition">
                                <i class="fab fa-whatsapp"></i>
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Coluna 2: Links Rápidos -->
                <div>
                    <h4 class="text-lg font-bold mb-4">Links Rápidos</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#home" class="text-emerald-100 hover:text-white transition">Início</a></li>
                        <li><a href="#servicos" class="text-emerald-100 hover:text-white transition">Serviços</a></li>
                        <li><a href="#sobre" class="text-emerald-100 hover:text-white transition">Sobre Nós</a></li>
                        <li><a href="#equipa" class="text-emerald-100 hover:text-white transition">Equipa Médica</a></li>
                        <li><a href="#contactos" class="text-emerald-100 hover:text-white transition">Contactos</a></li>
                    </ul>
                </div>

                <!-- Coluna 3: Serviços -->
                <div>
                    <h4 class="text-lg font-bold mb-4">Serviços</h4>
                    <ul class="space-y-2 text-sm">
                        @foreach($services->take(6) as $service)
                            <li><a href="#servicos" class="text-emerald-100 hover:text-white transition">{{ $service->title }}</a></li>
                        @endforeach
                    </ul>
                </div>

                <!-- Coluna 4: Contacto -->
                <div>
                    <h4 class="text-lg font-bold mb-4">Contacto</h4>
                    <ul class="space-y-3 text-sm">
                        @foreach($addresses as $addr)
                            <li class="flex items-start gap-2">
                                <i class="fas fa-map-marker-alt mt-1 text-emerald-300"></i>
                                <span class="text-emerald-100">{{ $addr->value }}</span>
                            </li>
                        @endforeach
                        @foreach($phones->take(1) as $phone)
                            <li class="flex items-center gap-2">
                                <i class="fas fa-phone text-emerald-300"></i>
                                <span class="text-emerald-100">{{ $phone->value }}</span>
                            </li>
                        @endforeach
                        @foreach($emails->take(1) as $email)
                            <li class="flex items-center gap-2">
                                <i class="fas fa-envelope text-emerald-300"></i>
                                <span class="text-emerald-100">{{ $email->value }}</span>
                            </li>
                        @endforeach
                        @foreach($hours->take(1) as $hour)
                            <li class="flex items-center gap-2">
                                <i class="fas fa-clock text-emerald-300"></i>
                                <span class="text-emerald-100">{{ $hour->value }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <!-- Rodapé -->
            <div class="mt-12 pt-8 border-t border-white/10 flex flex-col md:flex-row items-center justify-between gap-4">
                <p class="text-sm text-emerald-100">
                    © {{ date('Y') }} {{ $settings['site_name'] ?? 'Makombe Consultório Médico' }}. Todos os direitos reservados.
                </p>
                <div class="flex items-center gap-4 text-sm">
                    <a href="{{ route('patient.terms') }}" class="text-emerald-100 hover:text-white transition">Termos e Condições</a>
                    <span class="text-emerald-300">•</span>
                    <a href="{{ route('patient.privacy') }}" class="text-emerald-100 hover:text-white transition">Política de Privacidade</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- ============================================ -->
    <!-- BOTÃO WHATSAPP FLUTUANTE -->
    <!-- ============================================ -->
    @if(!empty($settings['whatsapp_number']))
        <a href="https://wa.me/{{ $settings['whatsapp_number'] }}" target="_blank" 
           class="whatsapp-float w-14 h-14 bg-green-500 hover:bg-green-600 text-white rounded-full flex items-center justify-center shadow-2xl transition">
            <i class="fab fa-whatsapp text-3xl"></i>
        </a>
    @endif

    <!-- ============================================ -->
    <!-- SCRIPTS -->
    <!-- ============================================ -->
    <script>
        // Mobile menu
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const mobileMenu = document.getElementById('mobileMenu');
        const closeMobileMenu = document.getElementById('closeMobileMenu');

        mobileMenuBtn.addEventListener('click', () => {
            mobileMenu.classList.remove('hidden');
            setTimeout(() => mobileMenu.classList.add('open'), 10);
        });

        closeMobileMenu.addEventListener('click', () => {
            mobileMenu.classList.remove('open');
            setTimeout(() => mobileMenu.classList.add('hidden'), 300);
        });

        mobileMenu.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => {
                mobileMenu.classList.remove('open');
                setTimeout(() => mobileMenu.classList.add('hidden'), 300);
            });
        });

        // Carousel automático
        let currentSlide = 0;
        const slides = document.querySelectorAll('.carousel-slide');
        const dots = document.querySelectorAll('.carousel-dot');

        function showSlide(index) {
            if (slides.length === 0) return;
            slides.forEach((slide, i) => {
                slide.style.opacity = i === index ? '1' : '0';
            });
            dots.forEach((dot, i) => {
                if (dot) {
                    dot.classList.toggle('bg-white', i === index);
                    dot.classList.toggle('bg-white/40', i !== index);
                }
            });
            currentSlide = index;
        }

        function nextSlide() {
            if (slides.length === 0) return;
            showSlide((currentSlide + 1) % slides.length);
        }

        dots.forEach((dot, index) => {
            if (dot) {
                dot.addEventListener('click', () => showSlide(index));
            }
        });

        // Trocar slide a cada 5 segundos
        setInterval(nextSlide, 5000);

        // Smooth scroll para links internos
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                const href = this.getAttribute('href');
                if (href === '#') return;
                const target = document.querySelector(href);
                if (target) {
                    e.preventDefault();
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });
    </script>

</body>
</html>