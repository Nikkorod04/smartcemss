@if($items && is_array($items) && count($items) > 0)
    @foreach($items as $item)
        <span class="inline-flex items-center gap-1 px-3 py-1 bg-blue-100 text-blue-800 text-sm font-medium rounded-full border border-blue-200">
            {{ $item }}
        </span>
    @endforeach
@else
    <span class="text-gray-500">-</span>
@endif
