<?php

declare(strict_types=1);

namespace Troidcz\VerifyEmail\Exception;

interface VerifyEmailExceptionInterface extends \Throwable
{
    public function getReason(): string;
}
