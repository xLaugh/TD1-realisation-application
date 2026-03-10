<?php

namespace db;

use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;

class connection {

    public static function createConn() {
        $capsule = new DB;
        $configFile = "./config/config.ini";
        if (file_exists($configFile)) {
            $ini = parse_ini_file($configFile, true);
            $config = isset($ini['database']) ? $ini['database'] : $ini;
            $capsule->addConnection($config);
        } else {
            $capsule->addConnection([
                'driver'   => 'mysql',
                'host'     => getenv('DB_HOST') ?: 'db',
                'database' => getenv('DB_NAME') ?: 'racoin',
                'username' => getenv('DB_USER') ?: 'racoin',
                'password' => getenv('DB_PASS') ?: '',
                'charset'  => 'utf8',
                'port'     => getenv('DB_PORT') ?: 3306,
            ]);
        }
        $capsule->setAsGlobal();
        $capsule->bootEloquent();
    }
}