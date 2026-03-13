<?php

declare(strict_types=1);

new class extends Livewire\Component
{
    public int $count = 1;

    public function increment()
    {
        $this->count++;
    }
};
