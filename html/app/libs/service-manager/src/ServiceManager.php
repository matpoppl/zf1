<?php

namespace matpoppl\ServiceManager;

use matpoppl\ServiceManager\Factory\FactoryInterface;
use matpoppl\Hydrator\ClassMethodsHydrator;

class ServiceManager implements ServiceManagerInterface
{
    /** @var string[] */
    private $aliases = [];
    /** @var \matpoppl\ServiceManager\Factory\FactoryInterface[] */
    private $factories = [];
    /** @var \matpoppl\ServiceManager\Factory\AbstractFactoryInterface[] */
    private $abstractFactories = [];
    /** @var mixed[] */
    private $services = [];

    /** @var \matpoppl\ServiceManager\ContainerInterface */
    private $fallbackContainer = null;

    public function __construct(array $options = null)
    {
        if (null !== $options) {
            $this->setOptions($options);
        }
    }

    public function setOptions(array $options)
    {
        $hydrator = new ClassMethodsHydrator();
        $hydrator->hydrate($this, $options);
        return $this;
    }

    public function setFallbackContainer(ContainerInterface $fallbackContainer)
    {
        $this->fallbackContainer = $fallbackContainer;
        return $this;
    }

    public function setAliases(array $aliases)
    {
        $this->aliases = $aliases;
        return $this;
    }

    public function setFactories(array $factories)
    {
        $this->factories = $factories;
        return $this;
    }

    public function setAbstractFactories(array $abstractFactories)
    {
        $this->abstractFactories = $abstractFactories;
        return $this;
    }

    public function setServices(array $services)
    {
        $this->services = $services;
        return $this;
    }

    public function setService(string $id, $service)
    {
        $this->services[$id] = $service;
        return $this;
    }

    public function resolve(string $id): bool
    {
        if (! array_key_exists($id, $this->aliases)) {
            return null;
        }

        while (array_key_exists($id, $this->aliases)) {
            $id = $this->aliases[$id];
        }
        return $id;
    }

    public function has(string $id): bool
    {
        while (array_key_exists($id, $this->aliases)) {
            $id = $this->aliases[$id];
        }

        return array_key_exists($id, $this->factories) || array_key_exists($id, $this->services) || (null === $this->fallbackContainer ? false : $this->fallbackContainer->has($id));
    }

    public function get(string $id)
    {
        while (array_key_exists($id, $this->aliases)) {
            $id = $this->aliases[$id];
        }

        if (! array_key_exists($id, $this->services)) {
            $this->services[$id] = $this->build($id);
        }

        return $this->services[$id];
    }

    public function build(string $id, array $options = null)
    {
        $factory = $this->getFactoryFor($id) ?: $this->getAbstractFactoryFor($id);

        if (null === $factory) {
            if (null !== $this->fallbackContainer) {
                return $this->fallbackContainer->get($id);
            }

            throw new \UnexpectedValueException("Factory for `{$id}` not found");
        }

        return $factory($this, $id, $options);
    }

    /**
     *
     * @param string $id
     * @return \matpoppl\ServiceManager\Factory\FactoryInterface|NULL
     */
    public function getFactoryFor(string $id)
    {
        while (array_key_exists($id, $this->aliases)) {
            $id = $this->aliases[$id];
        }

        if (! array_key_exists($id, $this->factories)) {
            return null;
        }

        $className = $this->factories[$id];
        $factory = new $className();

        if (! ($factory instanceof FactoryInterface)) {
            throw new \UnexpectedValueException('Unsupported factory type');
        }

        return $factory;
    }

    /**
     *
     * @param string $id
     * @throws \DomainException
     * @return \matpoppl\ServiceManager\Factory\AbstractFactoryInterface|NULL
     */
    public function getAbstractFactoryFor(string $id)
    {
        foreach ($this->abstractFactories as $key => $abstractFactory) {
            if (is_string($abstractFactory)) {
                $abstractFactory = new $abstractFactory();

                if (! ($abstractFactory instanceof Factory\AbstractFactoryInterface)) {
                    throw new \DomainException('Unsupported AbstractFactory type');
                }

                $this->abstractFactories[$key] = $abstractFactory;
            }

            if ($abstractFactory->canCreate($this, $id)) {
                return $abstractFactory;
            }
        }

        return null;
    }
}
