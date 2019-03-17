<?php

namespace ssz\mobo\components;

class Config
{
    public function all()
    {
        $config = [];
        $configDir = __DIR__ . '/../configs/*.php';
        foreach (glob( $configDir ) as $file) {
            $config[ pathinfo( $file, PATHINFO_FILENAME ) ] = require( $file );
        }

        $local = [];
        $localPath = __DIR__ . '/../configs/local.php';
        if ( file_exists( $localPath ) ) {
            $local = require( $localPath );
            $config = array_replace_recursive( $config, $local );
        }

        if ( $projectId = getenv( 'PROJECT_ID' ) ) {
            $projectDir = __DIR__ . '/../../sites/' . $projectId . '/configs';

            $projectConfig = [];
            foreach (glob( $projectDir . '/*.php' ) as $file) {
                $projectConfig[ pathinfo( $file, PATHINFO_FILENAME ) ] = require( $file );
            }

            $local = [];
            if ( file_exists( $projectDir . '/local.php' ) ) {
                $local = require( $projectDir . '/local.php' );
                $projectConfig = array_replace_recursive( $projectConfig, $local );
            }

            $config = array_replace_recursive( $config, $projectConfig );
        }

        return $config;
    }

    public function get( string $key = '' )
    {
        if ( $key ) {
            return data_get( self::all(), $key );
        }

        return null;
    }
}
