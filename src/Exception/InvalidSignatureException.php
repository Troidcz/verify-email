<?php

declare(strict_types=1);

namespace Troidcz\VerifyEmail\Exception;

final class InvalidSignatureException extends \Exception implements VerifyEmailExceptionInterface
{
    public function getReason(): string
    {
        return 'The link to verify your email is invalid. Please request a new link.';
    }
}
