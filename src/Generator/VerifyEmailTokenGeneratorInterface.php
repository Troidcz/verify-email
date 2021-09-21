<?php

declare(strict_types=1);


namespace Troidcz\VerifyEmail\Generator;

interface VerifyEmailTokenGeneratorInterface
{
    public function createToken(string $userId, string $email): string;
}
