<?php

namespace ssz\mobo\components;

use app\components\{
    Session,
    Config
};
use app\models\User;

class Auth
{
    public function isAuth() : bool
    {
        return !empty( Session::getCookie( User::AUTH_COOKIE_KEY ) );
    }

    public function isAdmin() : bool
    {
        return ( self::getUser()->getRole( true ) === User::ROLE_ADMIN );
    }

    public function isOwner() : bool
    {
        return ( self::getUser()->getRole( true ) === User::ROLE_OWNER );
    }

    public function isAgent() : bool
    {
        return ( self::getUser()->getRole( true ) === User::ROLE_AGENT );
    }

    public function isCubeAgent() : bool
    {
        return ( self::getUser()->getModule( true ) === User::MODULE_CUBEAGENT );
    }

    public function isCubeDoc() : bool
    {
        return ( self::getUser()->getModule( true ) === User::MODULE_CUBEDOC );
    }

    public function isCubeMaster() : bool
    {
        return ( self::getUser()->getModule( true ) === User::MODULE_CUBEMASTER );
    }

    public function getUser()
    {
        if ( $user = Session::getCookie( User::AUTH_COOKIE_KEY ) ) {
            $user = json_decode( $user, true );

            if ( $user[ 'id' ] ) {
                $query = ( new User )->find( $user[ 'id' ] )->fetch();
                if ( !empty( $query[ 0 ] ) ) {
                    return new User( $query[ 0 ] );
                }
            }

            if ( $user = Config::get( 'fakeUser.' . $user[ 'username' ] ) ) {
                return new User( $user );
            }
        }

        return null;
    }

    public function logout()
    {
        Session::removeCookie( User::AUTH_COOKIE_KEY );
    }
}
