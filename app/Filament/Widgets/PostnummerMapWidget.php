<?php

declare(strict_types=1);

namespace App\Filament\Queue\Widgets;

use App\Models\RatsitKommun;
use App\Models\RatsitPostort;
use EduardoRibeiroDev\FilamentLeaflet\Support\Markers\Marker;
use EduardoRibeiroDev\FilamentLeaflet\Widgets\MapWidget;
use Filament\Support\Colors\Color;
use Livewire\Attributes\On;

class PostnummerMapWidget extends MapWidget
{
    protected static ?int $sort = 1;

    protected ?string $heading = 'Sweden Postnummer Map';

    protected array $mapCenter = [60.1282, 18.6435];

    protected int $defaultZoom = 8;

    protected int $mapHeight = 690;

    public ?string $selectedKommun = null;

    #[On('show-postorter')]
    public function handleShowPostorter(string $kommun): void
    {
        $this->selectedKommun = $kommun;
        $this->heading = "Postnummer in {$kommun}";
        $this->dispatch('refresh-map');
    }

    #[On('clear-selection')]
    public function handleClearSelection(): void
    {
        $this->selectedKommun = null;
        $this->heading = 'Sweden Kommuner Map';
    }

    protected function getMarkers(): array
    {
        if ($this->selectedKommun) {
            return $this->getPostorterMarkers();
        }

        return $this->getKommunerMarkers();
    }

    protected function getKommunerMarkers(): array
    {
        $kommuner = RatsitKommun::query()
            ->whereNotNull('lat')
            ->whereNotNull('lng')
            ->where('personer_count', '>', 0)
            ->get();

        $markers = [];
        foreach ($kommuner as $kommun) {
            $markers[] = Marker::make($kommun->lat, $kommun->lng)
                ->title($kommun->kommun)
                ->popupContent($kommun->kommun.': '.number_format($kommun->personer_count).' personer')
                ->color($this->getMarkerColor($kommun->personer_count));
        }

        return $markers;
    }

    protected function getPostorterMarkers(): array
    {
        $kommun = RatsitKommun::whereRaw('LOWER(kommun) = ?', [strtolower($this->selectedKommun)])->first();

        if (! $kommun) {
            return [];
        }

        $postorter = RatsitPostort::whereRaw('LOWER(kommun) = ?', [strtolower($this->selectedKommun)])
            ->orWhereRaw('LOWER(personer_kommun) = ?', [strtolower($this->selectedKommun)])
            ->selectRaw('post_nummer, post_ort, SUM(personer_count) as personer_count, SUM(foretag_count) as foretag_count')
            ->groupBy('post_nummer', 'post_ort')
            ->get();

        $markers = [];
        $index = 0;
        $total = $postorter->count();

        foreach ($postorter as $postort) {
            $latOffset = (sin($index * 2 * M_PI / max($total, 1)) * 0.05);
            $lngOffset = (cos($index * 2 * M_PI / max($total, 1)) * 0.05);

            $markers[] = Marker::make(
                (float) $kommun->lat + $latOffset,
                (float) $kommun->lng + $lngOffset
            )
                ->title($postort->post_nummer.' - '.$postort->post_ort)
                ->popupContent($postort->post_nummer.' '.$postort->post_ort.'<br>Personer: '.number_format($postort->personer_count).'<br>Företag: '.number_format($postort->foretag_count))
                ->color(Color::Blue);
            $index++;
        }

        return $markers;
    }

    protected function getTotalKommuner(): int
    {
        return RatsitKommun::query()
            ->whereNotNull('lat')
            ->whereNotNull('lng')
            ->count();
    }

    protected function getTotalPersoner(): int
    {
        return (int) RatsitKommun::query()
            ->whereNotNull('lat')
            ->whereNotNull('lng')
            ->sum('personer_count');
    }

    protected function getMarkerColor(int $personerCount): array
    {
        if ($personerCount > 100000) {
            return Color::Red;
        }
        if ($personerCount > 50000) {
            return Color::Orange;
        }
        if ($personerCount > 20000) {
            return Color::Yellow;
        }

        return Color::Blue;
    }
}
