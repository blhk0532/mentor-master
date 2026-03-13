<?php

declare(strict_types=1);

use AdultDate\FilamentDialer\FilamentDialerPlugin;
use AdultDate\FilamentDialer\Livewire\PhoneDialerSidebar;
use Filament\Facades\Filament;
use Livewire\Livewire;

beforeEach(function () {
    Filament::setCurrentPanel('admin');
});

it('plugin can be instantiated', function () {
    $plugin = FilamentDialerPlugin::make();

    expect($plugin)->toBeInstanceOf(FilamentDialerPlugin::class);
});

it('plugin has correct ID', function () {
    $plugin = FilamentDialerPlugin::make();

    expect($plugin->getId())->toBe('filament-dialer');
});

it('plugin can be configured to show phone icon', function () {
    $plugin = FilamentDialerPlugin::make();

    expect($plugin->showPhoneIcon(false)->showPhoneIcon())->toBeFalse();
    expect($plugin->showPhoneIcon(true)->showPhoneIcon())->toBeTrue();
});

it('plugin can be configured to show sidebar', function () {
    $plugin = FilamentDialerPlugin::make();

    expect($plugin->showSidebar(false)->showSidebar())->toBeFalse();
    expect($plugin->showSidebar(true)->showSidebar())->toBeTrue();
});

it('phone dialer sidebar livewire component can be rendered', function () {
    Livewire::test(PhoneDialerSidebar::class)
        ->assertStatus(200)
        ->assertSee('Phone Dialer');
});

it('phone dialer can append digits', function () {
    Livewire::test(PhoneDialerSidebar::class)
        ->set('phoneNumber', '')
        ->call('append', '1')
        ->assertSet('phoneNumber', '1')
        ->assertSet('status', 'editing')
        ->call('append', '2')
        ->call('append', '3')
        ->assertSet('phoneNumber', '123');
});

it('phone dialer can backspace', function () {
    Livewire::test(PhoneDialerSidebar::class)
        ->set('phoneNumber', '123')
        ->call('backspace')
        ->assertSet('phoneNumber', '12');
});

it('phone dialer backspace resets status when empty', function () {
    Livewire::test(PhoneDialerSidebar::class)
        ->set('phoneNumber', '1')
        ->set('status', 'editing')
        ->call('backspace')
        ->assertSet('phoneNumber', '')
        ->assertSet('status', 'idle');
});

it('phone dialer can clear', function () {
    Livewire::test(PhoneDialerSidebar::class)
        ->set('phoneNumber', '123')
        ->set('status', 'calling')
        ->call('clear')
        ->assertSet('phoneNumber', '')
        ->assertSet('status', 'idle');
});

it('phone dialer can start call', function () {
    Livewire::test(PhoneDialerSidebar::class)
        ->set('phoneNumber', '1234567890')
        ->call('startCall')
        ->assertSet('status', 'calling')
        ->assertDispatched('phone-dialer.call', number: '1234567890');
});

it('phone dialer cannot start call with empty number', function () {
    Livewire::test(PhoneDialerSidebar::class)
        ->set('phoneNumber', '')
        ->call('startCall')
        ->assertSet('status', 'idle');
});

it('phone dialer can end call', function () {
    Livewire::test(PhoneDialerSidebar::class)
        ->set('status', 'calling')
        ->call('endCall')
        ->assertSet('status', 'hangup')
        ->assertDispatched('phone-dialer.hangup');
});

it('phone dialer can toggle mute', function () {
    Livewire::test(PhoneDialerSidebar::class)
        ->set('muted', false)
        ->call('toggleMute')
        ->assertSet('muted', true)
        ->call('toggleMute')
        ->assertSet('muted', false);
});

it('phone dialer sidebar can be accessed via panel', function () {
    $panel = Filament::getPanel('admin');

    expect($panel->getId())->toBe('admin');
});
