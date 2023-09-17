<?php

namespace matpoppl\DbEntityGenerator;

use matpoppl\Hydrator\ClassMethodsHydrator;

class ClassPropertyFactory
{
    public function create($options)
    {
        if (is_string($options)) {
            return new ClassProperty($options);
        }

        if (is_array($options)) {
            $property = new ClassProperty(null);
            $hydrator = new ClassMethodsHydrator();
            $hydrator->hydrate($property, $options);
        } else {
            throw new \UnexpectedValueException('Unsupported ClassProperty options');
        }

        return $property;
    }
}
