<?php

namespace R3H6\Oauth2Server\Http\Firewall;

use Psr\Http\Message\ServerRequestInterface;

interface RuleInterface
{
    public function __invoke(ServerRequestInterface $request);
}
