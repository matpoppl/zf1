<?php

namespace matpoppl\Pdb\Adapter;

class AdapterFactoryTest
{
    public function testSQLite()
    {
        $factory = new AdapterFactory();

        $sqlite = $factory->create([
            'type' => 'SQLite3',
            'options' => [
                'pathname' => PDB_TEST_DATA_DIR . 'sample.sqlite',
            ],
        ]);

        self::assertEquals(['first_table'], $sqlite->listTables());
    }
}
