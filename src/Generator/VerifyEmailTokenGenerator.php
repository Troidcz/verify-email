<?php

declare(strict_types=1);

namespace Troidcz\VerifyEmail\Generator;

class VerifyEmailTokenGenerator implements VerifyEmailTokenGeneratorInterface
{
    private string $signingKey;

    public function __construct(
        string $signingKey
    ) {
        $this->signingKey = $signingKey;
    }

    public function createToken(string $userId, string $email): string
    {
        $encodedData = json_encode([$userId, $email]);
        if (!$encodedData) {
            throw new \RuntimeException();
        }

        return base64_encode(hash_hmac('sha256', $encodedData, $this->signingKey, true));
    }
}
