<?php
declare(strict_types=1);

namespace Workbunny\WebmanNacos\Traits;

use Psr\Log\LoggerInterface;
use support\Log;

trait Logger
{
    protected ?string $logChannel = null;

    /**
     * @return LoggerInterface
     */
    public function logger(): LoggerInterface
    {
        if ($this->logChannel === null) {
            $this->logChannel = config('plugin.workbunny.webman-nacos.app.log_channel', 'default');
        }

        return Log::channel($this->logChannel);
    }
}