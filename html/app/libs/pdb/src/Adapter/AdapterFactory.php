<?php

namespace matpoppl\Pdb\Adapter;

use matpoppl\Pdb\Adapter\SQLite\SQLiteAdapter;
use matpoppl\Pdb\Adapter\PDO\PDOMysql;
use matpoppl\Pdb\Adapter\MySQL\MySQLAdapter;
use matpoppl\Pdb\Adapter\MySQLi\MySQLiAdapter;

class AdapterFactory
{
    private $aliases = [
        'SQLite3' => SQLiteAdapter::class,
        'PDOMySQL' => PDOMysql::class,
        'MySQL' => MySQLAdapter::class,
        'MySQLi' => MySQLiAdapter::class,
    ];
    /**
     *
     * @param array $options
     * @return AdapterInterface
     */
    public function create(array $options)
    {
        if (! array_key_exists('type', $options)) {
            throw new \InvalidArgumentException('Options key required `type`');
        }

        if (! array_key_exists('options', $options)) {
            throw new \InvalidArgumentException('Options key required `options`');
        }

        $className = $options['type'];

        while (array_key_exists($className, $this->aliases)) {
            $className = $this->aliases[$className];
        }

        if (! class_exists($className)) {
            throw new AdapterException("Unrecognized adapter type `{$className}`");
        }

        if (! is_subclass_of($className, AdapterInterface::class, true)) {
            throw new AdapterException("Unsupported adapter type `{$className}`");
        }

        return new $className($options['options']);
    }
}
