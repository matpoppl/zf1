<?php

use Panel\Controller\AbstractController;

class Panel_DebugController extends AbstractController
{
    public function logsAction()
    {
        phpinfo();
        die();
    }

    public function phpinfoAction()
    {
        phpinfo();
        die();
    }

    public function dbAction()
    {
        /** @var \Zend_Db_Adapter_Abstract $db */
        $db = $this->get('Db');

        $cfg = $db->getConfig();

        $dsn = sprintf('%s://%s@%s/%s', substr(get_class($db), 16), $cfg['username'], $cfg['host'], $cfg['dbname']);

        $dbVars = [];

        if ($db instanceof Zend_Db_Adapter_Pdo_Mysql) {
            $rows = $db->query('SHOW VARIABLES')->fetchAll();
            $dbVars = array_combine(array_column($rows, 'Variable_name'), array_column($rows, 'Value'));
        }

        return $this->render2('debug/db.phtml', [
            'dbOptions' => [
                'adapterClass' => get_class($db),
                'serverVersion' => $db->getServerVersion(),
                'dsn' => $dsn,
                'isConnected' => $db->isConnected() ? 'yes' : 'no',
            ] + array_diff_key($db->getConfig(), [
                'host' => true,
                'dbname' => true,
                'username' => true,
                'password' => true,
                'persistent' => true,
                'options' => true,
                'driver_options' => true,
            ]),
            'dbTables' => $db->listTables(),
            'dbVars' => $dbVars,
        ]);
    }
}
