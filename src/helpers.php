<?php
declare(strict_types=1);

namespace Workbunny\WebmanNacos;

use Workerman\Worker;

function reload(string $file)
{
    Worker::safeEcho($file . ' update and reload. ');
    Worker::reloadAllWorkers();
}