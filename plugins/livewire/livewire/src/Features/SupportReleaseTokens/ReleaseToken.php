<?php

declare(strict_types=1);

namespace Livewire\Features\SupportReleaseTokens;

use Livewire\Exceptions\LivewireReleaseTokenMismatchException;

class ReleaseToken
{
    // This token is stored client-side and sent along with each request to check
    // a users session to see if a new release has invalidated it. If there is
    // a mismatch it will throw an error and prompt for a browser refresh.
    public static $LIVEWIRE_RELEASE_TOKEN = 'a';

    public static function verify($snapshot): void
    {
        $componentClass = app('livewire.factory')->resolveComponentClass($snapshot['memo']['name']);

        if (! isset($snapshot['memo']['release']) || $snapshot['memo']['release'] !== self::generate($componentClass)) {
            throw new LivewireReleaseTokenMismatchException;
        }
    }

    public static function generate($componentOrComponentClass): string
    {
        $livewireReleaseToken = self::$LIVEWIRE_RELEASE_TOKEN;
        $appReleaseToken = app('config')->get('livewire.release_token', '');
        $componentReleaseToken = method_exists($componentOrComponentClass, 'releaseToken') ? $componentOrComponentClass::releaseToken() : '';

        return $livewireReleaseToken.'-'.$appReleaseToken.'-'.$componentReleaseToken;
    }
}
