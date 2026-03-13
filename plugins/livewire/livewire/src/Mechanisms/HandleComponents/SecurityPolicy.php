<?php

declare(strict_types=1);

namespace Livewire\Mechanisms\HandleComponents;

use Exception;

class SecurityPolicy
{
    /**
     * Classes that should never be instantiated by Livewire synthesizers.
     * These are known-dangerous classes that could be exploited if an attacker
     * somehow bypassed the checksum protection.
     */
    private static $deniedClasses = [
        // Console commands - could execute arbitrary system commands
        'Illuminate\Console\Command',
        'Symfony\Component\Console\Command\Command',

        // Process execution - direct system command execution
        'Symfony\Component\Process\Process',

        // Known serialization gadgets
        'Illuminate\Broadcasting\PendingBroadcast',
        'Illuminate\Foundation\Testing\PendingCommand',

        // Queue jobs - could execute arbitrary code
        'Illuminate\Queue\CallQueuedClosure',

        // Notifications - could send arbitrary notifications
        'Illuminate\Notifications\Notification',
    ];

    /**
     * Validate that a class is safe to instantiate.
     * Throws an exception if the class is in the denylist.
     */
    public static function validateClass(string $class): void
    {
        foreach (self::$deniedClasses as $denied) {
            if (is_a($class, $denied, true)) {
                throw new Exception("Livewire: Class [{$class}] is not allowed to be instantiated.");
            }
        }
    }

    /**
     * Add classes to the denylist at runtime.
     */
    public static function denyClasses(array $classes): void
    {
        self::$deniedClasses = array_merge(self::$deniedClasses, $classes);
    }

    /**
     * Get the current denylist (useful for testing).
     */
    public static function getDeniedClasses(): array
    {
        return self::$deniedClasses;
    }
}
