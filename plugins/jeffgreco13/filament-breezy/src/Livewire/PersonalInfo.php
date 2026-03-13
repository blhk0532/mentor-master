<?php

declare(strict_types=1);

namespace Jeffgreco13\FilamentBreezy\Livewire;

use Filament\Facades\Filament;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Schema;

class PersonalInfo extends MyProfileComponent
{
    public ?array $data = [];

    public $user;

    public $userClass;

    public bool $hasAvatars;

    public array $only = ['name', 'email'];

    public static $sort = 10;

    protected string $view = 'filament-breezy::livewire.personal-info';

    public function mount(): void
    {
        $this->user = Filament::getCurrentOrDefaultPanel()->auth()->user();
        $this->userClass = get_class($this->user);
        $this->hasAvatars = filament('filament-breezy')->hasAvatars();

        if ($this->hasAvatars) {
            $this->only[] = filament('filament-breezy')->getAvatarUploadComponent()->getStatePath(false);
        }

        $this->form->fill($this->user->only($this->only));
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components($this->getProfileFormSchema())
            ->columns(3)
            ->statePath('data');
    }

    public function submit(): void
    {
        $data = collect($this->form->getState())->only($this->only)->all();
        $this->user->update($data);
        $this->sendNotification();
    }

    protected function getProfileFormSchema(): array
    {
        $groupFields = Group::make($this->getProfileFormComponents())
            ->columnSpan($this->hasAvatars ? 2 : 3);

        return ($this->hasAvatars)
            ? [filament('filament-breezy')->getAvatarUploadComponent(), $groupFields]
            : [$groupFields];
    }

    protected function getProfileFormComponents(): array
    {
        return [
            $this->getNameComponent(),
            $this->getEmailComponent(),
        ];
    }

    protected function getNameComponent(): TextInput
    {
        return TextInput::make('name')
            ->required()
            ->label(__('filament-breezy::default.fields.name'));
    }

    protected function getEmailComponent(): TextInput
    {
        return TextInput::make('email')
            ->required()
            ->email()
            ->unique($this->userClass, ignorable: $this->user)
            ->label(__('filament-breezy::default.fields.email'));
    }

    protected function sendNotification(): void
    {
        Notification::make()
            ->success()
            ->title(__('filament-breezy::default.profile.personal_info.notify'))
            ->send();
    }
}
