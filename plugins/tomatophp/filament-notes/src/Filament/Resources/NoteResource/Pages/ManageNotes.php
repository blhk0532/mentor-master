<?php

namespace TomatoPHP\FilamentNotes\Filament\Resources\NoteResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Livewire\Attributes\On;
use TomatoPHP\FilamentNotes\Filament\Forms\NoteForm;
use TomatoPHP\FilamentNotes\Filament\Pages\NotesGroups;
use TomatoPHP\FilamentNotes\Filament\Pages\NotesStatus;
use TomatoPHP\FilamentNotes\Filament\Resources\NoteResource;

class ManageNotes extends ManageRecords
{
    protected static string $resource = NoteResource::class;

    #[On('note_deleted')]
    public function noteDeleted(): void
    {
        $this->resetTable();
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->form(NoteForm::make())
                ->mutateFormDataUsing(function (array $data) {
                    $data['user_id'] = auth()->user()->id;
                    $data['user_type'] = config('filament-notes.models.user');

                    return $data;
                }),
            Actions\Action::make('status')
                ->hidden(! filament('filament-notes')->useStatus)
                ->hiddenLabel()
                ->color('warning')
                ->tooltip(trans('filament-notes::messages.pages.status'))
                ->url(fn () => NotesStatus::getUrl())
                ->icon('heroicon-o-check-badge'),
            Actions\Action::make('groups')
                ->hidden(! filament('filament-notes')->useGroups)
                ->hiddenLabel()
                ->color('success')
                ->tooltip(trans('filament-notes::messages.pages.groups'))
                ->url(fn () => NotesGroups::getUrl())
                ->icon('heroicon-o-rectangle-group'),
        ];
    }
}
