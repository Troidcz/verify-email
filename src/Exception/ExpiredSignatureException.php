<?php

declare(strict_types=1);

namespace Troidcz\VerifyEmail\Exception;

final class ExpiredSignatureException extends \Exception implements VerifyEmailExceptionInterface
{
    public function getReason(): string
    {
        return 'The link to verify your email has expired. Please request a new link.';
    }
}
