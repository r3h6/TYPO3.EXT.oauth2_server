<?php

namespace R3H6\Oauth2Server\Tests\Unit;

use TYPO3\CMS\Core\Http\RedirectResponse;
use Psr\Http\Message\ServerRequestInterface;
use League\OAuth2\Server\AuthorizationServer;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;
use R3H6\Oauth2Server\Domain\Repository\UserRepository;
use R3H6\Oauth2Server\Controller\AuthorizationController;
use League\OAuth2\Server\RequestTypes\AuthorizationRequest;
use R3H6\Oauth2Server\Domain\Repository\AccessTokenRepository;
use TYPO3\CMS\Frontend\Authentication\FrontendUserAuthentication;

class AuthorizationControllerTest extends UnitTestCase
{
    use \Prophecy\PhpUnit\ProphecyTrait;

    /**
     * @var AuthorizationController
     */
    private $subject;

    /**
     * @var AuthorizationServer
     */
    private $server;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var AccessTokenRepository
     */
    private $accessTokenRepository;

    public function setUp (): void
    {
        parent::setUp();

        $this->server = $this->prophesize(AuthorizationServer::class);
        $this->userRepository = $this->prophesize(UserRepository::class);
        $this->accessTokenRepository = $this->prophesize(AccessTokenRepository::class);

        $this->subject = new AuthorizationController($this->server->reveal(), $this->userRepository->reveal(), $this->accessTokenRepository->reveal());
    }

    /**
     * @test
     */
    public function startAuthorizationWillReturnRedirectToConsent()
    {
        $frontenUser = $this->prophesize(FrontendUserAuthentication::class);

        $request = $this->prophesize(ServerRequestInterface::class);
        $request->getAttribute('frontend.user')->willReturn($frontenUser);

        $authRequest = $this->prophesize(AuthorizationRequest::class);
        $this->server->validateAuthorizationRequest($request->reveal())->willReturn($authRequest->reveal());

        $response = $this->subject->startAuthorization($request->reveal());

        self::assertInstanceOf(RedirectResponse::class, $response);
        self::assertSame('/?redirect_url=%2Fconsent', $response->getHeader('Location')[0]);
        $frontenUser->setAndSaveSessionData('oauth2/authRequest', $authRequest->reveal())->shouldHaveBeenCalled();
    }

}
