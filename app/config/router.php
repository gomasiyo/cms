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

$router->add(
    '/entry/:int',
    [
        'controller' => 'entry',
        'action' => 'article',
        'id' => 1
    ]
);

/**
 *  /entry/all
 */
$router->add(
    '/entry/all',
    [
        'controller' => 'entry',
        'action' => 'all'
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

$router->notFound(
    array(
        'controller' => 'status',
        'action' => 'code404'
    )
);


/**
 *  末尾のスラッシュを取り除く
 */
$router->removeExtraSlashes(true);

