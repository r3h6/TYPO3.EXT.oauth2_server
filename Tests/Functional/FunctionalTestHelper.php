<?php

namespace R3H6\Oauth2Server\Tests\Functional;

use TYPO3\CMS\Core\Http\Uri;
use TYPO3\TestingFramework\Core\Functional\Framework\Frontend\InternalRequest;
use TYPO3\TestingFramework\Core\Functional\Framework\Frontend\InternalResponse;
use TYPO3\TestingFramework\Core\Functional\Framework\Frontend\InternalRequestContext;

trait FunctionalTestHelper
{

    protected function doFrontendRequest(string $method, string $uri, array $params = [], string $cookie = null): InternalResponse
    {
        $request = new InternalRequest($uri);
        $context = null;
        $globalSettings = [];

        if ($method === 'POST') {
            $globalSettings = array_merge($globalSettings, [
                '_SERVER' => ['REQUEST_METHOD' => 'POST'],
                '_POST' => $params,
            ]);
        } else {
            foreach ($params as $key => $value) {
                $request = $request->withQueryParameter($key, $value);
            }
        }

        if ($cookie !== null) {
            $globalSettings['_COOKIE'] = ['fe_typo_user' => $cookie];
        }

        if (!empty($globalSettings)) {
            $context = new InternalRequestContext();
            $context = $context->withGlobalSettings($globalSettings);
        }

        return $this->executeFrontendRequest($request, $context);
    }

    protected function getLastCookie(): string
    {
        preg_match_all('/Set Cookie: (?<cookie>[a-z0-9]+)/', file_get_contents('typo3temp/var/log/auth.log'), $matches);
        return end($matches['cookie']);
    }

    protected function getCodeFromResponse(InternalResponse $response): string
    {
        $redirect = new Uri($response->getHeader('location')[0]);
        $params = [];
        parse_str($redirect->getQuery(), $params);
        return $params['code'];
    }
}
