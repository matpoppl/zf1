<?php

namespace matpoppl\Hydrator;

class ClassMethodsHydrator implements HydratorInterface
{
    public function hydrate($obj, array $data)
    {
        foreach ($data as $name => $val) {
            $method = str_replace(['_', '-'], ' ', $name);
            $method = ucwords($method);
            $method = 'set' . str_replace(' ', '', $method);
            if (method_exists($obj, $method)) {
                $obj->{$method}($val);
            }
        }
    }

    public function extract($obj)
    {
        $ret = [];

        foreach (get_class_methods(get_class($obj)) as $method) {
            if (0 !== strpos($method, 'get')) {
                continue;
            }

            // strip get
            $name = substr($method, 3);
            // deconstruct Camel_Case
            $name = preg_replace('#([A-Z]+)#', '_$0', $name);
            $name = ltrim($name, '_');
            // lower_case
            $name = strtolower($name);

            $ret[$name] = $obj->{$method}();
        }

        return $ret;
    }
}
