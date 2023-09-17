<?php

namespace matpoppl\DbEntityGenerator;

class MysqlTypeMap
{
    /**
     *
     * @var int
     */
    private $ex;

    public function extractDbType($str)
    {
        $offset = strpos($str, '(');
        if (false === $offset) {
            return [$this->extract($str), $str, null];
        }

        $type = substr($str, 0, $offset);
        $settings = substr($str, $offset+1, strpos($str, ')') - $offset - 1);

        $handle = fopen('php://memory', 'w+');
        fputs($handle, $settings);
        fseek($handle, 0);
        $csv = fgetcsv($handle, 100, ",", "'");
        fclose($handle);

        return [$this->extract($str), $type, $csv];
    }

    public function extract($dbType)
    {
        $typeName = preg_replace('#(\w+)(.*)#', '$1', $dbType);

        switch ($typeName) {
            case 'tinyint':
            case 'smallint':
            case 'int':
            case 'integer':
                return 'int';
            case 'enum':
            case 'varchar':
            case 'text':
            case 'blob':
            case 'mediumtext':
            case 'longtext':
                return 'string';
        }

        throw new \DomainException('Unsupported db type `'.$typeName.'`');
    }
}
