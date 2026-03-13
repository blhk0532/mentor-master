<?php

declare(strict_types=1);

namespace Buildix\Timex\Events;

use Auth;
use Carbon\Carbon;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Resources\Pages\Concerns\UsesResourceForm;
use Illuminate\Database\Eloquent\Model;

trait InteractWithEvents
{
    use InteractsWithForms;
    use UsesResourceForm;

    public $record;

    protected static string $resource;

    protected static string $model;

    public static function getModel(): string
    {
        return static::$model = config('timex.models.event');
    }

    public static function getResource(): string
    {
        return static::$resource = config('timex.resources.event');
    }

    public function eventUpdated($data)
    {
        $event = self::getModel()::query()->find($data['id']);
        $eventData = $event->getAttributes();
        $end = Carbon::create($eventData['end']);
        $toDate = Carbon::createFromTimestamp($data['toDate']);

        if ($eventData['organizer'] === Auth::id() && (($toDate->isAfter(today()) || $toDate->isCurrentDay())) || config('timex.isPastCreationEnabled', false)) {
            $event->update([
                'start' => Carbon::createFromTimestamp($data['toDate']),
            ]);
            if ($end < $toDate) {
                $event->update([
                    'end' => $toDate,
                ]);
            }
        }
        $this->emit('modelUpdated', ['id' => $this->id]);
        $this->emit('updateWidget', ['id' => $this->id]);
    }

    protected function getFormModel(): Model|string|null
    {
        $record = self::getModel()::find($this->record);

        return $record;
    }
}
