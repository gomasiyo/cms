<?php
/**
 *  Router file
 */

$router = new \Phalcon\Mvc\Router(false);

/**
 *  /
 */
$router->add(
    '/',
    [
        'controller' => 'Index',
        'action' => 'index'
    ]
);

/**
 *  /auth/signup
 */
$router->addPost(
    '/auth/signup',
    [
        'controller' => 'auth',
        'action' => 'signup'
    ]
);

/**
 *  /auth/login
 */
$router->addPost(
    '/auth/login',
    [
        'controller' => 'auth',
        'action' => 'login'
    ]
);

/**
 *  /entry/add
 */
$router->addPost(
    '/entry/add',
    [
        'controller' => 'entry',
        'action' => 'add'
    ]
);


/**
 *  /entry/all
 */
$router->addPost(
    '/entry/all',
    [
        'controller' => 'entry',
        'action' => 'all'
    ]
);

/**
 *  末尾のスラッシュを取り除く
 */
$router->removeExtraSlashes(true);

