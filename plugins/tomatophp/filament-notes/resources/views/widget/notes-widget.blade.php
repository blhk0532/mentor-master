@php
$notes = \TomatoPHP\FilamentNotes\Models\Note::query()
    ->where('is_pined', 1)
    ->where(function ($query) {
        $query->where('is_public', 1)
            ->orWhere('user_id', auth()->user()->id)
            ->orWhereHas('noteMetas', function ($q) {
                $q->where('key', config('filament-notes.models.user'))
                    ->where('value', (string) auth()->user()->id);
            });
    })
    ->orderBy('created_at', 'desc')
    ->limit(4)
    ->get();
@endphp
<x-filament-widgets::widget>
    <x-filament::section heading="{{ trans('filament-notes::messages.title') }}" icon="heroicon-o-bookmark">
        @if($notes->count() > 0)
            <div class="grid grid-cols-2 gap-4">
                @foreach($notes as $note)
                    @php
                        $bg = $note->background ?? '#fff';
                        $border = $note->border ?? '#ccc';
                        $color = $note->color ?? '#000';
                    @endphp
                    <div 
                        class="p-3 rounded shadow-md cursor-pointer hover:shadow-lg transition-shadow"
                        style="background-color: {{ $bg }}; border: 2px solid {{ $border }}; color: {{ $color }};"
                    >
                        @if($note->icon)
                            <x-filament::icon icon="{{ $note->icon }}" class="w-6 h-6 mb-2" />
                        @endif
                        @if($note->title)
                            <div class="font-bold mb-1">{{ $note->title }}</div>
                        @endif
                        <div class="text-sm opacity-90">
                            {!! $note->body !!}
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-gray-500">No pinned notes found.</div>
        @endif
    </x-filament::section>
</x-filament-widgets::widget>
