<?php

namespace R3H6\Oauth2Server\Security\Firewall;

class Rule
{
    /**
     * Undocumented variable
     *
     * @var string
     */
    private $name;

    /**
     * Undocumented variable
     *
     * @var string
     */
    private $path;

    /**
     * Undocumented variable
     *
     * @var string[]
     */
    private $methods;

    /**
     * Undocumented variable
     *
     * @var string[]
     */
    private $query;

    /**
     * Undocumented variable
     *
     * @var string[]
     */
    private $scope;

    /**
     * Undocumented variable
     *
     * @var string[]
     */
    private $scopes;

    public static function fromArray(array $rule): self
    {
        return new self(
            $rule['name'],
            $rule['path'],
            GeneralUtility::trimExplode('|', $rule['methods'] ?? ''),
            GeneralUtility::trimExplode(' ', $rule['query'] ?? ''),
            GeneralUtility::trimExplode('|', $rule['scope'] ?? ''),
            GeneralUtility::trimExplode(' ', $rule['scopes'] ?? '')
        );
    }

    public function __construct(string $name, string $path, array $methods = [], array $query = [], array $scope = [], array $scopes = [])
    {
        $this->name = $name;
        $this->path = $path;
        $this->methods = $methods;
        $this->query = $query;
        $this->scope = $scope;
        $this->scopes = $scopes;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function setPath(string $path)
    {
        $this->path = $path;

        return $this;
    }

    public function getMethods(): array
    {
        return $this->methods;
    }

    public function setMethods(array $methods)
    {
        $this->methods = $methods;

        return $this;
    }

    public function getQuery(): array
    {
        return $this->query;
    }

    public function setQuery(array $query)
    {
        $this->query = $query;

        return $this;
    }

    public function getScope(): array
    {
        return $this->scope;
    }

    public function setScope(array $scope)
    {
        $this->scope = $scope;

        return $this;
    }

    public function getScopes(): array
    {
        return $this->scopes;
    }

    public function setScopes(array $scopes)
    {
        $this->scopes = $scopes;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }
}
