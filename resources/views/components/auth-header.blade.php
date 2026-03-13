<div class="text-center">
    @if (isset($title))
        <h2 class="text-xl font-semibold">{{ $title }}</h2>
    @endif
    @if (isset($description))
        <p class="text-sm text-gray-600 mt-1">{{ $description }}</p>
    @endif
</div>
