<?php

declare(strict_types=1);


namespace Troidcz\Tests\VerifyEmail;

use Nette\Configurator;
use Nette\DI\Container;
use Troidcz\VerifyEmail\DI\VerifyEmailExtension;

final class Helpers
{
    /**
     * @param array<mixed> $config
     */
    public static function createContainerFromConfigurator(
        string $tempDir,
        array $config = []
    ): Container {
        $config = array_merge_recursive($config, [
            'extensions' => [
                'verifyEmail' => VerifyEmailExtension::class,
            ],
        ]);

        $configurator = new Configurator();

        $configurator->setTempDirectory($tempDir)
            ->addConfig($config);

        return $configurator->createContainer();
    }
}
