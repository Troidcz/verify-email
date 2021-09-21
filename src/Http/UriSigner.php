<?php

declare(strict_types=1);


namespace Troidcz\VerifyEmail\Http;

use Nette\Http\Request;

class UriSigner implements UriSignerInterface
{
    private string $secret;
    private string $parameter;

    public function __construct(
        string $secret,
        string $parameter = '_hash'
    ) {
        $this->secret = $secret;
        $this->parameter = $parameter;
    }

    public function sign(string $uri): string
    {
        $url = parse_url($uri);
        if (isset($url['query'])) {
            parse_str($url['query'], $params);
        } else {
            $params = [];
        }

        $uri = $this->buildUrl($url, $params);
        $params[$this->parameter] = $this->computeHash($uri);

        return $this->buildUrl($url, $params);
    }

    public function check(string $uri): bool
    {
        $url = parse_url($uri);
        if (isset($url['query'])) {
            parse_str($url['query'], $params);
        } else {
            $params = [];
        }

        if (empty($params[$this->parameter])) {
            return false;
        }

        $hash = $params[$this->parameter];
        unset($params[$this->parameter]);

        return hash_equals($this->computeHash($this->buildUrl($url, $params)), $hash);
    }

    public function checkRequest(Request $request): bool
    {
        $qs = ($qs = $request->server->get('QUERY_STRING')) ? '?' . $qs : '';

        return $this->check($request->getSchemeAndHttpHost() . $request->getBaseUrl() . $request->getPathInfo() . $qs);
    }

    private function computeHash(string $uri): string
    {
        return base64_encode(hash_hmac('sha256', $uri, $this->secret, true));
    }

    private function buildUrl(array $url, array $params = []): string
    {
        \ksort($params, \SORT_STRING);
        $url['query'] = \http_build_query($params, '', '&');
        $scheme = isset($url['scheme']) ? $url['scheme'] . '://' : '';
        $host = $url['host'] ?? '';
        $port = isset($url['port']) ? ':' . $url['port'] : '';
        $user = $url['user'] ?? '';
        $pass = isset($url['pass']) ? ':' . $url['pass'] : '';
        $pass = $user || $pass ? "{$pass}@" : '';
        $path = $url['path'] ?? '';
        $query = isset($url['query']) && $url['query'] ? '?' . $url['query'] : '';
        $fragment = isset($url['fragment']) ? '#' . $url['fragment'] : '';
        return $scheme . $user . $pass . $host . $port . $path . $query . $fragment;
    }
}
