<?php

namespace R3H6\Oauth2Server\Http\Firewall;

use Psr\Http\Message\ServerRequestInterface;
use R3H6\Oauth2Server\Exception\AccessDeniedException;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class IpRule implements RuleInterface
{
    private $ip;

    public function __construct($ip)
    {
        $this->ip = (string)$ip;
    }

    public function __invoke(ServerRequestInterface $request)
    {
        if ($this->ip && !GeneralUtility::cmpIP(GeneralUtility::getIndpEnv('REMOTE_ADDR'), $this->ip)) {
            throw new AccessDeniedException('IP restriction', 1613681711046);
        }
    }
}
