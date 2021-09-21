<?php

declare(strict_types=1);


namespace Troidcz\Tests\VerifyEmail;

use Nette\DI\Container;
use Tester\TestCase;

class TestAbstract extends TestCase
{
    protected Container $container;

    public function __construct(
        Container $container
    ) {
        $this->container = $container;
    }
}
