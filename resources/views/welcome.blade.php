<x-guest-layout>

    <header>
        <nav class="navbar">
            <div class="logo">
                <img src="{{ asset('images/logoTasklane.svg') }}" alt="Logo de la app">
            </div>
            <ul class="nav-buttons">
                <li><a href="#features" class="btn-nav">Características</a></li>
                <li><a href="#about" class="btn-nav">Sobre Nosotros</a></li>
                <li><a href="#contact" class="btn-nav">Contacto</a></li>
            </ul>
            <ul class="nav-login">
                <li><a href="{{ route('login') }}" class="btn-login">Iniciar Sesión</a></li>
                <li><a href="{{ route('register') }}" class="btn-login">Registrarse</a></li>
            </ul>
        </nav>

        <div class="hero">
            <div class="hero-text">
                <h1>TASKLANE</h1>
                <p>Gestiona, organiza y desarrolla tus proyectos con esta herramienta <br> hecha por desarrolladores para desarrolladores</p>
                <a href="{{ route('register') }}" class="btn">Comenzar ahora</a>
            </div>
            <div class="hero-image-frame">
                <img src="{{ asset('images/dashboard.png') }}" alt="Vista del dashboard" class="hero-image">
            </div>
        </div>
    </header>

    <section id="caracteristicas">
        <h2 class="titles">Características</h2>

        <div class="sections-container">

            <div class="section">
                <h3>Gestión de Tareas</h3>
                <p>Organiza y asigna tareas a tu equipo de forma sencilla.</p>
            </div>

            <div class="section">
                <h3>Colaboración en Tiempo Real</h3>
                <p>Comunícate y comparte avances instantáneamente.</p>
            </div>

            <div class="section">
                <h3>Integración con Tu Flujo</h3>
                <p>Adáptalo a tus necesidades con flexibilidad total.</p>
            </div>

        </div>
    </section>

    <section id="comienza">
        <h2 class="titles">Comienza</h2>
        <div class="sections-container">
            <div class="section">
                <h3>Crea un proyecto</h3>
                <p>Selecciona el botón crear proyecto en tu dashboard define de qué trata.</p>
            </div>
            <div class="section">
                <h3>Crea tareas</h3>
                <p>Dentro de tu proyecto podrás crear tareas y asignarlas a tus compañeros.</p>
            </div>
            <div class="section">
                <h3>Define el estado de las tareas</h3>
                <p>Asigna un estado a tu tarea a la vez que puedes indicar un comentario para el resto del equipo.</p>
            </div>
            <div class="section">
                <h3>Completa las tareas</h3>
                <p>Una vez acabadas las tareas puedes poner su estado completado.</p>
            </div>
        </div>
    </section>

    <section id="perfiles">
        <h2 class="titles">Perfiles</h2>
        <div class="sections-container">
            <div class="section">
                <h3>Administrador</h3>
                <p>Es el encargado de crear las tareas del proyecto y asignarlas a los empleados.</p>
            </div>
            <div class="section">
                <h3>Empleado</h3>
                <p>Su trabajo consiste en realizar la tarea a que le han asignado.</p>
            </div>
            <div class="section">
                <h3>Supervisor</h3>
                <p>Es un perfil que puede visualizar el estado de las tareas y comentar con el resto del equipo.</p>
            </div>
        </div>
    </section>
    <footer>
        <p>&copy; 2025 Tasklane - Sergio Ferrón García. Todos los derechos reservados.</p>
    </footer>

</x-guest-layout>