<?php

namespace Workbunny\WebmanNacos\Traits;

use Psr\Log\LoggerInterface;
use support\Log;

trait Logger
{
    private ?string $logChannel = null;

    protected function logger(): LoggerInterface
    {
        if ($this->logChannel === null) {
            $this->logChannel = config('plugin.workbunny.webman-nacos.app.log_channel', 'default');
        }

        return Log::channel($this->logChannel);
    }
}