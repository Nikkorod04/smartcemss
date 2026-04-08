@props(['class' => '', 'title' => '', 'footer' => false])

<div {{ $attributes->merge(['class' => "bg-white rounded-lg shadow-md hover:shadow-lg transition duration-300 p-6 " . $class]) }}>
    @if($title)
        <div class="mb-6 pb-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">{{ $title }}</h3>
        </div>
    @endif

    <div>
        {{ $slot }}
    </div>

    @if($footer)
        <div class="mt-6 pt-4 border-t border-gray-200">
            {{ $footer }}
        </div>
    @endif
</div>
