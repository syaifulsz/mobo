<?php

namespace ssz\mobo\components;

use ssz\mobo\components\{
    Config,
    Auth
};

class Url
{
    public function base( string $url = '' ) : string
    {
        // if ( getenv( 'SITE_URL' ) !== 'smartejen' ) {
        //     return self::siteBase( $url );
        // }

        $url = ltrim( $url, '/' );
        return Config::get( 'app.baseUrl' ) . ( $url ? "/{$url}" : '' );
    }

    public function siteBase( string $url = '' ) : string
    {
        $url = ltrim( $url, '/' );
        return Config::get( 'app.siteBaseUrl' ) . ( $url ? "/{$url}" : '' );
    }

    public function to( string $name, array $params = [] ) : string
    {
        if ( Auth::isAuth() && ( $name === 'adminLogin' ) ) {
            $name = 'home';
        }

        $query = [];
        if ( $url = Config::get( 'routes.' . $name ) ) {
            $url = ltrim( $url[ 0 ], '/' );
            if ( $params ) {

                foreach ( $params as $key => $value ) {
                    $__key = '{' . $key . '}';
                    if ( str_contains( $url, $__key ) ) {
                        $url = str_replace( $__key, $value, $url );
                    } else {
                        $query[ $key ] = $value;
                    }
                }
            }
            $url = rtrim( preg_replace( '/\{\w+\}/', '', $url ), '/' ) . ( $query ? '?' . http_build_query( $query ) : '' );
        }

        return $url ? "/{$url}" : '/';
    }

    public function redirect( $url, $permanent = false )
    {
        header('Location: ' . $url, true, $permanent ? 301 : 302);
        exit();
    }
}
