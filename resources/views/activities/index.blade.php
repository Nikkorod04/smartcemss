<x-admin-layout header="Activities">
    <div class="min-h-screen bg-white py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @livewire('ActivitiesGrid')
        </div>
    </div>

    <!-- Floating Action Button - Add Activity -->
    <a href="{{ route('activities.create') }}" 
       class="fixed bottom-8 right-8 z-40 w-16 h-16 bg-lnu-blue hover:bg-blue-700 text-white rounded-full shadow-lg hover:shadow-2xl transition-all duration-300 flex items-center justify-center group"
       title="Add New Activity">
        <svg class="w-8 h-8 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
        </svg>
    </a>
</x-admin-layout>
