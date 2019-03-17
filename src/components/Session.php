<?php

namespace ssz\mobo\components;

class Session
{
    public function setMessage( array $message )
    {
        if ( isset( $_SESSION[ 'messages' ] ) && is_array( $_SESSION[ 'messages' ] ) ) {
            $_SESSION[ 'messages' ][] = $message;
        } else {
            $_SESSION[ 'messages' ] = [
                $message
            ];
        }
    }

    public function getMessages() : array
    {
        return $_SESSION[ 'messages' ] ?? [];
    }

    public function clearMessages()
    {
        unset( $_SESSION[ 'messages' ] );
    }

    public function resetToken()
    {
        setcookie( '_token', '', time() - 3600);
        if ( !empty( $_SESSION[ 'token' ] ) ) {
            unset( $_SESSION[ 'token' ] );
        }

        return self::getToken();
    }

    public function initToken()
    {
        if ( empty( $_SESSION[ 'token' ] ) || empty( $_COOKIE[ '_token' ] ) ) {
            $_SESSION[ 'token' ] = bin2hex( random_bytes( 32 ) );
            setcookie( '_token', true, time() + ( 5 * MINUTE_IN_SECONDS ), '/' );
        }

        return $_SESSION[ 'token' ];
    }

    public function getToken() : string
    {
        return self::initToken();
    }

    public function getCookie( string $key )
    {
        return !empty( $_COOKIE[ $key ] ) ? $_COOKIE[ $key ] : null;
    }

    public function setCookie( string $key, $value, int $duration = 0, string $path = '/' )
    {
        setcookie( $key, $value, time() + ( $duration ?: ( 5 * MINUTE_IN_SECONDS ) ) , $path );
    }

    public function removeCookie( string $key )
    {
        setcookie( $key, '', time() - 3600);
    }
}
