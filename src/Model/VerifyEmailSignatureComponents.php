<?php

declare(strict_types=1);


namespace Troidcz\VerifyEmail\Model;

class VerifyEmailSignatureComponents
{
    private \DateTimeInterface $expiresAt;
    private string $uri;
    private int $generatedAt;

    public function __construct(
        \DateTimeInterface $expiresAt,
        string $uri,
        int $generatedAt
    ) {
        $this->expiresAt = $expiresAt;
        $this->uri = $uri;
        $this->generatedAt = $generatedAt;
    }

    public function getSignedUrl(): string
    {
        return $this->uri;
    }

    public function getExpiresAt(): \DateTimeInterface
    {
        return $this->expiresAt;
    }

    public function getExpiresAtIntervalInstance(): \DateInterval
    {
        $createdAtTime = \DateTimeImmutable::createFromFormat('U', (string) $this->generatedAt);
        if (!$createdAtTime) {
            throw new \RuntimeException();
        }

        return $this->expiresAt->diff($createdAtTime);
    }
}
