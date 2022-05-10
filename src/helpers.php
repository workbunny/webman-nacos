<?php
declare(strict_types=1);

namespace Workbunny\WebmanNacos;

use Workerman\Worker;

function reload()
{
    Worker::reloadAllWorkers();
}