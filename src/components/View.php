<?php

namespace ssz\mobo\components;

use app\components\Config;

class View
{
    const BLOCK_HEAD = 'blockHead';
    const BLOCK_BODY_START = 'blockBodyStart';
    const BLOCK_BODY_END = 'blockBodyEnd';

    public $layout = 'main';
    public $params = [];
    public $template;
    public $htmlClass;
    public $pageTitle;
    public $pageTitleArray = [];
    public $bodyClass = [];
    public $content;
    public $breadcrumb;
    public $blockHead = [];
    public $blockBodyStart = [];
    public $blockBodyEnd = [];
    protected $__raw;

    public function __construct( array $raw = [] )
    {
        if ( $this->__raw = $raw ) {
            foreach ( $raw as $property => $value ) {
                if ( property_exists( $this, $property ) ) {
                    $this->$property = $value;
                }
            }
        }
        if ( empty( $this->pageTitleArray ) ) {
            $this->pageTitleArray[] = Config::get( 'app.name' );
        }
    }

    public function addBodyClass( string $class )
    {
        $this->bodyClass[$class] = $class;
    }

    public function getRaw()
    {
        $array = [];
        foreach ( get_object_vars( $this ) as $key => $value ) {
            if ( $key !== '__raw' ) {
                $array[ $key ] = $value;
            }
        }

        return $array;
    }

    public function block()
    {
        ob_start();
    }

    public function blockEnd( string $block = '', string $key = '' )
    {
        $render = ob_get_contents();
        ob_end_clean();

        if ( !$block ) {
            echo $render;
        }

        if ( $key ) {
            $this->$block[ $key ] = $render;
        } else {
            $this->$block[] = $render;
        }
    }

    public function renderPartial( string $template_name, array $params = [] ) : string
    {
        if ( $template = $this->getTemplate( $template_name ) ) {
            extract( array_replace_recursive( $this->params, $params ), EXTR_SKIP );

            ob_start();
            require( $this->getTemplate( $template_name ) );
            $render_output = ob_get_contents();
            ob_end_clean();

            return $render_output;
        }

        return '';
    }

    public function render( string $template_name = '' )
    {
        return $this->renderPartial( $this->getLayout(), array_merge( $this->getData(), [
            'content' => $this->renderPartial( $template_name, $this->getData() )
        ] ) );
    }

    public function getTemplate( string $template_name = '' ) : string
    {
        $template = '';
        $template_name = $template_name ?: $this->template;

        if ( $projectId = getenv( 'PROJECT_ID' ) ) {
            $template = __DIR__ . '/../../sites/' . $projectId . '/views/' . $template_name . '.php';
        }

        if ( !file_exists( $template ) ) {
            $template = __DIR__ . '/../views/' . $template_name . '.php';
            if ( !file_exists( $template ) ) {
                throw new \Error( __METHOD__ . ' :: template not found :: ' . $template );
            }
        }

        return $template;
    }

    public function staticRender( string $template_name, array $params = [] ) : string
    {
        if ( $template = self::getTemplate( $template_name ) ) {
            extract( $params, EXTR_SKIP );

            ob_start();
            require( self::getTemplate( $template_name ) );
            $render_output = ob_get_contents();
            ob_end_clean();

            return $render_output;
        }

        return '';
    }

    public function getBlockHead()
    {
        return implode( '', $this->blockHead );
    }

    public function getBlockBodyStart()
    {
        return implode( '', $this->blockBodyStart );
    }

    public function getBlockBodyEnd()
    {
        return implode( '', $this->blockBodyEnd );
    }

    public function getHtmlClass()
    {
        return $this->htmlClass;
    }

    public function getPageTitle()
    {
        $array = $this->pageTitleArray;
        if ( $this->pageTitle ) {
            $array[] = $this->pageTitle;
        }
        krsort( $array );
        return implode( ' - ', $array );
    }

    public function addTitleCrumb( string $title )
    {
        array_push( $this->pageTitleArray, $title );
    }

    public function getPageTitleArray()
    {
        return implode( ' - ', $this->pageTitleArray );
    }

    public function getBodyClass()
    {
        return implode(' ', $this->bodyClass);
    }

    public function getContent()
    {
        return $this->content;
    }

    public function getBreadcrumb()
    {
        return $this->breadcrumb;
    }

    public function getParams()
    {
        return $this->params;
    }

    public function getData()
    {
        return [
            'params' => $this->getParams(),
            'layout' => $this->getLayout(),
            'params' => $this->getParams(),
            'htmlClass' => $this->getHtmlClass(),
            'pageTitle' => $this->getPageTitle(),
            'bodyClass' => $this->getBodyClass(),
            'breadcrumb' => $this->getBreadcrumb(),
            'blockHead' => $this->getBlockHead(),
            'blockBodyStart' => $this->getBlockBodyStart(),
            'blockBodyEnd' => $this->getBlockBodyEnd()
        ];
    }

    public function getLayout()
    {
        return 'layouts/' . $this->layout;
    }
}
