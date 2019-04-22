<?php

namespace App\Battleship\Controller;

use Symfony\Component\HttpFoundation\Request;


class ControllerResolver
{

    /**
     * If the ...$arg functionality is available.
     *
     * Requires at least PHP 5.6.0 or HHVM 3.9.1
     *
     * @var bool
     */
    private $supportsVariadic;

    /**
     * If scalar types exists.
     *
     * @var bool
     */
    private $supportsScalarTypes;


    /**
     * ControllerResolver constructor.
     */
    public function __construct()
    {
        $this->supportsVariadic = method_exists('ReflectionParameter', 'isVariadic');
        $this->supportsScalarTypes = method_exists('ReflectionParameter', 'getType');
    }

    /**
     * @param Request $request
     * @return bool|callable
     */
    public function getController(Request $request)
    {
        if (!$controller = $request->attributes->get('_controller')) {
            throw new \InvalidArgumentException('The _controller parameters is missing from the request attributes');
        }

        $callable = $this->createController($controller);

        if (!is_callable($callable)) {
            throw new \InvalidArgumentException(sprintf('The controller with URI "%s" is not callable',
                $request->getPathInfo()));
        }

        return $callable;
    }

    /**
     * @param Request $request
     * @param $controller
     * @return array
     * @throws \ReflectionException
     */
    public function getArguments(Request $request, $controller)
    {
        if (is_array($controller)) {
            $r = new \ReflectionMethod($controller[0], $controller[1]);
        } elseif (is_object($controller) && !$controller instanceof \Closure) {
            $r = new \ReflectionObject($controller);
            $r = $r->getMethod('__invoke');
        } else {
            $r = new \ReflectionFunction($controller);
        }

        return $this->doGetArguments($request, $controller, $r->getParameters());
    }

    /**
     * Returns a callable for the given controller.
     *
     * @param string $controller A Controller string
     *
     * @return callable A PHP callable
     *
     * @throws \InvalidArgumentException
     */
    protected function createController($controller)
    {
        if (false === strpos($controller, '::')) {
            throw new \InvalidArgumentException(sprintf('Unable to find controller "%s".', $controller));
        }

        list($class, $method) = explode('::', $controller, 2);

        if (!class_exists($class)) {
            throw new \InvalidArgumentException(sprintf('Class "%s" does not exist.', $class));
        }

        return array($this->instantiateController($class), $method);
    }

    /**
     * Returns an instantiated controller.
     *
     * @param string $class A class name
     *
     * @return object
     */
    protected function instantiateController($class)
    {
        return new $class();
    }

    /**
     * @param Request $request
     * @param $controller
     * @param array $parameters
     * @return array
     */
    protected function doGetArguments(Request $request, $controller, array $parameters)
    {
        $attributes = $request->attributes->all();
        $arguments = array();
        foreach ($parameters as $param) {
            if (array_key_exists($param->name, $attributes)) {
                if ($this->supportsVariadic && $param->isVariadic() && is_array($attributes[$param->name])) {
                    $arguments = array_merge($arguments, array_values($attributes[$param->name]));
                } else {
                    $arguments[] = $attributes[$param->name];
                }
            } elseif ($param->getClass() && $param->getClass()->isInstance($request)) {
                $arguments[] = $request;
            } elseif ($param->isDefaultValueAvailable()) {
                $arguments[] = $param->getDefaultValue();
            } elseif ($this->supportsScalarTypes && $param->hasType() && $param->allowsNull()) {
                $arguments[] = null;
            } else {
                if (is_array($controller)) {
                    $repr = sprintf('%s::%s()', get_class($controller[0]), $controller[1]);
                } elseif (is_object($controller)) {
                    $repr = get_class($controller);
                } else {
                    $repr = $controller;
                }

                throw new \RuntimeException(sprintf('Controller "%s" requires that you provide a value for the "$%s" argument (because there is no default value or because there is a non optional argument after this one).',
                    $repr, $param->name));
            }
        }

        return $arguments;
    }
}
