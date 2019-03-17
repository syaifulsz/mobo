<?php

namespace ssz\mobo;

use app\components\{
    Route,
    Config,
    Session,
    Database
};

ini_set( 'error_log', ( getenv( 'PROJECT_ID' ) ? __DIR__ . '/../sites/' . getenv( 'PROJECT_ID' ) . '/runtime/logs/app.log' : __DIR__ . '/runtime/logs/app.log' ) );
date_default_timezone_set( Config::get( 'app.timezone' ) );

define( 'MINUTE_IN_SECONDS', 60 );
define( 'HOUR_IN_SECONDS',   60 * MINUTE_IN_SECONDS );
define( 'DAY_IN_SECONDS',    24 * HOUR_IN_SECONDS   );
define( 'WEEK_IN_SECONDS',    7 * DAY_IN_SECONDS    );
define( 'MONTH_IN_SECONDS',  30 * DAY_IN_SECONDS    );
define( 'YEAR_IN_SECONDS',  365 * DAY_IN_SECONDS    );

class Bootstrap
{
    public function __construct()
    {
        $this->setupDatabase();
        $this->setupToken();
        $this->setupRoute();
    }

    private function setupToken()
    {
        session_start();

        Session::initToken();

        if ( !empty( $_POST ) ) {

            if ( empty( $_POST[ 'token' ] ) ) {
                // http_response_code( 401 );
                Session::setMessage( [
                    'tag' => 'alert',
                    'type' => 'danger',
                    'message' => 'Session expired! Please try again.',
                    'data' => []
                ] );
                Url::redirect( Url::to( 'adminLogin' ) );
                exit;
            }

            $sessionToken = $_SESSION[ 'token' ];
            $token = $_POST[ 'token' ];
            $this->resetToken();

            if ( !hash_equals( $sessionToken, $token ) ) {

                // http_response_code( 401 );
                Session::setMessage( [
                    'tag' => 'alert',
                    'type' => 'danger',
                    'message' => 'Session expired! Please try again.',
                    'data' => []
                ] );
                Url::redirect( Url::to( 'adminLogin' ) );
                exit;
            }
        }
    }

    private function resetToken()
    {
        Session::resetToken();
    }

    private function setupDatabase()
    {
        if ( Config::get( 'database.useMysql' ) ) {
            new Database;
        }
    }

    private function setupRoute()
    {
        $route = new Route;

        $routeDir = __DIR__ . '/routes/*.php';
        foreach ( glob( $routeDir ) as $file ) {
            require( $file );
        }

        if ( $projectId = getenv( 'PROJECT_ID' ) ) {
            $routeDir = __DIR__ . '/../sites/' . $projectId . '/routes/*.php';
            foreach ( glob( $routeDir ) as $file ) {
                require( $file );
            }
        }

        $route->listen();

        Session::clearMessages();
    }
}