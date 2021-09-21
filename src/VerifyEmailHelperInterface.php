<?php

declare(strict_types=1);


namespace Troidcz\VerifyEmail;

use Nette\Http\Request;
use Troidcz\VerifyEmail\Model\VerifyEmailSignatureComponents;

interface VerifyEmailHelperInterface
{
    /**
     * @param string $routeName
     * @param string $userId
     * @param string $userEmail
     * @param array<string,string|int|bool> $extraParams
     * @return VerifyEmailSignatureComponents
     */
    public function generateSignature(string $routeName, string $userId, string $userEmail, array $extraParams = []): VerifyEmailSignatureComponents;

    public function validateRequestEmailConfirmation(Request $request, string $userId, string $userEmail): void;

    public function validateEmailConfirmation(string $signedUrl, string $userId, string $userEmail): void;
}
