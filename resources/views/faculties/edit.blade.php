<x-admin-layout header="{{ isset($faculty) ? 'Edit Faculty Member' : 'Create Faculty Member' }}">
    <div class="max-w-3xl">
        <form method="POST" action="{{ isset($faculty) ? route('faculties.update', $faculty) : route('faculties.store') }}" class="space-y-6 bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            @csrf
            @if(isset($faculty))
                @method('PATCH')
            @endif

            <!-- Name -->
            <div>
                <label class="block text-sm font-medium text-gray-900 mb-1">Full Name *</label>
                <input type="text" name="name" value="{{ isset($faculty) ? $faculty->user->name : old('name') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-lnu-blue @error('name') border-red-500 @enderror" required>
                @error('name')<span class="text-red-600 text-sm mt-1">{{ $message }}</span>@enderror
            </div>

            <!-- Email -->
            <div>
                <label class="block text-sm font-medium text-gray-900 mb-1">Email Address *</label>
                <input type="email" name="email" value="{{ isset($faculty) ? $faculty->user->email : old('email') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-lnu-blue @error('email') border-red-500 @enderror" required>
                @error('email')<span class="text-red-600 text-sm mt-1">{{ $message }}</span>@enderror
            </div>

            <!-- Employee ID -->
            @unless(isset($faculty))
            <div>
                <label class="block text-sm font-medium text-gray-900 mb-1">Employee ID *</label>
                <input type="text" name="employee_id" value="{{ old('employee_id') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-lnu-blue @error('employee_id') border-red-500 @enderror" required>
                @error('employee_id')<span class="text-red-600 text-sm mt-1">{{ $message }}</span>@enderror
            </div>
            @else
            <div>
                <label class="block text-sm font-medium text-gray-900 mb-1">Employee ID</label>
                <input type="text" value="{{ $faculty->employee_id }}" class="w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg text-gray-600" disabled>
            </div>
            @endunless

            <!-- Department & Position -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-900 mb-1">Department *</label>
                    <input type="text" name="department" value="{{ isset($faculty) ? $faculty->department : old('department') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-lnu-blue @error('department') border-red-500 @enderror" required>
                    @error('department')<span class="text-red-600 text-sm mt-1">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-900 mb-1">Position *</label>
                    <input type="text" name="position" value="{{ isset($faculty) ? $faculty->position : old('position') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-lnu-blue @error('position') border-red-500 @enderror" required>
                    @error('position')<span class="text-red-600 text-sm mt-1">{{ $message }}</span>@enderror
                </div>
            </div>

            <!-- Specialization -->
            <div>
                <label class="block text-sm font-medium text-gray-900 mb-1">Specialization</label>
                <input type="text" name="specialization" value="{{ isset($faculty) ? $faculty->specialization : old('specialization') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-lnu-blue">
            </div>

            <!-- Phone & Address -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-900 mb-1">Phone</label>
                    <input type="text" name="phone" value="{{ isset($faculty) ? $faculty->phone : old('phone') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-lnu-blue">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-900 mb-1">Address</label>
                    <input type="text" name="address" value="{{ isset($faculty) ? $faculty->address : old('address') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-lnu-blue">
                </div>
            </div>

            <!-- Notes -->
            <div>
                <label class="block text-sm font-medium text-gray-900 mb-1">Notes</label>
                <textarea name="notes" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-lnu-blue">{{ isset($faculty) ? $faculty->notes : old('notes') }}</textarea>
            </div>

            <!-- Buttons -->
            <div class="flex gap-3 pt-4 border-t border-gray-200">
                <button type="submit" class="px-6 py-2 bg-lnu-blue hover:bg-blue-700 text-white font-medium rounded-lg transition">
                    {{ isset($faculty) ? 'Update Faculty Member' : 'Create Faculty Member' }}
                </button>
                <a href="{{ isset($faculty) ? route('faculties.show', $faculty) : route('faculties.index') }}" class="px-6 py-2 bg-gray-200 hover:bg-gray-300 text-gray-900 font-medium rounded-lg transition">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</x-admin-layout>
