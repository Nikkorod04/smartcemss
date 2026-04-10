<x-admin-layout header="Extension Programs">
    <div class="bg-white rounded-lg shadow-lg -mx-6 -my-8 md:-mx-8 md:-my-8 p-6 md:p-8 space-y-6">
        <!-- Livewire Programs Grid -->
        <livewire:programs-grid />
    </div>

    <!-- Floating Action Button - Create Program -->
    @if (auth()->user()->role === 'director')
    <a href="{{ route('programs.create') }}" 
       class="fixed bottom-8 right-8 z-40 w-16 h-16 bg-lnu-blue hover:bg-blue-700 text-white rounded-full shadow-lg hover:shadow-2xl transition-all duration-300 flex items-center justify-center group"
       title="Create New Program">
        <svg class="w-8 h-8 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
        </svg>
    </a>
    @endif
</x-admin-layout>
