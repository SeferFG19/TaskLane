<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Welcome --}}
            <div class="bg-white p-6 rounded-lg shadow mb-8">
                <h3 class="text-2xl font-bold text-gray-900 mb-2">
                    ¡Hola {{ Auth::user()->name }}!
                </h3>
                <p class="text-gray-600">
                    Bienvenido a Tasklane. Selecciona un proyecto para empezar.
                </p>
            </div>

            {{-- Projects --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                @foreach(\App\Models\Project::whereHas('createdBy', fn($q) =>
                $q->where('users.id', Auth::id())
                )->get() as $project)

                <a href="{{ route('projects.show', $project) }}"
                    class="block p-6 bg-white shadow hover:shadow-lg rounded-lg border border-gray-200">

                    <h3 class="text-xl font-semibold text-gray-900">
                        {{ $project->name }}
                    </h3>

                    <p class="mt-2 text-gray-600 text-sm">
                        {{ Str::limit($project->description, 80) }}
                    </p>

                    <div class="mt-4 text-indigo-600 font-medium">
                        Ver proyecto →
                    </div>
                </a>

                @endforeach

            </div>

        </div>
    </div>
</x-app-layout>