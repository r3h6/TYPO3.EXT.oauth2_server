<?php

namespace R3H6\Oauth2Server\Tests\Functional;

use TYPO3\CMS\Core\Http\StreamFactory;
use TYPO3\TestingFramework\Core\Functional\Framework\Frontend\InternalRequest;
use TYPO3\TestingFramework\Core\Functional\Framework\Frontend\InternalRequestContext;

final class RefreshTokenGrantTest extends ApplicationTestCase
{
    /** Expires 17-6-2034 */
    private const REFRESH_TOKEN = 'def50200afddde02c4a694a92991e1b87267c88c6a8f97c6eadf6b61e5553434b87c95833c5b5d4d34e4857221f1214ea847473049c7e40026cb4a42d8138a643e61bd2778abb3300bf721b464a68e4e94da59a2595b0a07684927798940d5061e16c68600893aeea62797fbbe67ca46f876353ae7911496c7eb271c48752ab86360346bccaea4a84529776a7e1f8e0fa7b2d2c0ba733f17cbdb8de43362f7dbabab6c68db7fbaf39ae28b7eed32e5436878682ba25d7592379cb99c64d78b2a63bf033f060dcce9d70213514c23f8d032a9a02111045ae3450a2d8250582149de889cae2bdbb7d1339aae20aba9c6e2290b25095e8a7e337b31952fda93f9120f9db4904a180fc796f364784ada430bfe6aa4d6cff8cafb45fb8edcaf378175739917622847084bb84c6f1c8bf11c7b8c0dfc434b700567acb0a3d44bda9039c22ed0f2893c42dc81c959c21a1e00d5cc7b8d07f4748924b10cb440a2fa804ded1b8d4198dcff78868a46317c9e6d0228203d761db6e55a11e8d663082142e76a35a91a8f7bb982c4d89d09e4ce4e2bdbb749fb4775c7b75d';

    /**
     * @test
     */
    public function refreshTokenGrant(): void
    {
        $this->importCSVDataSet(__DIR__ . '/../Fixtures/Database/refreshToken.csv');
        $request = (new InternalRequest('https://localhost/oauth2/token'))
            ->withMethod('POST')
            ->withHeader('Content-Type', 'application/x-www-form-urlencoded')
            ->withBody((new StreamFactory())->createStream(http_build_query([
                'grant_type' => 'refresh_token',
                'refresh_token' => self::REFRESH_TOKEN,
                'client_id' => 'fc17757d-aa0c-481d-96d7-c2504ce7199f',
                'client_secret' => 'Password1!',
                'scope' => 'news read write',
            ])));

        $context = new InternalRequestContext();
        $response = $this->executeFrontendSubRequest($request, $context);
        self::assertStringContainsString('access_token', (string)$response->getBody());
    }
}
