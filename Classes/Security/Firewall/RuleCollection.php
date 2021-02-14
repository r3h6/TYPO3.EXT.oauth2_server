<?php

namespace R3H6\Oauth2Server\Security\Firewall;

use Psr\Http\Message\RequestInterface;

class RuleCollection
{
    private $rulesConfiguration;

    public function __construct(array $rulesConfiguration)
    {
        $this->rulesConfiguration;
    }

    public function match(RequestInterface $request): ?Rule
    {
        $path = trim($request->getUri()->getPath(), '/');
        $query = '&' . $request->getUri()->getQuery();
        $method = $request->getMethod();

        foreach ($this->rulesConfiguration as $ruleName => $ruleConfiguration) {
            $ruleConfiguration['name'] = $ruleName;
            $rule = Rule::fromArray($ruleConfiguration);

            $pathPattern = '/^' . addcslashes(trim($rule->getPath(), '/^$'), '/') . '$/i';
            if (!preg_match($pathPattern, $path)) {
                continue; // No match, try next rule
            }

            $methodsRules = $rule->getMethods();
            if (!empty($methodsRules) && !in_array($method, $methodsRules)) {
                continue; // No match, try next rule
            }

            $queryRules = $rule->getQuery();
            if (!empty($queryRules)) {
                foreach ($queryRules as $querRule) {
                    if (strpos($query, $querRule) === false) {
                        continue; // No match, try next rule
                    }
                }
            }

            return $rule;
        }
        return null;
    }
}
