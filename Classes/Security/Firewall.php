<?php

namespace R3H6\Oauth2Server\Security;

use Psr\Http\Message\ServerRequestInterface;
use R3H6\Oauth2Server\Security\Firewall\RuleCollection;

class Firewall
{
    /**
     * @var RuleCollection
     */
    private $rules;

    /**
     * @var ResourceGuard
     */
    private $resourceGuard;

    public function __construct(RuleCollection $rules)
    {
        $this->rules = $rules;
    }

    public function checkRequest(ServerRequestInterface $request): ServerRequestInterface
    {
        $rule = $this->rules->match($request);
        $request = $request->withAttribute('firewall.rule', $rule);

        if ($rule === null) {
            return $request;
        }

        $request = $this->resourceGuard->validateAuthenticatedRequest($request);

        $scope = $rule->getScope();
        if (!empty($scope)) {
            $this->tokenGuard->anyScope($scope, $request);
        }

        $scopes = $rule->getScopes();
        if (!empty($scopes)) {
            $this->tokenGuard->allScopes($scopes, $request);
        }

        return $request;
    }
}
