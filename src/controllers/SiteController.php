<?php

namespace ssz\mobo\controllers;

use ssz\mobo\core\controllers\Controller;
use ssz\mobo\components\View;

class SiteController extends Controller
{
    public function index()
    {
        $this->render( 'index' );
    }

    public function example()
    {
        $this->render( 'example' );
    }
}
