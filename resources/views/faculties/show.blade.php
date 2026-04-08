<x-admin-layout header="Faculty Member Details">
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <img src="https://ui-avatars.com/api/?name={{ urlencode($faculty->user->name) }}&background=003599&color=fff&size=64" alt="{{ $faculty->user->name }}" class="w-16 h-16 rounded-full border-2 border-lnu-blue">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $faculty->user->name }}</h1>
                    <p class="text-gray-600">{{ $faculty->position }} | {{ $faculty->department }}</p>
                </div>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('faculties.edit', $faculty) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-lnu-blue hover:bg-blue-700 text-white font-medium rounded-lg transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit
                </a>
                <form method="POST" action="{{ route('faculties.destroy', $faculty) }}" onsubmit="return confirm('Are you sure? This will delete the faculty member account.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Delete
                    </button>
                </form>
            </div>
        </div>

        @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-start gap-3">
            <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
            <div>{{ session('success') }}</div>
        </div>
        @endif

        @if(session('tokenDisplay') && session('token'))
        <div class="bg-blue-50 border-2 border-blue-200 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-blue-900 mb-2">New Access Token Generated</h3>
            <p class="text-blue-700 text-sm mb-4">Save this token securely. It will not be shown again. Share it with the faculty member via a secure channel.</p>
            <div class="bg-white border border-blue-300 rounded-lg p-3 font-mono text-sm break-all mb-3 select-all">{{ session('token') }}</div>
            <p class="text-xs text-blue-600">The token has been created and expires on: <strong>{{ session('token_expires', 'the date set during generation') }}</strong></p>
        </div>
        @endif

        <!-- Faculty Information -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Basic Info -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Basic Information</h2>
                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-600">Email</p>
                        <p class="text-gray-900 font-medium">{{ $faculty->user->email }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Employee ID</p>
                        <p class="text-gray-900 font-medium">{{ $faculty->employee_id }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Specialization</p>
                        <p class="text-gray-900">{{ $faculty->specialization ?? 'Not specified' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Phone</p>
                        <p class="text-gray-900">{{ $faculty->phone ?? 'Not provided' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Address</p>
                        <p class="text-gray-900">{{ $faculty->address ?? 'Not provided' }}</p>
                    </div>
                </div>
            </div>

            <!-- Statistics -->
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200 rounded-lg p-4">
                    <p class="text-xs text-blue-600 uppercase font-semibold mb-1">Extension Programs</p>
                    <p class="text-2xl font-bold text-blue-900">{{ $faculty->extensionPrograms->count() }}</p>
                </div>
                <div class="bg-gradient-to-br from-green-50 to-green-100 border border-green-200 rounded-lg p-4">
                    <p class="text-xs text-green-600 uppercase font-semibold mb-1">Active Tokens</p>
                    <p class="text-2xl font-bold text-green-900">{{ $activeTokens->count() }}</p>
                </div>
                <div class="bg-gradient-to-br from-orange-50 to-orange-100 border border-orange-200 rounded-lg p-4">
                    <p class="text-xs text-orange-600 uppercase font-semibold mb-1">Total Tokens</p>
                    <p class="text-2xl font-bold text-orange-900">{{ $faculty->tokens->count() }}</p>
                </div>
                <div class="bg-gradient-to-br from-purple-50 to-purple-100 border border-purple-200 rounded-lg p-4">
                    <p class="text-xs text-purple-600 uppercase font-semibold mb-1">Activities</p>
                    <p class="text-2xl font-bold text-purple-900">{{ $faculty->activities->count() }}</p>
                </div>
            </div>
        </div>

        <!-- Token Management - Livewire Component -->
        <livewire:generate-token-modal :faculty="$faculty" />

        <!-- Access Tokens Table -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Active Tokens</h2>
            @if($faculty->tokens->count())
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-4 py-2 text-left font-semibold text-gray-900">Token</th>
                            <th class="px-4 py-2 text-left font-semibold text-gray-900">Status</th>
                            <th class="px-4 py-2 text-left font-semibold text-gray-900">Generated</th>
                            <th class="px-4 py-2 text-left font-semibold text-gray-900">Expires</th>
                            <th class="px-4 py-2 text-right font-semibold text-gray-900">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($faculty->tokens as $token)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2 font-mono text-xs">{{ substr($token->token, 0, 16) }}...{{ substr($token->token, -8) }}</td>
                            <td class="px-4 py-2">
                                @if($token->isValid())
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">Active</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">Expired</span>
                                @endif
                            </td>
                            <td class="px-4 py-2 text-gray-700">{{ $token->created_at->format('M d, Y') }}</td>
                            <td class="px-4 py-2 text-gray-700">
                                @if($token->expires_at)
                                    <div>
                                        <p class="font-medium">{{ $token->expires_at->format('M d, Y') }}</p>
                                        <p class="text-xs text-gray-500">{{ $token->getExpirationStatus() }}</p>
                                    </div>
                                @else
                                    <span class="text-gray-500">Never</span>
                                @endif
                            </td>
                            <td class="px-4 py-2 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button type="button" onclick="navigator.clipboard.writeText('{{ $token->token }}'); this.textContent='Copied!'; setTimeout(() => this.textContent='Copy', 2000);" class="text-lnu-blue hover:text-blue-700 font-medium transition" title="Copy token">
                                        Copy
                                    </button>
                                    <livewire:revoke-token-modal :token="$token" :faculty="$faculty" :key="'revoke-' . $token->id" />
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <p class="text-gray-600 py-4">No access tokens generated yet.</p>
            @endif
        </div>

        <!-- Activity Involvement -->
        @if($faculty->activities->count())
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Activities Involved</h2>
            <div class="space-y-2">
                @foreach($faculty->activities as $activity)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                    <div>
                        <p class="font-medium text-gray-900">{{ $activity->title }}</p>
                        <p class="text-sm text-gray-600">{{ $activity->extension_program_id ? 'Program ' . $activity->extensionProgram->title : 'No program' }}</p>
                    </div>
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $activity->status === 'completed' ? 'bg-green-100 text-green-800' : ($activity->status === 'ongoing' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">
                        {{ ucfirst($activity->status) }}
                    </span>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

</x-admin-layout>
