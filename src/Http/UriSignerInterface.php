<?php

declare(strict_types=1);


namespace Troidcz\VerifyEmail\Http;

use Nette\Http\IRequest;

interface UriSignerInterface
{
    public function sign(string $uri): string;

    public function check(string $uri): bool;

    public function checkRequest(IRequest $request): bool;
}
