<x-guest-layout>
    <div class="bg-gray-50">

        {{-- Hero --}}
        <section class="pt-20 pb-32 text-center">
            <h1 class="text-5xl font-bold text-gray-900">
                Gestiona tus proyectos con <span class="text-indigo-600">Tasklane</span>
            </h1>

            <p class="mt-4 text-xl text-gray-600 max-w-2xl mx-auto">
                Organiza tus tareas, colabora con tu equipo y mantén el proyecto en movimiento.
            </p>

            <div class="mt-8">
                <a href="{{ route('register') }}"
                    class="px-8 py-4 bg-indigo-600 text-white rounded-lg shadow hover:bg-indigo-700">
                    Empezar ahora
                </a>
            </div>
        </section>

        {{-- Footer --}}
        <footer class="py-10 text-center text-gray-600">
            Tasklane © {{ date('Y') }} — Proyecto DAW
        </footer>

    </div>
</x-guest-layout>