@props(['label' => '', 'value' => '', 'trend' => 0, 'icon' => 'info', 'iconBg' => 'bg-blue-100', 'iconColor' => 'text-lnu-blue'])

<div class="card hover-scale">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-gray-600 text-sm font-medium">{{ $label }}</p>
            <p class="text-3xl font-bold text-lnu-blue mt-2">{{ $value }}</p>
            @if($trend !== 0)
                <p @class(["text-xs mt-2", $trend > 0 ? 'text-green-600' : 'text-red-600'])>
                    @if($trend > 0)
                        ↑ {{ $trend }}% this period
                    @else
                        ↓ {{ abs($trend) }}% this period
                    @endif
                </p>
            @endif
        </div>
        <div class="w-12 h-12 {{ $iconBg }} rounded-lg flex items-center justify-center">
            @if($icon === 'programs')
                <svg class="w-6 h-6 {{ $iconColor }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            @elseif($icon === 'beneficiaries')
                <svg class="w-6 h-6 {{ $iconColor }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-2a6 6 0 0112 0v2zm0 0h6v-2a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
            @elseif($icon === 'communities')
                <svg class="w-6 h-6 {{ $iconColor }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.856-1.611M15 7a3 3 0 11-6 0 3 3 0 016 0zM16 16a5 5 0 01-10 0" />
                </svg>
            @elseif($icon === 'budget')
                <svg class="w-6 h-6 {{ $iconColor }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            @else
                <svg class="w-6 h-6 {{ $iconColor }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            @endif
        </div>
    </div>
</div>
