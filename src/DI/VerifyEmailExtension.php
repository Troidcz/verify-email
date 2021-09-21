<?php

declare(strict_types=1);


namespace Troidcz\VerifyEmail\DI;

use Nette\DI\CompilerExtension;
use Nette\Schema\Expect;
use Nette\Schema\Schema;
use Troidcz\VerifyEmail\Generator\VerifyEmailTokenGenerator;
use Troidcz\VerifyEmail\Http\UriSigner;
use Troidcz\VerifyEmail\Util\VerifyEmailQueryUtility;
use Troidcz\VerifyEmail\VerifyEmailHelper;

class VerifyEmailExtension extends CompilerExtension
{
    public function getConfigSchema(): Schema
    {
        $builder = $this->getContainerBuilder();

        return Expect::structure([
            'debug' => Expect::bool($builder->parameters['debugMode']),
            'lifetime' => Expect::int(),
            'secretKey' => Expect::string()->required(),
        ]);
    }

    public function loadConfiguration(): void
    {
        $builder = $this->getContainerBuilder();

        $builder->addDefinition($this->prefix('verifyEmailTokenGenerator'))
            ->setFactory(VerifyEmailTokenGenerator::class)
            ->setArguments([$this->config->secretKey]);

        $builder->addDefinition($this->prefix('verifyEmailQueryUtility'))
            ->setFactory(VerifyEmailQueryUtility::class);


        $builder->addDefinition($this->prefix('uriSigner'))
            ->setFactory(UriSigner::class)
            ->setArguments([$this->config->secretKey, 'signature']);

        $builder->addDefinition($this->prefix('verifyEmailHelper'))
            ->setFactory(VerifyEmailHelper::class)
            ->setArgument('lifetime', $this->config->lifetime);
    }
}
