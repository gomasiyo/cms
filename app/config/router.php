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
 *  /auth/login
 */
$router->addPost(
    '/entry/add',
    [
        'controller' => 'entry',
        'action' => 'add'
    ]
);

/**
 *  末尾のスラッシュを取り除く
 */
$router->removeExtraSlashes(true);

