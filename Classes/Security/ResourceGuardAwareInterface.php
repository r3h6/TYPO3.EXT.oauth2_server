<?php

namespace R3H6\Oauth2Server\Security;

interface ResourceGuardAwareInterface
{
    public function injectResourceGuard(ResourceGuard $resourceGuard): void;
}
