<?php

namespace App\EntityManager\DbTable;

use Zend_Db_Adapter_Abstract as DbAdapter;

class DbTableFactory
{
    /** @var DbAdapter */
    private $dbAdapter;

    public function __construct(DbAdapter $dbAdapter)
    {
        $this->dbAdapter = $dbAdapter;
    }

    public function create($options)
    {
        if (! is_array($options)) {
            $options = [
                'name' => $options,
                //'primary' => '', // string or array of primary key(s).
                //'rowClass' => '', // row class name.
                //'rowsetClass' => '', // rowset class name.
                //'referenceMap' => '', // array structure to declare relationship to parent tables.
                //'dependentTables' => '', // array of child tables.
                //'metadataCache' => '', // cache for information from adapter describeTable().
            ];
        }

        $options['adapter'] = $this->dbAdapter;

        $className = DbTable::class;

        if (array_key_exists('type', $options)) {
            $className = $options['type'];
            unset($options['type']);

            if (! is_subclass_of($className, AbstractDbTable::class)) {
                throw new \UnexpectedValueException('Unsupported table class');
            }
        }

        return new $className($options);
    }
}
