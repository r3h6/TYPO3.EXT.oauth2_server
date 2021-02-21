<?php

namespace R3H6\Oauth2Server\Routing;

class Route
{
    /** @var string */
    private $name;

    /** @var string */
    private $path;

    /** @var string[] */
    private $methods;

    /** @var array */
    private $options;

    public function __construct(string $name, string $path)
    {
        $this->name = $name;
        $this->path = $path;
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

    public function getOptions()
    {
        return $this->options;
    }

    public function setOptions($options)
    {
        $this->options = $options;

        return $this;
    }

    public function getName()
    {
        return $this->name;
    }
}
