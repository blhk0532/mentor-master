<?php

declare(strict_types=1);

namespace AdultDate\FilamentDialer\Livewire;

use Filament\Facades\Filament;
use Livewire\Component;

class PhoneDialerSidebar extends Component
{
    public string $phoneNumber = '';

    public string $status = 'idle';

    public bool $muted = false;

    public function panelId(): ?string
    {
        return Filament::getCurrentPanel()?->getId();
    }

    public function append(string $digit): void
    {
        $this->phoneNumber .= $digit;
        $this->status = 'editing';
    }

    public function backspace(): void
    {
        $this->phoneNumber = mb_substr($this->phoneNumber, 0, -1);
        if (empty($this->phoneNumber)) {
            $this->status = 'idle';
        }
    }

    public function clear(): void
    {
        $this->phoneNumber = '';
        $this->status = 'idle';
    }

    public function startCall(): void
    {
        if (empty($this->phoneNumber)) {
            return;
        }
        $this->status = 'calling';
        $this->dispatch('phone-dialer.call', number: $this->phoneNumber);
    }

    public function endCall(): void
    {
        $this->status = 'hangup';
        $this->dispatch('phone-dialer.hangup');
    }

    public function toggleMute(): void
    {
        $this->muted = ! $this->muted;
    }

    public function render()
    {
        return view('filament-dialer::livewire.phone-dialer-sidebar');
    }
}
