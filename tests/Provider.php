<?php

declare(strict_types=1);

namespace Tests;

use Workbunny\WebmanNacos\Provider\AbstractProvider;

final class Provider extends AbstractProvider
{
    public function verifyTester(array $options, array $validators): void
    {
        $this->verify($options, $validators);
    }

    public function filterTester(array $input): array
    {
        return $this->filter($input);
    }
}
