<?php

declare(strict_types=1);

namespace R3H6\Oauth2Server\Service;

use Psr\Http\Message\ServerRequestInterface;
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
 * @phpstan-method void writelog(int $type, int $action, int $error, int $code, string $details, array $data = [], string $tablename = '', int $recuid = '')
 */
class Oauth2AuthService extends AbstractAuthenticationService
{
    private const AUTH_FAILED = 0;
    private const AUTH_SUCCESS = 200;
    private const AUTH_CONTINUE = 100;

    public function getRequest(): ServerRequestInterface
    {
        return $this->authInfo['request'];
    }

    public function getUser(): array|bool
    {
        $request = $this->getRequest();
        $userId = $request->getAttribute('oauth_user_id') ?? null;

        if ($userId === null) {
            return false;
        }

        $dbUserSetup = $this->db_user;
        $dbUserSetup['check_pid_clause'] = '';
        $dbUserSetup['check_pid_clause'] = '';
        $where = $dbUserSetup['userid_column'] . ' = ' . (int)$userId;
        $user = $this->fetchUserRecord('', $where, $dbUserSetup);

        if ($user === false) {
            $message = 'Login-attempt from ###IP### for oauth_user_id \'%s\'';
            $this->writelog(SystemLogType::LOGIN, SystemLogLoginAction::ATTEMPT, SystemLogErrorClassification::SECURITY_NOTICE, 2, $message, [$userId]);
            $this->logger->warning(sprintf('Login-attempt from %s for oauth_user_id \'%s\'', $this->authInfo['REMOTE_ADDR'], $userId));
        } else {
            $this->logger->debug('User found', ['oauth_user_id' => $userId]);
        }

        return $user;
    }

    public function authUser(array $user): int
    {
        $request = $this->getRequest();
        $userId = $request->getAttribute('oauth_user_id');

        if ($userId === null) {
            return self::AUTH_CONTINUE;
        }

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
}
