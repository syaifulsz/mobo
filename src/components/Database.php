<?php

namespace ssz\mobo\components;

// components
use ssz\mobo\components\Config;

// vendors
use Illuminate\{
    Container\Container,
    Database\Capsule\Manager as Capsule
};
// use Jenssegers\Mongodb\Connection as MongoDBConnection;

class Database
{
    public function __construct( $config = [] )
    {
        $capsule = new Capsule;

        // extends capsule add mongo
        // $capsule->getDatabaseManager()->extend('mongodb', function($config) {
        //     return new MongoDBConnection($config);
        // });

        $config = array_replace_recursive( Config::get( 'database' ), $config );

        // if ( !isset( $config[ 'mysql' ] ) && !isset( $config[ 'mongodb' ] ) ) {
        //     throw new \Error('Database configuration is not set!');
        // }

        // setup mysql connection
        if ( isset( $config[ 'mysql' ] ) ) {
            $capsule->addConnection( array_merge( [
                'driver'    => 'mysql',
                'host'      => '127.0.0.1',
                'port'      => 3306,
                'database'  => '',
                'username'  => 'root',
                'password'  => 'root',
                'charset'   => 'utf8',
                'collation' => 'utf8_unicode_ci',
                'prefix'    => '',
            ], $config[ 'mysql' ] ) );
        }

        // setup mongo connection
        // if (isset($config['mongodb']['connection_name'])) {
        //     $capsule->addConnection(array_merge([
        //         'driver'   => 'mongodb',
        //         'host'     => 'twmongo',
        //         'port'     => '27017',
        //         'database' => 'tengokwayang_db_mongo',
        //         'username' => '',
        //         'password' => '',
        //         'options'  => [
        //             'database' => 'admin' // sets the authentication database required by mongo 3
        //         ]
        //     ], $config['mongodb']), @$config['mysql'] ? $config['mongodb']['connection_name'] : 'default');
        // }

        $capsule->setAsGlobal();
        $capsule->bootEloquent();
    }
}
