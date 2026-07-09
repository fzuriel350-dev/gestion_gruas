<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Sistema de Grúas') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(40px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
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
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 0;
            width: 0;
            height: 2px;
            background: #FFD500;
            transition: width 0.3s ease;
        }
        .nav-link.active-nav { color: #FFD500; }
        .nav-link.active-nav::after { width: 100%; }
    </style>
    <script>
        let revealObserver;

        function observeReveals() {
            if (revealObserver) revealObserver.disconnect();
            revealObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                    }
                });
            }, { threshold: 0.15 });
            document.querySelectorAll('.reveal').forEach(el => revealObserver.observe(el));
        }

        function showSection(id) {
            document.querySelectorAll('.page-section').forEach(s => {
                s.classList.remove('active', 'active-block');
                s.style.display = 'none';
            });
            const section = document.getElementById(id);
            if (section) {
                section.style.display = id === 'inicio' ? 'flex' : 'block';
                section.classList.add(id === 'inicio' ? 'active' : 'active-block');
                setTimeout(observeReveals, 100);
            }
            document.querySelectorAll('.nav-link').forEach(link => {
                link.classList.remove('active-nav');
                if (link.dataset.section === id) {
                    link.classList.add('active-nav');
                }
            });
        }
        document.addEventListener('DOMContentLoaded', function () {
            showSection('inicio');
        });
    </script>
</head>
<body class="font-sans antialiased" style="background: linear-gradient(135deg, #000000, #1a1a1a, #0F0F0F);">
    <div class="min-h-screen flex flex-col">
        <header class="w-full px-6 py-4">
            <nav class="flex items-center justify-between max-w-7xl mx-auto">
                <div class="flex items-center gap-2">
                    <img src="{{ asset('logo-gruas.jpeg') }}" alt="Logo" class="w-36 h-36 object-contain">
                </div>
                <div class="flex items-center gap-6">
                    <a href="#" onclick="showSection('inicio')" class="nav-link text-white/80 hover:text-white text-sm font-medium transition-colors duration-200" data-section="inicio">INICIO</a>
                    <a href="#" onclick="showSection('nosotros')" class="nav-link text-white/80 hover:text-white text-sm font-medium transition-colors duration-200" data-section="nosotros">NOSOTROS</a>
                    <a href="#" onclick="showSection('servicio')" class="nav-link text-white/80 hover:text-white text-sm font-medium transition-colors duration-200" data-section="servicio">SERVICIO</a>
                    <a href="#" onclick="showSection('contacto')" class="nav-link text-white/80 hover:text-white text-sm font-medium transition-colors duration-200" data-section="contacto">CONTACTO</a>
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="inline-flex items-center px-5 py-2 bg-white/10 hover:bg-white/20 text-white rounded-lg text-sm font-medium transition-all duration-200">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="inline-flex items-center px-5 py-2 text-white/80 hover:text-white text-sm font-medium transition-colors duration-200">
                                Iniciar Sesión
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="inline-flex items-center px-5 py-2 bg-yellow-500 hover:bg-yellow-400 text-black rounded-lg text-sm font-semibold transition-all duration-200">
                                    Registrarse
                                </a>
                            @endif
                        @endauth
                    @endif
                </div>
            </nav>
        </header>

        <section id="inicio" class="page-section flex-1 flex items-center justify-center px-6 py-12">
            <div class="text-center max-w-2xl">
                <div class="mx-auto mb-6 flex items-center justify-center animate-float">
                    <img src="{{ asset('logo-gruas.jpeg') }}" alt="Logo" class="w-96 h-96 object-contain drop-shadow-[0_0_50px_rgba(255,213,0,0.6)] rounded-xl">
                </div>

                <h1 class="text-4xl lg:text-5xl font-extrabold text-white mb-3 animate-fade-in-up">
                    {{ config('app.name', 'Sistema de Grúas') }}
                </h1>
                <p class="text-lg text-gray-400 mb-10 animate-fade-in-up delay-200">
                    Gestión eficiente de servicios de grúas y asistencia vial
                </p>

                <div class="flex flex-col sm:flex-row items-center justify-center gap-4 animate-fade-in-up delay-400">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="inline-flex items-center px-8 py-3 bg-yellow-500 hover:bg-yellow-400 text-black rounded-xl font-bold text-base transition-all duration-200 shadow-lg shadow-yellow-500/25 hover:scale-105">
                            Ir al Dashboard
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="inline-flex items-center px-8 py-3 bg-yellow-500 hover:bg-yellow-400 text-black rounded-xl font-bold text-base transition-all duration-200 shadow-lg shadow-yellow-500/25 hover:scale-105">
                            Comenzar
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                        </a>
                    @endauth
                </div>
            </div>
        </section>

        <section id="nosotros" class="page-section w-full py-20 px-6" style="background: linear-gradient(135deg, #0a0a0a, #1a1a1a);">
            <div class="max-w-5xl mx-auto">
                <div class="text-center mb-16 reveal">
                    <h2 class="text-2xl lg:text-3xl font-bold text-yellow-500 mb-6">¿Quiénes somos?</h2>
                    <p class="text-xl lg:text-2xl text-gray-300 leading-relaxed max-w-4xl mx-auto">
                        Somos una empresa dedicada a brindar servicios de grúas y asistencia vial, comprometida con la seguridad y satisfacción de nuestros clientes.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-16">
                    <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-2xl p-8 hover:bg-white/10 hover:border-yellow-500/50 transition-all duration-300 reveal">
                        <div class="w-14 h-14 flex items-center justify-center bg-yellow-500/20 rounded-xl mb-5">
                            <svg class="w-7 h-7 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-yellow-500 mb-3">MISIÓN</h3>
                        <p class="text-gray-400 leading-relaxed">
                            Brindar servicios de grúas y asistencia vial con rapidez, seguridad y profesionalismo, superando las expectativas de nuestros clientes y garantizando su tranquilidad en cada servicio.
                        </p>
                    </div>
                    <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-2xl p-8 hover:bg-white/10 hover:border-yellow-500/50 transition-all duration-300 reveal">
                        <div class="w-14 h-14 flex items-center justify-center bg-yellow-500/20 rounded-xl mb-5">
                            <svg class="w-7 h-7 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-yellow-500 mb-3">VISIÓN</h3>
                        <p class="text-gray-400 leading-relaxed">
                            Ser la empresa líder en servicios de grúas y asistencia vial en la región, reconocida por nuestra excelencia operativa, innovación constante y compromiso con la seguridad vial.
                        </p>
                    </div>
                </div>

                <div class="max-w-4xl mx-auto mb-16 text-center reveal">
                    <h3 class="text-2xl font-bold text-yellow-500 mb-6">NUESTRA PRIORIDAD</h3>
                    <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-2xl p-8 hover:bg-white/10 hover:border-yellow-500/50 transition-all duration-300 text-left">
                        <p class="text-gray-300 leading-relaxed text-lg">
                            Atender cada servicio en el momento justo, con profesionalismo, eficacia y calidad, dando cuidado extremo durante el traslado, Guarda y custodia, para así lograr la satisfacción total de nuestros clientes. Proporcionando la mejor orientación y atención al usuario para la recuperación de su unidad.
                        </p>
                    </div>
                </div>

                <div class="text-center mb-10">
                    <h3 class="text-2xl font-bold text-yellow-500 mb-8 reveal">NUESTROS VALORES</h3>
                    <div class="grid grid-cols-1 gap-6">
                        <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-xl p-6 text-left hover:bg-white/10 hover:border-yellow-500/50 transition-all duration-300 reveal">
                            <h4 class="text-yellow-500 font-bold text-lg mb-2">RESPONSABILIDAD</h4>
                            <p class="text-gray-400 leading-relaxed">Trabajando de la mano con las diferentes autoridades de manera adecuada, para así lograr la entera satisfacción de los usuarios y de la misma empresa.</p>
                        </div>
                        <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-xl p-6 text-left hover:bg-white/10 hover:border-yellow-500/50 transition-all duration-300 reveal">
                            <h4 class="text-yellow-500 font-bold text-lg mb-2">CONFIANZA</h4>
                            <p class="text-gray-400 leading-relaxed">Dar la tranquilidad a los ciudadanía a través de la tecnología de nuestros equipos y la capacitación de nuestro personal.</p>
                        </div>
                        <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-xl p-6 text-left hover:bg-white/10 hover:border-yellow-500/50 transition-all duration-300 reveal">
                            <h4 class="text-yellow-500 font-bold text-lg mb-2">RESPETO</h4>
                            <p class="text-gray-400 leading-relaxed">En todo momento y bajo cualquier circunstancia nuestro personal de cualquier área debe dirigirse al ciudadano de una forma adecuada brindando la atención que se merece.</p>
                        </div>
                    </div>
                </div>

                <div class="text-center reveal">
                    <h3 class="text-2xl font-bold text-yellow-500 mb-8">ACCESOS RÁPIDOS</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 max-w-3xl mx-auto">
                        <a href="#" onclick="showSection('servicio')" class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-xl p-6 text-center hover:bg-white/10 hover:border-yellow-500/50 transition-all duration-300 group">
                            <div class="w-12 h-12 mx-auto mb-3 flex items-center justify-center bg-yellow-500/20 rounded-xl group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-6 h-6 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                </svg>
                            </div>
                            <h4 class="text-white font-bold">Servicios</h4>
                            <p class="text-gray-400 text-sm mt-1">Conoce todos nuestros servicios</p>
                        </a>
                        <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-xl p-6 text-center hover:bg-white/10 hover:border-yellow-500/50 transition-all duration-300 group">
                            <div class="w-12 h-12 mx-auto mb-3 flex items-center justify-center bg-yellow-500/20 rounded-xl group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-6 h-6 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h4 class="text-white font-bold">Horarios</h4>
                            <p class="text-gray-400 text-sm mt-1">Disponibles 24/7 los 365 días del año</p>
                        </div>
                        <a href="#" onclick="showSection('contacto')" class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-xl p-6 text-center hover:bg-white/10 hover:border-yellow-500/50 transition-all duration-300 group">
                            <div class="w-12 h-12 mx-auto mb-3 flex items-center justify-center bg-yellow-500/20 rounded-xl group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-6 h-6 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
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
                <h2 class="text-2xl lg:text-3xl font-bold text-yellow-500 mb-4 reveal">SERVICIOS</h2>
                <p class="text-lg text-gray-400 mb-12 reveal">Ofrecemos soluciones rápidas y confiables para cualquier emergencia vial</p>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-2xl p-8 text-center hover:bg-white/10 hover:border-yellow-500/50 transition-all duration-300 hover:-translate-y-2 reveal">
                        <div class="w-16 h-16 mx-auto mb-5 flex items-center justify-center bg-yellow-500/20 rounded-xl">
                            <svg class="w-8 h-8 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-3">Arrastre</h3>
                        <p class="text-gray-400 leading-relaxed">Servicio de arrastre de vehículos ligeros y pesados. Trasladamos tu vehículo de manera segura al destino que necesites.</p>
                    </div>
                    <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-2xl p-8 text-center hover:bg-white/10 hover:border-yellow-500/50 transition-all duration-300 hover:-translate-y-2 reveal">
                        <div class="w-16 h-16 mx-auto mb-5 flex items-center justify-center bg-yellow-500/20 rounded-xl">
                            <svg class="w-8 h-8 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-3">Rescate</h3>
                        <p class="text-gray-400 leading-relaxed">Servicio de rescate vial para vehículos varados. Llegamos al lugar para sacar tu vehículo de cualquier situación.</p>
                    </div>
                    <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-2xl p-8 text-center hover:bg-white/10 hover:border-yellow-500/50 transition-all duration-300 hover:-translate-y-2 reveal">
                        <div class="w-16 h-16 mx-auto mb-5 flex items-center justify-center bg-yellow-500/20 rounded-xl">
                            <svg class="w-8 h-8 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-3">Auxilio Vial</h3>
                        <p class="text-gray-400 leading-relaxed">Asistencia vial básica: paso de corriente, cambio de llanta, cerrraduría, suministro de gasolina, etc.</p>
                    </div>
                </div>
            </div>
        </section>

        <section id="contacto" class="page-section w-full py-20 px-6" style="background: linear-gradient(135deg, #0a0a0a, #1a1a1a);">
            <div class="max-w-4xl mx-auto text-center">
                <h2 class="text-2xl lg:text-3xl font-bold text-yellow-500 mb-6 reveal">CONTACTO</h2>
                <p class="text-lg text-gray-400 mb-8 reveal">Estamos listos para atenderte</p>
                <div class="bg-yellow-500/10 border border-yellow-500/30 rounded-xl p-4 mb-8 text-center reveal max-w-md mx-auto">
                    <p class="text-yellow-500 text-sm font-semibold">🚨 EMERGENCIAS 24/7</p>
                    <p class="text-yellow-500 text-xl font-bold">(123) 456-7890</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-left">
                    <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-2xl p-6 hover:bg-white/10 transition-all duration-300 reveal">
                        <h3 class="text-white font-bold mb-2">Teléfono</h3>
                        <p class="text-gray-400">(123) 456-7890</p>
                    </div>
                    <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-2xl p-6 hover:bg-white/10 transition-all duration-300 reveal">
                        <h3 class="text-white font-bold mb-2">Email</h3>
                        <p class="text-gray-400">contacto@gruas.com</p>
                    </div>
                    <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-2xl p-6 hover:bg-white/10 transition-all duration-300 reveal">
                        <h3 class="text-white font-bold mb-2">Ubicación</h3>
                        <p class="text-gray-400">Estado de México</p>
                    </div>
                </div>
            </div>
        </section>

        <footer class="py-4 text-center">
            <p class="text-sm text-gray-600">&copy; {{ date('Y') }} {{ config('app.name', 'Sistema de Grúas') }}. Todos los derechos reservados.</p>
        </footer>
    </div>
</body>
</html>
