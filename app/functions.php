<?php

/**
 * Here is your custom functions.
 */

use Composer\InstalledVersions;

if (!\function_exists('event_loop')) {
    function event_loop(): string
    {
        if (\extension_loaded('swow')) {
            return Workerman\Events\Swow::class;
        }
        if (\extension_loaded('swoole')) {
            return Workerman\Events\Swoole::class;
        }
        if (InstalledVersions::isInstalled('cloudtay/ripple-driver') && \PHP_VERSION_ID >= 80100) {
            return Ripple\Driver\Workerman\Driver5::class;
        }
        if (InstalledVersions::isInstalled('revolt/event-loop') && \PHP_VERSION_ID >= 80100) {
            return Workerman\Events\Fiber::class;
        }

        return '';
    }
}
