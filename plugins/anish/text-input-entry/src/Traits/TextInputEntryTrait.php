<?php

namespace Anish\TextInputEntry\Traits;

use Anish\TextInputEntry\Infolists\Components\TextInputEntry;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Validator;

trait TextInputEntryTrait
{
    public function updateEditableEntry(string $name, mixed $value, int $recordId): void
    {
        $record = $this->record;

        if ($record->getKey() !== $recordId) {
            return;
        }

        $infolist = $this->infolist;
        $entry = $infolist->getComponent($name);

        if ($entry instanceof TextInputEntry) {
            if (! $entry->isEditable()) {
                Notification::make()
                    ->title('Permission denied')
                    ->body('You do not have permission to update this field.')
                    ->danger()
                    ->duration(2000)
                    ->send();

                return;
            }

            $rules = $entry->getRules();
            if (! empty($rules)) {
                $validationRules = is_string($rules) ? $rules : $rules;

                $validator = Validator::make(
                    [$name => $value],
                    [$name => $validationRules]
                );

                if ($validator->fails()) {
                    Notification::make()
                        ->title('Validation failed')
                        ->body($validator->errors()->first($name))
                        ->danger()
                        ->duration(2000)
                        ->send();

                    return;
                }
            }

            // Show success notification immediately (optimistically)
            Notification::make()
                ->title('Updated successfully')
                ->success()
                ->duration(1500) // Hide after 1.5 seconds
                ->send();

            // Perform the update after showing notification
            $callback = $entry->getUpdateStateUsing();

            if ($callback) {
                $callback($value, $record);
            } else {
                $record->{$name} = $value;
                $record->save();
            }
        }
    }
}
