<?php

$route->add( '/', [ '\\app\\controllers\\SiteController', 'index' ] );
$route->add( '/example', [ '\\app\\controllers\\SiteController', 'example' ] );
