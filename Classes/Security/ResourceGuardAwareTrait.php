<?php

namespace R3H6\Oauth2Server\Security;

trait ResourceGuardAwareTrait
{
    /**
     * @var ResourceGuard
     */
    private $resourceGuard;

    public function injectResourceGuard(ResourceGuard $resourceGuard): void
    {
        $this->resourceGuard = $resourceGuard;
    }
}
