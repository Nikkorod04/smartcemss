@if($value && $value !== '-')
    <span class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-800 text-sm font-medium rounded-full border border-blue-200">
        {{ $value }}
    </span>
@else
    <span class="text-gray-500">-</span>
@endif
