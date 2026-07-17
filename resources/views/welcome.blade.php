<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $empresa->nombre ?? config('app.name', 'Sistema de Grúas') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    @php
        $font = ($empresa->tipografia ?? 'Inter');
        $fontUrl = str_replace(' ', '+', $font);
        $c = $empresa->color ?? '#FFD500';
        $logo = $empresa->logo ? asset('storage/'.$empresa->logo) : asset('logo-gruas.jpeg');
        $bgImage = $empresa->imagen_fondo ? asset('storage/'.$empresa->imagen_fondo) : null;
        $empNombre = $empresa->nombre ?? config('app.name', 'Sistema de Grúas');
        $tel = $empresa->telefono_contacto ?? '(123) 456-7890';
        $email = $empresa->email_contacto ?? 'contacto@gruas.com';
        $direccion = $empresa->direccion ?? 'Estado de México';
        $footerTexto = $empresa->footer_texto ?? $empNombre.' — Confianza en el camino';
    @endphp
    <link href="https://fonts.bunny.net/css?family={{ $fontUrl }}:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(40px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        @keyframes float { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-10px); } }
        .animate-fade-in-up { animation: fadeInUp 0.8s ease-out both; }
        .animate-fade-in { animation: fadeIn 1s ease-out both; }
        .animate-float { animation: float 4s ease-in-out infinite; }
        .delay-200 { animation-delay: 0.2s; }
        .delay-400 { animation-delay: 0.4s; }
        .delay-600 { animation-delay: 0.6s; }
        .page-section { display: none; opacity: 0; transition: opacity 0.5s ease; }
        .page-section.active { display: flex; opacity: 1; }
        .page-section.active-block { display: block; opacity: 1; }
        .reveal { opacity: 0; transform: translateY(30px); transition: all 0.7s ease-out; }
        .reveal.visible { opacity: 1; transform: translateY(0); }
        .nav-link { position: relative; }
        .nav-link::after { content: ''; position: absolute; bottom: -4px; left: 0; width: 0; height: 2px; background: {{ $c }}; transition: width 0.3s ease; }
        .nav-link.active-nav { color: {{ $c }}; }
        .nav-link.active-nav::after { width: 100%; }
        .clay-card {
            background: rgba(60,50,40,0.6);
            border-radius: 20px;
            box-shadow: 8px 8px 16px rgba(0,0,0,0.3), -8px -8px 16px rgba(80,70,60,0.15);
            transition: all 0.3s ease;
        }
        .clay-card:hover {
            transform: translateY(-4px);
            box-shadow: 12px 12px 24px rgba(0,0,0,0.35), -12px -12px 24px rgba(80,70,60,0.2);
        }
        .clay-btn {
            border-radius: 16px;
            box-shadow: 4px 4px 8px rgba(0,0,0,0.3), -4px -4px 8px rgba(80,70,60,0.15);
            transition: all 0.25s ease;
        }
        .clay-btn:hover {
            transform: translateY(-2px);
            box-shadow: 6px 6px 12px rgba(0,0,0,0.35), -6px -6px 12px rgba(80,70,60,0.2);
        }
        .clay-btn:active {
            transform: scale(0.97);
            box-shadow: inset 2px 2px 6px rgba(0,0,0,0.3), inset -2px -2px 6px rgba(80,70,60,0.15);
        }
        .clay-input {
            border-radius: 16px;
            border: none;
            background: rgba(40,35,30,0.8);
            box-shadow: inset 3px 3px 8px rgba(0,0,0,0.3), inset -3px -3px 8px rgba(80,70,60,0.15);
            transition: all 0.2s ease;
        }
        .clay-input:focus {
            outline: none;
            background: rgba(50,45,40,0.9);
            box-shadow: inset 3px 3px 8px rgba(0,0,0,0.3), inset -3px -3px 8px rgba(80,70,60,0.15), 0 0 0 3px {{ $c }}33;
        }
    </style>
    <script>
        let revealObserver;
        function observeReveals() {
            if (revealObserver) revealObserver.disconnect();
            revealObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => { if (entry.isIntersecting) { entry.target.classList.add('visible'); } });
            }, { threshold: 0.15 });
            document.querySelectorAll('.reveal').forEach(el => revealObserver.observe(el));
        }
        function showSection(id) {
            document.querySelectorAll('.page-section').forEach(s => { s.classList.remove('active', 'active-block'); s.style.display = 'none'; });
            const section = document.getElementById(id);
            if (section) {
                section.style.display = id === 'inicio' ? 'flex' : 'block';
                section.classList.add(id === 'inicio' ? 'active' : 'active-block');
                setTimeout(observeReveals, 100);
            }
            document.querySelectorAll('.nav-link').forEach(link => {
                link.classList.remove('active-nav');
                if (link.dataset.section === id) link.classList.add('active-nav');
            });
        }
        document.addEventListener('DOMContentLoaded', function () { showSection('inicio'); });
    </script>
</head>
<body class="antialiased" style="font-family: '{{ $font }}', sans-serif; background: linear-gradient(135deg, #000000, #1a1a1a, #0F0F0F);">
    @if($bgImage)
    <div class="fixed inset-0 bg-cover bg-center bg-no-repeat opacity-20" style="background-image: url('{{ $bgImage }}');"></div>
    @endif
    <div class="min-h-screen flex flex-col relative z-10">
        <header class="w-full px-6 py-4">
            <nav class="flex items-center justify-between max-w-7xl mx-auto">
                <div class="flex items-center gap-2">
                    <img src="{{ $logo }}" alt="Logo" class="w-10 h-10 object-contain md:w-12 md:h-12">
                </div>
                <div class="flex items-center gap-6">
                    <a href="#" onclick="showSection('inicio')" class="nav-link text-white/80 hover:text-white text-sm font-medium transition-colors duration-200" data-section="inicio">INICIO</a>
                    <a href="#" onclick="showSection('nosotros')" class="nav-link text-white/80 hover:text-white text-sm font-medium transition-colors duration-200" data-section="nosotros">NOSOTROS</a>
                    <a href="#" onclick="showSection('servicio')" class="nav-link text-white/80 hover:text-white text-sm font-medium transition-colors duration-200" data-section="servicio">SERVICIO</a>
                    <a href="#" onclick="showSection('contacto')" class="nav-link text-white/80 hover:text-white text-sm font-medium transition-colors duration-200" data-section="contacto">CONTACTO</a>
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="inline-flex items-center px-5 py-2 bg-white/10 hover:bg-white/20 text-white rounded-lg text-sm font-medium transition-all duration-200">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="inline-flex items-center px-5 py-2 text-white/80 hover:text-white text-sm font-medium transition-colors duration-200">Iniciar Sesión</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="inline-flex items-center px-5 py-2 rounded-lg text-sm font-semibold transition-all duration-200 clay-btn" style="background:{{ $c }}; color:black;">Registrarse</a>
                            @endif
                        @endauth
                    @endif
                </div>
            </nav>
        </header>

        <section id="inicio" class="page-section flex-1 flex items-center justify-center px-6 py-12">
            <div class="text-center max-w-2xl">
                <h1 class="text-4xl lg:text-5xl font-extrabold text-white mb-3 animate-fade-in-up">{{ $empNombre }}</h1>
                <p class="text-lg text-gray-400 mb-10 animate-fade-in-up delay-200">Sistema Integral de Gestión de Gruas y Asistencia Víal</p>
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4 animate-fade-in-up delay-400">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="inline-flex items-center px-8 py-3 text-black rounded-xl font-bold text-base transition-all duration-200 hover:scale-105 clay-btn" style="background:{{ $c }};">
                            Ir al Dashboard
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" /></svg>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="inline-flex items-center px-8 py-3 text-black rounded-xl font-bold text-base transition-all duration-200 hover:scale-105 clay-btn" style="background:{{ $c }};">
                            Comenzar
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" /></svg>
                        </a>
                    @endauth
                </div>
            </div>
        </section>

        <section id="nosotros" class="page-section w-full py-20 px-6" style="background: linear-gradient(135deg, #0a0a0a, #1a1a1a);">
            <div class="max-w-5xl mx-auto">
                <div class="text-center mb-16 reveal">
                    <h2 class="text-2xl lg:text-3xl font-bold mb-6" style="color:{{ $c }};">¿Quiénes somos?</h2>
                    <p class="text-xl lg:text-2xl text-gray-300 leading-relaxed max-w-4xl mx-auto">Somos una empresa dedicada a brindar servicios de grúas y asistencia vial, comprometida con la seguridad y satisfacción de nuestros clientes.</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-16">
                    <div class="clay-card p-8 transition-all duration-300 reveal">
                        <div class="w-14 h-14 flex items-center justify-center rounded-xl mb-5" style="background:{{ $c }}33;">
                            <svg class="w-7 h-7" style="color:{{ $c }};" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                        </div>
                        <h3 class="text-xl font-bold mb-3" style="color:{{ $c }};">MISIÓN</h3>
                        <p class="text-gray-400 leading-relaxed">Brindar servicios de grúas y asistencia vial con rapidez, seguridad y profesionalismo, superando las expectativas de nuestros clientes y garantizando su tranquilidad en cada servicio.</p>
                    </div>
                    <div class="clay-card p-8 transition-all duration-300 reveal">
                        <div class="w-14 h-14 flex items-center justify-center rounded-xl mb-5" style="background:{{ $c }}33;">
                            <svg class="w-7 h-7" style="color:{{ $c }};" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                        </div>
                        <h3 class="text-xl font-bold mb-3" style="color:{{ $c }};">VISIÓN</h3>
                        <p class="text-gray-400 leading-relaxed">Ser la empresa líder en servicios de grúas y asistencia vial en la región, reconocida por nuestra excelencia operativa, innovación constante y compromiso con la seguridad vial.</p>
                    </div>
                </div>
                <div class="max-w-4xl mx-auto mb-16 text-center reveal">
                    <h3 class="text-2xl font-bold mb-6" style="color:{{ $c }};">NUESTRA PRIORIDAD</h3>
                    <div class="clay-card p-8 transition-all duration-300 text-left">
                        <p class="text-gray-300 leading-relaxed text-lg">Atender cada servicio en el momento justo, con profesionalismo, eficacia y calidad, dando cuidado extremo durante el traslado, Guarda y custodia, para así lograr la satisfacción total de nuestros clientes. Proporcionando la mejor orientación y atención al usuario para la recuperación de su unidad.</p>
                    </div>
                </div>
                <div class="text-center mb-10">
                    <h3 class="text-2xl font-bold mb-8 reveal" style="color:{{ $c }};">NUESTROS VALORES</h3>
                    <div class="grid grid-cols-1 gap-6">
                        <div class="clay-card p-6 text-left transition-all duration-300 reveal">
                            <h4 class="font-bold text-lg mb-2" style="color:{{ $c }};">RESPONSABILIDAD</h4>
                            <p class="text-gray-400 leading-relaxed">Trabajando de la mano con las diferentes autoridades de manera adecuada, para así lograr la entera satisfacción de los usuarios y de la misma empresa.</p>
                        </div>
                        <div class="clay-card p-6 text-left transition-all duration-300 reveal">
                            <h4 class="font-bold text-lg mb-2" style="color:{{ $c }};">CONFIANZA</h4>
                            <p class="text-gray-400 leading-relaxed">Dar la tranquilidad a los ciudadanía a través de la tecnología de nuestros equipos y la capacitación de nuestro personal.</p>
                        </div>
                        <div class="clay-card p-6 text-left transition-all duration-300 reveal">
                            <h4 class="font-bold text-lg mb-2" style="color:{{ $c }};">RESPETO</h4>
                            <p class="text-gray-400 leading-relaxed">En todo momento y bajo cualquier circunstancia nuestro personal de cualquier área debe dirigirse al ciudadano de una forma adecuada brindando la atención que se merece.</p>
                        </div>
                    </div>
                </div>
                <div class="text-center reveal">
                    <h3 class="text-2xl font-bold mb-8" style="color:{{ $c }};">ACCESOS RÁPIDOS</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 max-w-3xl mx-auto">
                        <a href="#" onclick="showSection('servicio')" class="clay-card p-6 text-center transition-all duration-300 group">
                            <div class="w-12 h-12 mx-auto mb-3 flex items-center justify-center rounded-xl group-hover:scale-110 transition-transform duration-300" style="background:{{ $c }}33;">
                                <svg class="w-6 h-6" style="color:{{ $c }};" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /></svg>
                            </div>
                            <h4 class="text-white font-bold">Servicios</h4>
                            <p class="text-gray-400 text-sm mt-1">Conoce todos nuestros servicios</p>
                        </a>
                        <div class="clay-card p-6 text-center transition-all duration-300 group">
                            <div class="w-12 h-12 mx-auto mb-3 flex items-center justify-center rounded-xl group-hover:scale-110 transition-transform duration-300" style="background:{{ $c }}33;">
                                <svg class="w-6 h-6" style="color:{{ $c }};" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            </div>
                            <h4 class="text-white font-bold">Horarios</h4>
                            <p class="text-gray-400 text-sm mt-1">Disponibles 24/7 los 365 días del año</p>
                        </div>
                        <a href="#" onclick="showSection('contacto')" class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-xl p-6 text-center transition-all duration-300 group">
                            <div class="w-12 h-12 mx-auto mb-3 flex items-center justify-center rounded-xl group-hover:scale-110 transition-transform duration-300" style="background:{{ $c }}33;">
                                <svg class="w-6 h-6" style="color:{{ $c }};" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                            </div>
                            <h4 class="text-white font-bold">Contacto</h4>
                            <p class="text-gray-400 text-sm mt-1">Comunícate con nosotros</p>
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <section id="servicio" class="page-section w-full py-20 px-6">
            <div class="max-w-6xl mx-auto text-center">
                <h2 class="text-2xl lg:text-3xl font-bold mb-4 reveal" style="color:{{ $c }};">SERVICIOS</h2>
                <p class="text-lg text-gray-400 mb-12 reveal">Ofrecemos soluciones rápidas y confiables para cualquier emergencia vial</p>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="clay-card p-8 text-center transition-all duration-300 hover:-translate-y-2 reveal">
                        <div class="w-16 h-16 mx-auto mb-5 flex items-center justify-center rounded-xl" style="background:{{ $c }}33;">
                            <svg class="w-8 h-8" style="color:{{ $c }};" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-3">Arrastre</h3>
                        <p class="text-gray-400 leading-relaxed">Servicio de arrastre de vehículos ligeros y pesados. Trasladamos tu vehículo de manera segura al destino que necesites.</p>
                    </div>
                    <div class="clay-card p-8 text-center transition-all duration-300 hover:-translate-y-2 reveal">
                        <div class="w-16 h-16 mx-auto mb-5 flex items-center justify-center rounded-xl" style="background:{{ $c }}33;">
                            <svg class="w-8 h-8" style="color:{{ $c }};" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-3">Asistencia</h3>
                        <p class="text-gray-400 leading-relaxed">Asistencia vial básica: paso de corriente, cambio de llanta, cerraduría, suministro de gasolina, etc.</p>
                    </div>
                    <div class="clay-card p-8 text-center transition-all duration-300 hover:-translate-y-2 reveal">
                        <div class="w-16 h-16 mx-auto mb-5 flex items-center justify-center rounded-xl" style="background:{{ $c }}33;">
                            <svg class="w-8 h-8" style="color:{{ $c }};" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" /></svg>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-3">Plataforma</h3>
                        <p class="text-gray-400 leading-relaxed">Servicio de plataforma para transporte de vehículos. Ideal para mudanzas, traslados y distribución.</p>
                    </div>
                    <div class="clay-card p-8 text-center transition-all duration-300 hover:-translate-y-2 reveal">
                        <div class="w-16 h-16 mx-auto mb-5 flex items-center justify-center rounded-xl" style="background:{{ $c }}33;">
                            <svg class="w-8 h-8" style="color:{{ $c }};" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" /></svg>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-3">Rescate</h3>
                        <p class="text-gray-400 leading-relaxed">Servicio de rescate vial para vehículos varados. Llegamos al lugar para sacar tu vehículo de cualquier situación.</p>
                    </div>
                </div>
            </div>
        </section>

        <section id="contacto" class="page-section w-full py-20 px-6" style="background: linear-gradient(135deg, #0a0a0a, #1a1a1a);">
            <div class="max-w-4xl mx-auto text-center">
                <h2 class="text-2xl lg:text-3xl font-bold mb-6 reveal" style="color:{{ $c }};">CONTACTO</h2>
                <p class="text-lg text-gray-400 mb-8 reveal">Estamos listos para atenderte</p>
                <div class="clay-card p-4 mb-8 text-center reveal max-w-md mx-auto" style="background:{{ $c }}15;">
                    <p class="text-sm font-semibold" style="color:{{ $c }};">EMERGENCIAS 24/7</p>
                    <p class="text-xl font-bold" style="color:{{ $c }};">{{ $tel }}</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-left">
                    <div class="clay-card p-6 transition-all duration-300 reveal">
                        <h3 class="text-white font-bold mb-2">Teléfono</h3>
                        <p class="text-gray-400">{{ $tel }}</p>
                    </div>
                    <div class="clay-card p-6 transition-all duration-300 reveal">
                        <h3 class="text-white font-bold mb-2">Email</h3>
                        <p class="text-gray-400">{{ $email }}</p>
                    </div>
                    <div class="clay-card p-6 transition-all duration-300 reveal">
                        <h3 class="text-white font-bold mb-2">Ubicación</h3>
                        <p class="text-gray-400">{{ $direccion }}</p>
                    </div>
                </div>
            </div>
        </section>

        <footer class="py-4 text-center">
            <p class="text-sm text-gray-600">&copy; {{ date('Y') }} {{ $footerTexto }}</p>
        </footer>
    </div>
</body>
</html>
