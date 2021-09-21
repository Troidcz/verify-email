<?php

declare(strict_types=1);

namespace Troidcz\VerifyEmail\Exception;

final class WrongEmailVerifyException extends \Exception implements VerifyEmailExceptionInterface
{
    public function getReason(): string
    {
        return 'The link to verify your email appears to be for a different account or email. Please request a new link.';
    }
}
