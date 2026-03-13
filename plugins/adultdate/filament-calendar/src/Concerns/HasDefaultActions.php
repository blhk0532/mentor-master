<?php

declare(strict_types=1);

namespace Adultdate\FilamentBooking\Concerns;

use Adultdate\FilamentBooking\Filament\Actions\CreateAction;
use Adultdate\FilamentBooking\Filament\Actions\DeleteAction;
use Adultdate\FilamentBooking\Filament\Actions\EditAction;
use Adultdate\FilamentBooking\Filament\Actions\ViewAction;
use Filament\Actions\Action;
use Illuminate\Support\Str;

trait HasDefaultActions
{
    public function viewAction(): ViewAction
    {
        return ViewAction::make();
    }

    public function editAction(): EditAction
    {
        return EditAction::make();
    }

    public function deleteAction(): DeleteAction
    {
        return DeleteAction::make();
    }

    /**
     * Returns a create action configured for the specified model.
     *
     * @param  string  $model  The model class for which you want to make a create action.
     */
    protected function createAction(string $model, ?string $name = null): CreateAction
    {
        if (! $name) {
            $name = Str::of('create')->append(Str::pascal(class_basename($model)))->toString();
        }

        return CreateAction::make($name)
            ->model($model);
    }
}
