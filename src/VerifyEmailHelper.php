<?php

declare(strict_types=1);


namespace Troidcz\VerifyEmail;

use Nette\Application\LinkGenerator;
use Troidcz\VerifyEmail\Exception\ExpiredSignatureException;
use Troidcz\VerifyEmail\Exception\InvalidSignatureException;
use Troidcz\VerifyEmail\Exception\WrongEmailVerifyException;
use Troidcz\VerifyEmail\Generator\VerifyEmailTokenGeneratorInterface;
use Troidcz\VerifyEmail\Http\UriSignerInterface;
use Troidcz\VerifyEmail\Model\VerifyEmailSignatureComponents;
use Troidcz\VerifyEmail\Util\VerifyEmailQueryUtility;

class VerifyEmailHelper implements VerifyEmailHelperInterface
{
    private LinkGenerator $linkGenerator;
    private UriSignerInterface $uriSigner;
    private VerifyEmailQueryUtility $queryUtility;
    private VerifyEmailTokenGeneratorInterface $tokenGenerator;
    private int $lifetime;

    public function __construct(
        LinkGenerator $linkGenerator,
        UriSignerInterface $uriSigner,
        VerifyEmailQueryUtility $queryUtility,
        VerifyEmailTokenGeneratorInterface $tokenGenerator,
        int $lifetime
    ) {
        $this->linkGenerator = $linkGenerator;
        $this->uriSigner = $uriSigner;
        $this->queryUtility = $queryUtility;
        $this->tokenGenerator = $tokenGenerator;
        $this->lifetime = $lifetime;
    }

    /**
     * {@inheritdoc}
     */
    public function generateSignature(string $routeName, string $userId, string $userEmail, array $extraParams = []): VerifyEmailSignatureComponents
    {
        $generatedAt = time();
        $expiryTimestamp = $generatedAt + $this->lifetime;

        $extraParams['token'] = $this->tokenGenerator->createToken($userId, $userEmail);
        $extraParams['expires'] = $expiryTimestamp;

        // todo check for absolute path!
        $uri = $this->linkGenerator->link($routeName, $extraParams);

        $signature = $this->uriSigner->sign($uri);
        $expireAt = \DateTimeImmutable::createFromFormat('U', (string) $expiryTimestamp);
        if (!$expireAt) {
            throw new \RuntimeException();
        }

        return new VerifyEmailSignatureComponents($expireAt, $signature, $generatedAt);
    }

    /**
     * {@inheritdoc}
     */
    public function validateEmailConfirmation(string $signedUrl, string $userId, string $userEmail): void
    {
        if (!$this->uriSigner->check($signedUrl)) {
            throw new InvalidSignatureException();
        }

        if ($this->queryUtility->getExpiryTimestamp($signedUrl) <= time()) {
            throw new ExpiredSignatureException();
        }

        $knownToken = $this->tokenGenerator->createToken($userId, $userEmail);
        $userToken = $this->queryUtility->getTokenFromQuery($signedUrl);

        if (!hash_equals($knownToken, $userToken)) {
            throw new WrongEmailVerifyException();
        }
    }
}
