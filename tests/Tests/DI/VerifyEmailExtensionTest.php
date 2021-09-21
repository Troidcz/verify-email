<?php

declare(strict_types=1);


namespace Troidcz\Tests\VerifyEmail\DI;

use Tester\Assert;
use Troidcz\Tests\VerifyEmail\Helpers;
use Troidcz\Tests\VerifyEmail\TestAbstract;
use Troidcz\VerifyEmail\Http\UriSignerInterface;

$container = require __DIR__ . '/../../bootstrap.php';


class VerifyEmailExtensionTest extends TestAbstract
{
    public function test01(): void
    {
        $container = Helpers::createContainerFromConfigurator($this->container->getParameters()['tempDir'], [
            'verifyEmail' => [
                'lifetime' => 3600,
                'secretKey' => 'asdasd',
            ],
        ]);

        $container->getByType(UriSignerInterface::class, true);
        Assert::true(true);
    }
}

(new VerifyEmailExtensionTest($container))->run();
