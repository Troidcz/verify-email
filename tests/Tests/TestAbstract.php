<?php

declare(strict_types=1);


namespace Troidcz\Tests\VerifyEmail;

use Nette\DI\Container;
use Tester\TestCase;

class TestAbstract extends TestCase
{
    public function __construct(
        protected Container $container
    ) {
    }
}
