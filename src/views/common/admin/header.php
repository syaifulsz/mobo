<?php

use app\components\{
    Url,
    Auth
};

$user = Auth::getUser();

?>

<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm mb-3">
    <div class="container">
        <a class="navbar-brand" href="<?= Url::base() ?>"><i class="fas fa-dice-d20 text-muted"></i></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#main-navbar" aria-controls="main-navbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="main-navbar">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="<?= Url::base() ?>">Home <span class="sr-only">(current)</span></a>
                </li>

                <?php /* if ( Auth::isAuth() && !Auth::isCubeDoc() ) : ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">
                            Agents
                        </a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="<?= Url::to( 'agentAdd' ) ?>">Add Agent</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="<?= Url::to( 'agentIndex' ) ?>">Manage Agents</a>
                        </div>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">
                            Products
                        </a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="<?= Url::to( 'productAdd' ) ?>">Add Product</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="<?= Url::to( 'productIndex' ) ?>">Manage Products</a>
                        </div>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">
                            Location
                        </a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="<?= Url::to( 'locationStateAdd' ) ?>">Add State</a>
                            <a class="dropdown-item" href="<?= Url::to( 'locationAreaAdd' ) ?>">Add Area</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="<?= Url::to( 'locationIndex' ) ?>">All</a>
                            <a class="dropdown-item" href="<?= Url::to( 'locationState' ) ?>">Manage States</a>
                            <a class="dropdown-item" href="<?= Url::to( 'locationArea' ) ?>">Manage Areas</a>
                        </div>
                    </li>
                <?php endif */ ?>
            </ul>
            <?php if ( Auth::isAuth() ) : ?>
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">
                            <?= $user->getName() ?>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="<?= Url::to( 'profileIndex' ) ?>">Profile</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="<?= Url::to( 'adminLogout' ) ?>">Logout</a>
                        </div>
                    </li>
                </ul>
            <?php else : ?>
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= Url::to( 'adminLogin' ) ?>">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= Url::to( 'adminRegister' ) ?>">Register</a>
                    </li>
                </ul>
            <?php endif ?>
        </div>
    </div>
</nav>
