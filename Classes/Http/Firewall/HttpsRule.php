<?php

namespace R3H6\Oauth2Server\Http\Firewall;

use Psr\Http\Message\ServerRequestInterface;
use R3H6\Oauth2Server\Exception\AccessDeniedException;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class HttpsRule implements RuleInterface
{
    private $https;

    public function __construct($https)
    {
        $this->https = (bool)$https;
    }

    public function __invoke(ServerRequestInterface $request)
    {
        if ($this->https && !GeneralUtility::getIndpEnv('TYPO3_SSL')) {
            throw new AccessDeniedException('Requires TLS', 1613595295245);
        }
    }
}
