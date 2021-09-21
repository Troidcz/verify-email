<?php

declare(strict_types=1);


namespace Troidcz\VerifyEmail\Util;

class VerifyEmailQueryUtility
{
    public function getTokenFromQuery(string $uri): string
    {
        $params = $this->getQueryParams($uri);

        return (string) $params['token'];
    }

    public function getExpiryTimestamp(string $uri): int
    {
        $params = $this->getQueryParams($uri);

        if (empty($params['expires'])) {
            return 0;
        }

        return (int) $params['expires'];
    }

    /**
     * @param string $uri
     * @return array<string, int|string>
     */
    private function getQueryParams(string $uri): array
    {
        $params = [];
        $urlComponents = parse_url($uri);
        if (!$urlComponents) {
            return [];
        }

        if (\array_key_exists('query', $urlComponents)) {
            parse_str(($urlComponents['query'] ?? ''), $params);
        }

        return $params;
    }
}
