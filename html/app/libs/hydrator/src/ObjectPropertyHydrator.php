<?php

namespace matpoppl\Hydrator;

class ObjectPropertyHydrator implements HydratorInterface
{
    private $strictProperties = true;

    public function setStrictProperties($strictProperties)
    {
        $this->strictProperties = $strictProperties;
    }

    public function hydrate($obj, array $data)
    {
        if ($this->strictProperties) {
            foreach (array_intersect_key($data, get_object_vars($obj)) as $name => $val) {
                $obj->{$name} = $val;
            }
        } else {
            foreach ($data as $name => $val) {
                $obj->{$name} = $val;
            }
        }
    }

    public function extract($obj)
    {
        return get_object_vars($obj);
    }
}
