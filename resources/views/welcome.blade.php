<!DOCTYPE html>
<html lang="pt-MZ" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Makombe Consultório Médico - Aqui você tem saúde. Agende consultas online, teleconsultas e muito mais em Maputo.">
    <title>{{ $settings['site_name'] ?? 'Makombe Consultório Médico' }} • {{ $settings['site_slogan'] ?? 'Aqui você tem saúde' }}</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        makombe: {
                            50: '#f5f3ff',
                            100: '#ede9fe',
                            500: '#8b5cf6',
                            600: '#7c3aed',
                            700: '#6d28d9',
                            800: '#5b21b6',
                            900: '#4c1d95',
                        }
                    },
                    fontFamily: {
                        sans: ['Lato', 'system-ui', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    
    <style>
        .bg-makombe-gradient { background: linear-gradient(135deg, #4c1d95 0%, #5b21b6 50%, #6d28d9 100%); }
        .text-makombe-gradient { background: linear-gradient(135deg, #4c1d95 0%, #6d28d9 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        
        .hero-overlay { background: linear-gradient(135deg, rgba(76, 29, 149, 0.92) 0%, rgba(91, 33, 182, 0.85) 100%); }
        
        .nav-scrolled { background: rgba(255, 255, 255, 0.98); backdrop-filter: blur(10px); box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05); }
        
        .service-card { transition: all 0.3s ease; }
        .service-card:hover { transform: translateY(-8px); box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); }
        
        .glass-footer { background: rgba(76, 29, 149, 0.95); backdrop-filter: blur(12px); }
        
        @keyframes float { 0%, 100% { transform: translateY(0px); } 50% { transform: translateY(-10px); } }
        .animate-float { animation: float 4s ease-in-out infinite; }
        
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-fade-in-up { animation: fadeInUp 0.8s ease-out forwards; }
    </style>
</head>
<body class="font-sans text-slate-800 bg-slate-50">

    <!-- ============================================ -->
    <!-- NAVBAR -->
    <!-- ============================================ -->
    <nav id="navbar" class="fixed top-0 left-0 right-0 z-50 transition-all duration-300 bg-white/90 backdrop-blur-md border-b border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                
                <!-- Logo -->
                <a href="#home" class="flex items-center gap-3 group">
                    <div class="w-12 h-12 bg-white rounded-xl shadow-md flex items-center justify-center overflow-hidden border border-slate-100 group-hover:shadow-lg transition">
                        @if(file_exists(public_path('images/logo-mcm.png')))
                            <img src="{{ asset('images/logo-mcm.png') }}" alt="Makombe" class="w-full h-full object-contain p-1">
                        @else
                            <i class="fas fa-heartbeat text-2xl text-makombe-700"></i>
                        @endif
                    </div>
                    <div class="hidden sm:block">
                        <h1 class="text-lg font-black text-slate-900 leading-none tracking-tight">MAKOMBE</h1>
                        <p class="text-[10px] text-makombe-600 font-bold uppercase tracking-wider">Consultório Médico</p>
                    </div>
                </a>

                <!-- Menu Desktop -->
                <div class="hidden lg:flex items-center gap-8">
                    <a href="#home" class="text-sm font-semibold text-slate-600 hover:text-makombe-700 transition">Início</a>
                    <a href="#servicos" class="text-sm font-semibold text-slate-600 hover:text-makombe-700 transition">Serviços</a>
                    <a href="#sobre" class="text-sm font-semibold text-slate-600 hover:text-makombe-700 transition">Sobre</a>
                    <a href="#equipa" class="text-sm font-semibold text-slate-600 hover:text-makombe-700 transition">Equipa</a>
                    <a href="#contactos" class="text-sm font-semibold text-slate-600 hover:text-makombe-700 transition">Contactos</a>
                </div>

                <!-- Botões de Ação -->
                <div class="hidden lg:flex items-center gap-3">
                  
                    <a href="{{ route('login') }}" class="px-5 py-2.5 bg-makombe-700 hover:bg-makombe-800 text-white font-bold rounded-xl transition text-sm shadow-lg shadow-makombe-500/30">
                        <i class="fas fa-user-shield mr-1"></i> Entrar
                    </a>
                </div>

                <!-- Botão Mobile -->
                <button id="mobileMenuBtn" class="lg:hidden text-slate-700 text-2xl p-2">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>
    </nav>

    <!-- Menu Mobile -->
    <div id="mobileMenu" class="fixed inset-y-0 right-0 w-80 bg-white shadow-2xl z-50 transform translate-x-full transition-transform duration-300 lg:hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <h2 class="text-xl font-black text-makombe-800">Menu</h2>
            <button id="closeMobileMenu" class="text-slate-500 hover:text-slate-800 text-2xl"><i class="fas fa-times"></i></button>
        </div>
        <nav class="p-6 space-y-4">
            <a href="#home" class="block py-3 px-4 text-slate-700 hover:bg-makombe-50 hover:text-makombe-700 rounded-lg font-semibold transition mobile-link">Início</a>
            <a href="#servicos" class="block py-3 px-4 text-slate-700 hover:bg-makombe-50 hover:text-makombe-700 rounded-lg font-semibold transition mobile-link">Serviços</a>
            <a href="#sobre" class="block py-3 px-4 text-slate-700 hover:bg-makombe-50 hover:text-makombe-700 rounded-lg font-semibold transition mobile-link">Sobre</a>
            <a href="#equipa" class="block py-3 px-4 text-slate-700 hover:bg-makombe-50 hover:text-makombe-700 rounded-lg font-semibold transition mobile-link">Equipa</a>
            <a href="#contactos" class="block py-3 px-4 text-slate-700 hover:bg-makombe-50 hover:text-makombe-700 rounded-lg font-semibold transition mobile-link">Contactos</a>
            <hr class="border-slate-100 my-4">
            <a href="{{ route('login') }}" class="block w-full py-3 px-4 border-2 border-makombe-600 text-makombe-700 text-center rounded-xl font-bold transition mobile-link">Portal do Paciente</a>
            <a href="{{ route('login') }}" class="block w-full py-3 px-4 bg-makombe-700 text-white text-center rounded-xl font-bold transition mobile-link">Área Staff</a>
        </nav>
    </div>
    <div id="mobileOverlay" class="fixed inset-0 bg-black/50 z-40 hidden lg:hidden backdrop-blur-sm"></div>

    <!-- ============================================ -->
    <!-- HERO SECTION -->
    <!-- ============================================ -->
    <section id="home" class="relative min-h-screen flex items-center pt-20 overflow-hidden">
        <!-- Background Image -->
        <div class="absolute inset-0 z-0">
            <img src="https://images.unsplash.com/photo-1631217868264-e5b90bb7e133?w=1920&q=80" alt="Consultório Médico" class="w-full h-full object-cover">
        </div>
        <!-- Overlay -->
        <div class="hero-overlay absolute inset-0 z-10"></div>

        <!-- Content -->
        <div class="relative z-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 w-full">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <div class="text-white animate-fade-in-up">
                    <div class="inline-flex items-center gap-2 px-4 py-2 bg-white/10 backdrop-blur-sm border border-white/20 rounded-full mb-6">
                        <span class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></span>
                        <span class="text-sm font-semibold tracking-wide">Atendimento 24h • Maputo, Moçambique</span>
                    </div>
                    
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-black mb-6 leading-tight">
                        Cuidamos da sua <span class="text-emerald-300">saúde</span> com excelência
                    </h1>
                    
                    <p class="text-lg md:text-xl text-white/90 mb-8 leading-relaxed max-w-xl">
                        Consultas presenciais, teleconsultas e visitas domiciliárias com os melhores profissionais de saúde, tecnologia de ponta e atendimento humanizado.
                    </p>

                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="{{ route('register') }}" class="px-8 py-4 bg-white text-makombe-800 hover:bg-slate-100 font-black rounded-xl shadow-xl transition transform hover:scale-105 flex items-center justify-center gap-2 text-lg">
                            <i class="fas fa-user-plus"></i>
                            <span>Criar Conta Grátis</span>
                        </a>
                        <!-- Botão Flutuante WhatsApp (Sugestão 12) -->
<a href="https://wa.me/258841178857?text=Olá,%20gostaria%20de%20mais%20informações%20sobre%20o%20Makombe%20Consultório." 
   target="_blank" 
   rel="noopener noreferrer"
   class="fixed bottom-6 right-6 z-50 bg-[#25D366] hover:bg-[#20bd5a] text-white w-14 h-14 rounded-full flex items-center justify-center shadow-2xl transition-all duration-300 hover:scale-110 group animate-pulse"
   title="Fale connosco no WhatsApp">
    <i class="fab fa-whatsapp text-3xl"></i>
    <!-- Tooltip ao passar o rato -->
    <span class="absolute right-full mr-3 bg-gray-800 text-white text-xs px-3 py-1.5 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap shadow-lg pointer-events-none">
        Fale connosco (84 117 88 57)
    </span>
</a>
                    </div>

                    <!-- Stats -->
                    <div class="grid grid-cols-3 gap-6 mt-12 pt-8 border-t border-white/20">
                        <div>
                            <p class="text-3xl md:text-4xl font-black text-emerald-300">+5k</p>
                            <p class="text-sm text-white/80 mt-1">Pacientes atendidos</p>
                        </div>
                        <div>
                            <p class="text-3xl md:text-4xl font-black text-emerald-300">+20</p>
                            <p class="text-sm text-white/80 mt-1">Médicos especialistas</p>
                        </div>
                        <div>
                            <p class="text-3xl md:text-4xl font-black text-emerald-300">10+</p>
                            <p class="text-sm text-white/80 mt-1">Anos de experiência</p>
                        </div>
                    </div>
                </div>

                <!-- Floating Card (Desktop only) -->
                <div class="hidden lg:block animate-float">
                    <div class="bg-white rounded-3xl shadow-2xl p-8 max-w-md ml-auto border border-slate-100">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-14 h-14 bg-makombe-100 rounded-2xl flex items-center justify-center">
                                <i class="fas fa-calendar-check text-makombe-700 text-2xl"></i>
                            </div>
                            <div>
                                <h3 class="font-black text-slate-900 text-lg">Agendamento Rápido</h3>
                                <p class="text-sm text-slate-500">Em menos de 2 minutos</p>
                            </div>
                        </div>
                        
                        <div class="space-y-4 mb-6">
                            <div class="flex items-center gap-3 p-3 bg-emerald-50 rounded-xl border border-emerald-100">
                                <i class="fas fa-check-circle text-emerald-600"></i>
                                <span class="text-sm font-semibold text-slate-700">Escolha o tipo de consulta</span>
                            </div>
                            <div class="flex items-center gap-3 p-3 bg-blue-50 rounded-xl border border-blue-100">
                                <i class="fas fa-check-circle text-blue-600"></i>
                                <span class="text-sm font-semibold text-slate-700">Selecione data e horário</span>
                            </div>
                            <div class="flex items-center gap-3 p-3 bg-purple-50 rounded-xl border border-purple-100">
                                <i class="fas fa-check-circle text-purple-600"></i>
                                <span class="text-sm font-semibold text-slate-700">Receba confirmação imediata</span>
                            </div>
                        </div>

                        <a href="{{ route('register') }}" class="block w-full py-4 bg-makombe-700 hover:bg-makombe-800 text-white text-center font-black rounded-xl shadow-lg transition">
                            Começar Agora <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================================ -->
    <!-- SERVIÇOS -->
    <!-- ============================================ -->
    <section id="servicos" class="py-24 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <span class="inline-block px-4 py-1.5 bg-makombe-100 text-makombe-700 text-sm font-black rounded-full mb-4 uppercase tracking-wider">Nossos Serviços</span>
                <h2 class="text-4xl md:text-5xl font-black text-slate-900 mb-4">
                    Cuidados completos para <span class="text-makombe-gradient">toda a família</span>
                </h2>
                <p class="text-lg text-slate-600 max-w-2xl mx-auto">
                    Oferecemos uma ampla gama de serviços médicos com profissionais qualificados e tecnologia de ponta.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($services as $service)
                    <div class="service-card bg-white rounded-2xl p-8 border border-slate-100 shadow-sm relative overflow-hidden group">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-makombe-50 rounded-full -mr-16 -mt-16 transition group-hover:bg-makombe-100"></div>
                        
                        <div class="relative z-10">
                            <div class="w-16 h-16 bg-makombe-100 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-makombe-600 transition-colors duration-300">
                                <i class="fas {{ $service->icon ?? 'fa-stethoscope' }} text-makombe-700 text-2xl group-hover:text-white transition-colors duration-300"></i>
                            </div>
                            <h3 class="text-xl font-black text-slate-900 mb-3">{{ $service->title }}</h3>
                            <p class="text-slate-600 mb-6 leading-relaxed">{{ $service->description }}</p>
                            <a href="{{ route('register') }}" class="inline-flex items-center gap-2 text-makombe-700 font-bold hover:text-makombe-900 transition group/link">
                                Agendar agora <i class="fas fa-arrow-right text-sm transform group-hover/link:translate-x-1 transition-transform"></i>
                            </a>
                        </div>
                    </div>
                @empty
                    <!-- Fallback se não houver serviços no DB -->
                    <div class="col-span-3 text-center py-12 text-slate-500">
                        <i class="fas fa-stethoscope text-6xl text-slate-300 mb-4"></i>
                        <p class="text-lg">Serviços em breve...</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- ============================================ -->
    <!-- SOBRE NÓS -->
    <!-- ============================================ -->
    <section id="sobre" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                
                <!-- Imagem -->
                <div class="relative">
                    @php $aboutImagePath = \App\Models\SiteSetting::get('about_image_path'); @endphp
                    @if($aboutImagePath && file_exists(storage_path('app/public/' . $aboutImagePath)))
                        <img src="{{ asset('storage/' . $aboutImagePath) }}" alt="Sobre Makombe" class="rounded-3xl shadow-2xl w-full object-cover h-[500px]">
                    @else
                        <img src="https://images.unsplash.com/photo-1551190822-a9333d879b1f?w=800&q=80" alt="Equipa Makombe" class="rounded-3xl shadow-2xl w-full object-cover h-[500px]">
                    @endif
                    
                    <!-- Card Flutuante -->
                    <div class="absolute -bottom-8 -right-8 bg-white rounded-2xl shadow-xl p-6 hidden md:block border border-slate-100">
                        <div class="flex items-center gap-4">
                            <div class="w-16 h-16 bg-emerald-100 rounded-2xl flex items-center justify-center">
                                <i class="fas fa-award text-emerald-600 text-3xl"></i>
                            </div>
                            <div>
                                <p class="text-3xl font-black text-slate-900">{{ $settings['about_card_number'] ?? '10+' }}</p>
                                <p class="text-sm text-slate-600 font-medium">{{ $settings['about_card_text'] ?? 'Anos de experiência' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Texto -->
                <div>
                    <span class="inline-block px-4 py-1.5 bg-emerald-100 text-emerald-700 text-sm font-black rounded-full mb-4 uppercase tracking-wider">{{ $settings['about_subtitle'] ?? 'Sobre Nós' }}</span>
                    <h2 class="text-4xl md:text-5xl font-black text-slate-900 mb-6 leading-tight">
                        {{ $settings['about_title'] ?? 'Excelência em cuidados médicos em Moçambique' }}
                    </h2>
                    <p class="text-lg text-slate-600 mb-6 leading-relaxed">
                        {{ $settings['about_paragraph_1'] ?? 'O Makombe Consultório Médico é referência em atendimento de qualidade em Maputo. Com uma equipa multidisciplinar de profissionais altamente qualificados, oferecemos cuidados personalizados para cada paciente.' }}
                    </p>
                    <p class="text-lg text-slate-600 mb-8 leading-relaxed">
                        {{ $settings['about_paragraph_2'] ?? 'Nossa missão é proporcionar saúde acessível, humana e de excelência, utilizando tecnologia moderna e seguindo os mais altos padrões éticos e profissionais.' }}
                    </p>

                    <!-- Características -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-8">
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center flex-shrink-0 mt-1">
                                <i class="fas fa-check text-emerald-600"></i>
                            </div>
                            <div>
                                <p class="font-bold text-slate-900">{{ $settings['feature_1_title'] ?? 'Profissionais Qualificados' }}</p>
                                <p class="text-sm text-slate-600">{{ $settings['feature_1_desc'] ?? 'Médicos com especialização' }}</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center flex-shrink-0 mt-1">
                                <i class="fas fa-check text-emerald-600"></i>
                            </div>
                            <div>
                                <p class="font-bold text-slate-900">{{ $settings['feature_2_title'] ?? 'Tecnologia Moderna' }}</p>
                                <p class="text-sm text-slate-600">{{ $settings['feature_2_desc'] ?? 'Equipamentos de ponta' }}</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center flex-shrink-0 mt-1">
                                <i class="fas fa-check text-emerald-600"></i>
                            </div>
                            <div>
                                <p class="font-bold text-slate-900">{{ $settings['feature_3_title'] ?? 'Atendimento Humanizado' }}</p>
                                <p class="text-sm text-slate-600">{{ $settings['feature_3_desc'] ?? 'Cuidado com o paciente' }}</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center flex-shrink-0 mt-1">
                                <i class="fas fa-check text-emerald-600"></i>
                            </div>
                            <div>
                                <p class="font-bold text-slate-900">{{ $settings['feature_4_title'] ?? 'Preços Acessíveis' }}</p>
                                <p class="text-sm text-slate-600">{{ $settings['feature_4_desc'] ?? 'Saúde para todos' }}</p>
                            </div>
                        </div>
                    </div>

                    <a href="#contactos" class="inline-flex items-center gap-2 px-8 py-4 bg-makombe-700 hover:bg-makombe-800 text-white font-black rounded-xl shadow-lg shadow-makombe-500/30 transition transform hover:scale-105">
                        <i class="fas fa-phone"></i>
                        <span>Fale Connosco</span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================================ -->
    <!-- EQUIPA MÉDICA -->
    <!-- ============================================ -->
    <section id="equipa" class="py-24 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <span class="inline-block px-4 py-1.5 bg-makombe-100 text-makombe-700 text-sm font-black rounded-full mb-4 uppercase tracking-wider">Nossa Equipa</span>
                <h2 class="text-4xl md:text-5xl font-black text-slate-900 mb-4">
                    Profissionais <span class="text-makombe-gradient">dedicados</span> à sua saúde
                </h2>
                <p class="text-lg text-slate-600 max-w-2xl mx-auto">
                    Conheça alguns dos nossos médicos especialistas, comprometidos em oferecer o melhor atendimento.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                @forelse($team as $member)
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden hover:shadow-xl transition group">
                        <div class="h-72 overflow-hidden bg-slate-200 relative">
                            @if($member->photo_path && file_exists(storage_path('app/public/' . $member->photo_path)))
                                <img src="{{ asset('storage/' . $member->photo_path) }}" alt="{{ $member->name }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-slate-400">
                                    <i class="fas fa-user-md text-6xl"></i>
                                </div>
                            @endif
                            <!-- Overlay social -->
                            <div class="absolute inset-0 bg-makombe-900/80 opacity-0 group-hover:opacity-100 transition duration-300 flex items-center justify-center gap-3">
                                @if($member->facebook) <a href="{{ $member->facebook }}" target="_blank" class="w-10 h-10 bg-white text-makombe-700 rounded-full flex items-center justify-center hover:bg-makombe-100 transition"><i class="fab fa-facebook-f"></i></a> @endif
                                @if($member->linkedin) <a href="{{ $member->linkedin }}" target="_blank" class="w-10 h-10 bg-white text-makombe-700 rounded-full flex items-center justify-center hover:bg-makombe-100 transition"><i class="fab fa-linkedin-in"></i></a> @endif
                                @if($member->whatsapp) <a href="https://wa.me/{{ $member->whatsapp }}" target="_blank" class="w-10 h-10 bg-white text-emerald-600 rounded-full flex items-center justify-center hover:bg-emerald-50 transition"><i class="fab fa-whatsapp"></i></a> @endif
                            </div>
                        </div>
                        <div class="p-6 text-center">
                            <h3 class="text-lg font-black text-slate-900 mb-1">{{ $member->name }}</h3>
                            <p class="text-makombe-600 font-bold text-sm mb-3">{{ $member->position }}</p>
                            <p class="text-sm text-slate-600 line-clamp-2">{{ $member->description }}</p>
                        </div>
                    </div>
                @empty
                    <div class="col-span-4 text-center py-12 text-slate-500">
                        <i class="fas fa-users text-6xl text-slate-300 mb-4"></i>
                        <p class="text-lg">Equipa em breve...</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- ============================================ -->
    <!-- CONTACTOS -->
    <!-- ============================================ -->
    <section id="contactos" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <span class="inline-block px-4 py-1.5 bg-emerald-100 text-emerald-700 text-sm font-black rounded-full mb-4 uppercase tracking-wider">Contactos</span>
                <h2 class="text-4xl md:text-5xl font-black text-slate-900 mb-4">
                    Estamos aqui para <span class="text-makombe-gradient">ajudar</span>
                </h2>
                <p class="text-lg text-slate-600 max-w-2xl mx-auto">
                    Entre em contacto connosco. Estamos disponíveis para esclarecer todas as suas dúvidas.
                </p>
            </div>

            <div class="grid lg:grid-cols-2 gap-12">
                <!-- Informações -->
                <div class="space-y-6">
                    @php
                        $contacts = \App\Models\ContactInfo::where('is_active', true)->orderBy('order')->get();
                        $addresses = $contacts->where('type', 'address');
                        $phones = $contacts->where('type', 'phone');
                        $emails = $contacts->where('type', 'email');
                        $hours = $contacts->where('type', 'hours');
                    @endphp

                    @if($addresses->count() > 0)
                        <div class="flex items-start gap-4 p-6 bg-slate-50 rounded-2xl border border-slate-100 hover:shadow-md transition">
                            <div class="w-14 h-14 bg-makombe-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-map-marker-alt text-makombe-700 text-xl"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-slate-900 mb-1">Endereço</h3>
                                @foreach($addresses as $addr) <p class="text-slate-600">{{ $addr->value }}</p> @endforeach
                            </div>
                        </div>
                    @endif

                    @if($phones->count() > 0)
                        <div class="flex items-start gap-4 p-6 bg-slate-50 rounded-2xl border border-slate-100 hover:shadow-md transition">
                            <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-phone text-blue-700 text-xl"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-slate-900 mb-1">Telefone</h3>
                                @foreach($phones as $phone) <p class="text-slate-600">{{ $phone->label ? $phone->label . ': ' : '' }}{{ $phone->value }}</p> @endforeach
                            </div>
                        </div>
                    @endif

                    @if($emails->count() > 0)
                        <div class="flex items-start gap-4 p-6 bg-slate-50 rounded-2xl border border-slate-100 hover:shadow-md transition">
                            <div class="w-14 h-14 bg-purple-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-envelope text-purple-700 text-xl"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-slate-900 mb-1">Email</h3>
                                @foreach($emails as $email) <p class="text-slate-600">{{ $email->label ? $email->label . ': ' : '' }}{{ $email->value }}</p> @endforeach
                            </div>
                        </div>
                    @endif

                    @if($hours->count() > 0)
                        <div class="flex items-start gap-4 p-6 bg-slate-50 rounded-2xl border border-slate-100 hover:shadow-md transition">
                            <div class="w-14 h-14 bg-amber-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-clock text-amber-700 text-xl"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-slate-900 mb-1">Horário de Atendimento</h3>
                                @foreach($hours as $hour) <p class="text-slate-600">{{ $hour->label ? $hour->label . ': ' : '' }}{{ $hour->value }}</p> @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Mapa -->
                <div class="bg-slate-200 rounded-3xl overflow-hidden shadow-lg h-full min-h-[400px] border-4 border-white">
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3589.2284352877!2d32.5713!3d-25.9692!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMjXCsDU4JzA5LjEiUyAzMsKwMzQnMTYuNyJF!5e0!3m2!1spt-PT!2smz!4v1234567890"
                        width="100%" 
                        height="100%" 
                        style="border:0; min-height: 400px;" 
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
    <section class="py-20 bg-makombe-gradient relative overflow-hidden">
        <div class="absolute top-0 right-0 w-96 h-96 bg-white opacity-5 rounded-full -mr-48 -mt-48"></div>
        <div class="absolute bottom-0 left-0 w-64 h-64 bg-white opacity-5 rounded-full -ml-32 -mb-32"></div>
        
        <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-white">
            <h2 class="text-4xl md:text-5xl font-black mb-6">Pronto para cuidar da sua saúde?</h2>
            <p class="text-xl text-white/90 mb-8 max-w-2xl mx-auto">
                Crie sua conta grátis e tenha acesso a consultas, teleconsultas, histórico médico e muito mais.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('register') }}" class="px-8 py-4 bg-white text-makombe-800 hover:bg-slate-100 font-black rounded-xl shadow-xl transition flex items-center justify-center gap-2 text-lg transform hover:scale-105">
                    <i class="fas fa-user-plus"></i>
                    <span>Criar Conta Grátis</span>
                </a>
                @if(!empty($settings['whatsapp_number']))
                    <!-- Botão Flutuante WhatsApp (Sugestão 12) -->
<a href="https://wa.me/258841178857?text=Olá,%20gostaria%20de%20mais%20informações%20sobre%20o%20Makombe%20Consultório." 
   target="_blank" 
   rel="noopener noreferrer"
   class="fixed bottom-6 right-6 z-50 bg-[#25D366] hover:bg-[#20bd5a] text-white w-14 h-14 rounded-full flex items-center justify-center shadow-2xl transition-all duration-300 hover:scale-110 group animate-pulse"
   title="Fale connosco no WhatsApp">
    <i class="fab fa-whatsapp text-3xl"></i>
    <!-- Tooltip ao passar o rato -->
    <span class="absolute right-full mr-3 bg-gray-800 text-white text-xs px-3 py-1.5 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap shadow-lg pointer-events-none">
        Fale connosco (84 117 88 57)
    </span>
</a>
                @endif
            </div>
        </div>
    </section>

    <!-- ============================================ -->
    <!-- FOOTER -->
    <!-- ============================================ -->
    <footer class="glass-footer text-white pt-16 pb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-12">
                
                <!-- Coluna 1: Sobre -->
                <div>
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center overflow-hidden">
                            @if(file_exists(public_path('images/logo-icon.png')))
                                <img src="{{ asset('images/logo-icon.png') }}" alt="Makombe" class="w-full h-full object-contain p-1">
                            @else
                                <i class="fas fa-heartbeat text-makombe-700 text-xl"></i>
                            @endif
                        </div>
                        <div>
                            <h3 class="text-xl font-black">{{ $settings['site_name'] ?? 'MAKOMBE' }}</h3>
                            <p class="text-xs text-makombe-200 italic">"{{ $settings['site_slogan'] ?? 'Aqui você tem saúde' }}"</p>
                        </div>
                    </div>
                    <p class="text-makombe-100 text-sm mb-6 leading-relaxed">
                        Consultório médico de referência em Maputo, oferecendo cuidados de saúde de excelência, humanizados e com tecnologia de ponta.
                    </p>
                    <div class="flex gap-3">
                        @if(!empty($settings['facebook_url']))
                            <a href="{{ $settings['facebook_url'] }}" target="_blank" class="w-10 h-10 bg-white/10 hover:bg-white/20 rounded-lg flex items-center justify-center transition"><i class="fab fa-facebook-f"></i></a>
                        @endif
                        @if(!empty($settings['instagram_url']))
                            <a href="{{ $settings['instagram_url'] }}" target="_blank" class="w-10 h-10 bg-white/10 hover:bg-white/20 rounded-lg flex items-center justify-center transition"><i class="fab fa-instagram"></i></a>
                        @endif
                        @if(!empty($settings['whatsapp_number']))
                            <!-- Botão Flutuante WhatsApp (Sugestão 12) -->
<a href="https://wa.me/258841178857?text=Olá,%20gostaria%20de%20mais%20informações%20sobre%20o%20Makombe%20Consultório." 
   target="_blank" 
   rel="noopener noreferrer"
   class="fixed bottom-6 right-6 z-50 bg-[#25D366] hover:bg-[#20bd5a] text-white w-14 h-14 rounded-full flex items-center justify-center shadow-2xl transition-all duration-300 hover:scale-110 group animate-pulse"
   title="Fale connosco no WhatsApp">
    <i class="fab fa-whatsapp text-3xl"></i>
    <!-- Tooltip ao passar o rato -->
    <span class="absolute right-full mr-3 bg-gray-800 text-white text-xs px-3 py-1.5 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap shadow-lg pointer-events-none">
        Fale connosco (84 117 88 57)
    </span>
</a>
                        @endif
                    </div>
                </div>

                <!-- Coluna 2: Links Rápidos -->
                <div>
                    <h4 class="text-lg font-black mb-6 text-white">Links Rápidos</h4>
                    <ul class="space-y-3 text-sm">
                        <li><a href="#home" class="text-makombe-200 hover:text-white transition flex items-center gap-2"><i class="fas fa-chevron-right text-xs"></i> Início</a></li>
                        <li><a href="#servicos" class="text-makombe-200 hover:text-white transition flex items-center gap-2"><i class="fas fa-chevron-right text-xs"></i> Serviços</a></li>
                        <li><a href="#sobre" class="text-makombe-200 hover:text-white transition flex items-center gap-2"><i class="fas fa-chevron-right text-xs"></i> Sobre Nós</a></li>
                        <li><a href="#equipa" class="text-makombe-200 hover:text-white transition flex items-center gap-2"><i class="fas fa-chevron-right text-xs"></i> Equipa Médica</a></li>
                        <li><a href="#contactos" class="text-makombe-200 hover:text-white transition flex items-center gap-2"><i class="fas fa-chevron-right text-xs"></i> Contactos</a></li>
                    </ul>
                </div>

                <!-- Coluna 3: Serviços -->
                <div>
                    <h4 class="text-lg font-black mb-6 text-white">Serviços</h4>
                    <ul class="space-y-3 text-sm">
                        @forelse($services->take(5) as $service)
                            <li><a href="#servicos" class="text-makombe-200 hover:text-white transition flex items-center gap-2"><i class="fas fa-chevron-right text-xs"></i> {{ $service->title }}</a></li>
                        @empty
                            <li class="text-makombe-200">Consultas Presenciais</li>
                            <li class="text-makombe-200">Teleconsultas</li>
                            <li class="text-makombe-200">Exames Laboratoriais</li>
                        @endforelse
                    </ul>
                </div>

                <!-- Coluna 4: Contacto -->
                <div>
                    <h4 class="text-lg font-black mb-6 text-white">Contacto</h4>
                    <ul class="space-y-4 text-sm">
                        @foreach($addresses->take(1) as $addr)
                            <li class="flex items-start gap-3">
                                <i class="fas fa-map-marker-alt mt-1 text-emerald-400"></i>
                                <span class="text-makombe-100">{{ $addr->value }}</span>
                            </li>
                        @endforeach
                        @foreach($phones->take(1) as $phone)
                            <li class="flex items-center gap-3">
                                <i class="fas fa-phone text-emerald-400"></i>
                                <span class="text-makombe-100">{{ $phone->value }}</span>
                            </li>
                        @endforeach
                        @foreach($emails->take(1) as $email)
                            <li class="flex items-center gap-3">
                                <i class="fas fa-envelope text-emerald-400"></i>
                                <span class="text-makombe-100">{{ $email->value }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <!-- Rodapé Inferior -->
            <div class="pt-8 border-t border-white/10 flex flex-col md:flex-row items-center justify-between gap-4">
                <p class="text-sm text-makombe-200">
                    © {{ date('Y') }} {{ $settings['site_name'] ?? 'Makombe Consultório Médico' }}. Todos os direitos reservados.
                </p>
                <div class="flex items-center gap-4 text-sm">
                    <a href="{{ route('terms') }}" class="text-makombe-200 hover:text-white transition">Termos e Condições</a>
                    <span class="text-makombe-400">•</span>
                    <a href="{{ route('privacy') }}" class="text-makombe-200 hover:text-white transition">Política de Privacidade</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Botão Flutuante WhatsApp -->
    @if(!empty($settings['whatsapp_number']))
        <!-- Botão Flutuante WhatsApp (Sugestão 12) -->
<a href="https://wa.me/258841178857?text=Olá,%20gostaria%20de%20mais%20informações%20sobre%20o%20Makombe%20Consultório." 
   target="_blank" 
   rel="noopener noreferrer"
   class="fixed bottom-6 right-6 z-50 bg-[#25D366] hover:bg-[#20bd5a] text-white w-14 h-14 rounded-full flex items-center justify-center shadow-2xl transition-all duration-300 hover:scale-110 group animate-pulse"
   title="Fale connosco no WhatsApp">
    <i class="fab fa-whatsapp text-3xl"></i>
    <!-- Tooltip ao passar o rato -->
    <span class="absolute right-full mr-3 bg-gray-800 text-white text-xs px-3 py-1.5 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap shadow-lg pointer-events-none">
        Fale connosco (84 117 88 57)
    </span>
</a>
    @endif

    <!-- ============================================ -->
    <!-- SCRIPTS -->
    <!-- ============================================ -->
    <script>
        // Navbar scroll effect
        const navbar = document.getElementById('navbar');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                navbar.classList.add('nav-scrolled');
            } else {
                navbar.classList.remove('nav-scrolled');
            }
        });

        // Mobile menu
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const mobileMenu = document.getElementById('mobileMenu');
        const closeMobileMenu = document.getElementById('closeMobileMenu');
        const mobileOverlay = document.getElementById('mobileOverlay');
        const mobileLinks = document.querySelectorAll('.mobile-link');

        function openMenu() {
            mobileMenu.classList.remove('translate-x-full');
            mobileOverlay.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeMenu() {
            mobileMenu.classList.add('translate-x-full');
            mobileOverlay.classList.add('hidden');
            document.body.style.overflow = '';
        }

        mobileMenuBtn.addEventListener('click', openMenu);
        closeMobileMenu.addEventListener('click', closeMenu);
        mobileOverlay.addEventListener('click', closeMenu);
        mobileLinks.forEach(link => link.addEventListener('click', closeMenu));

        // Smooth scroll para links internos
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const targetId = this.getAttribute('href');
                if (targetId === '#') return;
                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    const offsetTop = targetElement.offsetTop - 80; // Compensar navbar fixa
                    window.scrollTo({ top: offsetTop, behavior: 'smooth' });
                }
            });
        });
    </script>
</body>
</html>