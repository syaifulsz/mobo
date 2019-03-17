<?php

namespace ssz\mobo\core\controllers;

use app\components\{
    View,
    Url,
    Request,
    Cache
};
use app\models\layouts\Layout;

class Controller
{
    protected $layout = 'main';
    protected $breadcrumb;
    protected $view;
    protected $request;
    protected $cache;

    public function __construct( array $config = [] )
    {
        if ( $config ) {
            foreach ( $config as $property => $value ) {
                if ( property_exists( $this, $property ) ) {
                    $this->$property = $value;
                }
            }
        }

        $this->cache = new Cache;
        $this->view = new View;
        $this->view->layout = $this->layout;
        $this->view->breadcrumb = [
            'home' => [
                'label' => 'Home',
                'url' => Url::base()
            ]
        ];
        $this->request = new Request;
    }

    protected function addCrumb( string $key, string $label, string $url )
    {
        $this->view->breadcrumb[ 'home' ][ 'active' ] = false;
        $this->view->breadcrumb[ $key ] = [
            'label' => $label,
            'url' => $url
        ];
    }

    public function render( string $template, array $data = [], bool $partial = false )
    {
        $this->view->params = $data;
        if ( $partial ) {
            return $this->view->render( $template );
        }
        echo $this->view->render( $template );
    }
}
