<?php

declare(strict_types=1);
namespace R3H6\Oauth2Server\Service;

use Psr\Http\Message\ServerRequestInterface;
use R3H6\Oauth2Server\Http\RequestAttribute;
use TYPO3\CMS\Core\Authentication\AbstractAuthenticationService;
use TYPO3\CMS\Core\SysLog\Action\Login as SystemLogLoginAction;
use TYPO3\CMS\Core\SysLog\Error as SystemLogErrorClassification;
use TYPO3\CMS\Core\SysLog\Type as SystemLogType;

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
 * Oauth2AuthService
 */
class Oauth2AuthService extends AbstractAuthenticationService
{
    private const AUTH_FAILED = 0;
    private const AUTH_SUCCESS = 200;
    private const AUTH_CONTINUE = 100;

    public function getRequest(): ?ServerRequestInterface
    {
        return $GLOBALS['TYPO3_REQUEST'] ?? null;
    }

    public function getUser()
    {
        $user = false;
        $request = $this->getRequest();

        if ($request == null) {
            return $user;
        }

        $isOauth2Request = $request->getAttribute(RequestAttribute::CONFIGURATION) !== null;
        $userId = $request->getAttribute('oauth_user_id');

        if ($isOauth2Request && $userId !== null) {
            $dbUserSetup = $this->db_user;
            $dbUserSetup['check_pid_clause'] = '';
            $user = $this->fetchUserRecord('', 'uid=' . (int)$userId, $dbUserSetup);

            if ($user === false) {
                $message = 'Login-attempt from ###IP### for oauth_user_id \'%s\'';
                $this->writelog(SystemLogType::LOGIN, SystemLogLoginAction::ATTEMPT, SystemLogErrorClassification::SECURITY_NOTICE, 2, $message, [$userId]);
                $this->logger->warning(sprintf('Login-attempt from %s for oauth_user_id \'%s\'', $this->authInfo['REMOTE_ADDR'], $userId));
            } else {
                $this->logger->debug('User found', ['oauth_user_id' => $userId]);
            }
        }
        return $user;
    }

    /**
     * @param array $user User data
     * @return int Authentication status code, one of 0, 100, 200
     */
    public function authUser(array $user): int
    {
        $request = $this->getRequest();

        if ($request == null) {
            return self::AUTH_CONTINUE;
        }

        $isOauth2Request = $request->getAttribute(RequestAttribute::CONFIGURATION) !== null;
        $userId = $request->getAttribute('oauth_user_id');
        if ($isOauth2Request && $userId !== null) {
            if ((int)$user['uid'] === (int)$userId) {
                $message = $this->pObj->loginType . ' Authentication successful for oauth_user_id \'%s\'';
                $this->logger->notice(sprintf($message, $userId));
                return self::AUTH_SUCCESS;
            }

            $message = 'Login-attempt from ###IP###, oauth_user_id \'%s\', user uid does not match!';
            $this->writelog(SystemLogType::LOGIN, SystemLogLoginAction::ATTEMPT, SystemLogErrorClassification::SECURITY_NOTICE, 1, $message, [$userId]);
            $this->logger->info(sprintf($message, $userId));

            return self::AUTH_FAILED;
        }
        return self::AUTH_CONTINUE;
    }
}
