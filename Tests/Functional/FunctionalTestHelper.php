<?php

declare(strict_types=1);
namespace R3H6\Oauth2Server\Tests\Functional;

use League\OAuth2\Server\CryptKey;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\ScopeEntityInterface;
use R3H6\Oauth2Server\Domain\Bridge\AccessTokenRepository;
use TYPO3\CMS\Core\Http\Uri;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\TestingFramework\Core\Functional\Framework\Frontend\InternalRequest;
use TYPO3\TestingFramework\Core\Functional\Framework\Frontend\InternalRequestContext;
use TYPO3\TestingFramework\Core\Functional\Framework\Frontend\InternalResponse;

/***
 *
 * This file is part of the "OAuth2 Server" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2020
 *
 ***/

/**
 * FunctionalTestHelper
 */
trait FunctionalTestHelper
{
    protected function doFrontendRequest(string $method, string $uri, array $params = [], string $cookie = null): InternalResponse
    {
        $request = new InternalRequest($uri);
        $context = null;
        $globalSettings = [];

        if ($method === 'POST') {
            $globalSettings = array_merge_recursive($globalSettings, [
                '_SERVER' => ['REQUEST_METHOD' => 'POST'],
                '_POST' => $params,
            ]);
        } else {
            foreach ($params as $key => $value) {
                if (\strpos($key, 'HTTP_') === 0) {
                    $globalSettings = array_merge_recursive($globalSettings, [
                        '_SERVER' => [strtoupper($key) => $value],
                    ]);
                } else {
                    $request = $request->withQueryParameter($key, $value);
                }
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

    protected function createAccessToken(array $scopes = [], $userIdentifier = 1, ClientEntityInterface $client = null): AccessTokenEntityInterface
    {
        $length = 40;
        $client = $client ?? $this->createClientStub();
        $privateKey = new CryptKey(GeneralUtility::getFileAbsFileName('EXT:oauth2_server/Resources/Private/Keys/private.key'));
        $accessTokenRepository = GeneralUtility::makeInstance(AccessTokenRepository::class);
        $accessToken = $accessTokenRepository->getNewToken($client, $scopes, $userIdentifier);
        $accessToken->setIdentifier(\bin2hex(\random_bytes($length)));
        $accessToken->setPrivateKey($privateKey);
        $accessTokenRepository->persistNewAccessToken($accessToken);
        return $accessToken;
    }

    protected function createClientStub()
    {
        $client = $this->prophesize(ClientEntityInterface::class);
        $client->getIdentifier()->willReturn('660e56d72c12f9a1e2ec');
        $client->getName()->willReturn('Test');
        $client->getRedirectUri()->willReturn('http://localhost/');
        $client->isConfidential()->willReturn(true);
        return $client->reveal();
    }

    protected function createScopeMock(string $identifier)
    {
        $scope = $this->prophesize(ScopeEntityInterface::class);
        $scope->getIdentifier()->willReturn($identifier);
        $scope->jsonSerialize()->willReturn($identifier);
        return $scope->reveal();
    }
}
