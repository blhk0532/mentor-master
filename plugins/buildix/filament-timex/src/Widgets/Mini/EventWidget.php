<?php

declare(strict_types=1);

namespace Buildix\Timex\Widgets\Mini;

use Buildix\Timex\Events\InteractWithEvents;
use Buildix\Timex\Traits\TimexTrait;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Livewire\Component;

class EventWidget extends Component
{
    use InteractWithEvents;
    use TimexTrait;

    public $events;

    public $now;

    protected $listeners = [
        'updateWidget' => 'updateWidget',
    ];

    public static function getEvents(): Collection
    {
        $events = self::getPageClass()::getEvents();

        return collect($events)->filter(function ($event) {
            return isset($event->startTime) && ! config('timex.resources.isStartEndHidden', false) ? $event->start === today()->timestamp && Carbon::createFromTimeString($event->startTime) >= now() : $event->start === today()->timestamp
                || $event->start === today()->timestamp && $event->isAllDay;
        });

    }

    public function updateWidget()
    {
        $this->reset('events');
        $this->events = self::getEvents();
    }

    public function boot()
    {
        $this->now = Carbon::today()->timestamp;
    }

    public function mount()
    {
        $this->events = self::getEvents();
    }

    public function render()
    {
        if (count($this->events) === 0) {
            return view('timex::widgets.mini.event-widget-empty');
        }

        return view('timex::widgets.mini.event-widget');

    }
}
