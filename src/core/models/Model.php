<?php

namespace ssz\mobo\core\models;

use ssz\mobo\components\Cache;
use SleekDB\SleekDB;

class Model
{
    protected $_id;
    protected $date_created;
    protected $date_modified;
    protected $__raw_data;
    protected $__table;
    protected $__dataDir;
    protected $__table_fillables = [];
    protected $__db;
    protected $project_id;
    public $site_id;
    protected $memcached;

    public function __construct( array $raw_data = [] )
    {
        $this->cache = new Cache;
        $this->__dataDir = ( $projectId = getenv( 'PROJECT_ID' ) ) ? realpath( __DIR__ . '/../../../sites/' . $projectId . '/runtime/data' ) : realpath( __DIR__ . '/../../runtime/data' );
        $this->__raw_data = $raw_data;
        $this->project_id = ( getenv( 'PROJECT_ID' ) ?? '' );
        $this->site_id = ( getenv( 'SITE_ID' ) ?? '' );

        if ( $raw_data ) {
            foreach ( $raw_data as $property => $value ) {
                if ( property_exists( $this, $property ) ) {
                    $this->$property = $value;
                }
            }
        }

        $this->__table = $this->getTableName();
        $this->__db = SleekDB::store( $this->__table, $this->__dataDir );
    }

    public function find( string $id )
    {
        return $this->db()->where( '_id', '=', $id );
    }

    public function db()
    {
        // return $this->__db->where( 'site_id', '=', $this->site_id );
        return $this->__db;
    }

    public function update( string $id = '' )
    {
        $id = $id ?? $this->_id;
        $data = [];
        foreach ( $this->__table_fillables as $prop ) {
            if ( property_exists( $this, $prop ) ) {
                $data[ $prop ] = $this->$prop;
            }
        }
        $data[ 'date_modified' ] = date( 'Y-m-d H:i:s' );
        // $data[ 'project_id' ] = $this->project_id;
        // $data[ 'site_id' ] = $this->site_id;

        return $this->find( $id )->update( $data );
    }

    public function delete( string $id )
    {
        return $this->find( $id )->delete();
    }

    public function set_attributes( array $array )
    {
        foreach ( $array as $prop => $value ) {
            if ( property_exists( $this, $prop ) ) {
                $this->$prop = $value;
            }
        }
    }

    public function save()
    {
        $data = [];
        foreach ( $this->__table_fillables as $prop ) {
            if ( property_exists( $this, $prop ) ) {
                $data[ $prop ] = $this->$prop;
            }
        }
        $data[ 'date_created' ] = $data[ 'date_modified' ] = date( 'Y-m-d H:i:s' );
        // $data[ 'project_id' ] = $this->project_id;
        // $data[ 'site_id' ] = $this->site_id;
        $res = $this->db()->insert( $data );
        if ( !empty( $res[ '_id' ] ) ) {
            $this->_id = $res[ '_id' ];
        }
        return $res;
    }

    public function getId()
    {
        return $this->_id;
    }

    public function getTableName()
    {
        if ( $this->__table ) {
            return $this->__table;
        }

        $__names = explode( '\\', static::class );
        $names = [];
        $grab = false;
        foreach ( $__names as $bit ) {

            if ( $grab ) {
                $names[] = $bit;
            }

            if ( $bit === 'models' ) {
                $grab = true;
            }
        }

        return snake_case( implode( ' ', $names ) );
    }

    public function getRawData()
    {
        return $this->__raw_data;
    }

    public function getData()
    {
        $array = [];
        foreach ( get_object_vars( $this ) as $key => $value ) {
            if ( $key !== '__raw_data' ) {
                $array[ $key ] = $value;
            }
        }

        return $array;
    }
}
