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

$router->add(
    '/tag/:params',
    [
        'controller' => 'entry',
        'action' => 'tagArticle',
        'tag' => 1
    ]
);

$router->add(
    '/category/:params',
    [
        'controller' => 'entry',
        'action' => 'categoryArticle',
        'category' => 1
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

/**
 *  /entry/update/:int
 */
$router->addPost(
    '/entry/update/:int',
    [
        'controller' => 'entry',
        'action' => 'update',
        'id' => 1
    ]
);

/**
 *  /entry/delete/:int
 */
$router->addPost(
    '/entry/delete/:int',
    [
        'controller' => 'entry',
        'action' => 'delete',
        'id' => 1
    ]
);

/**
 *  /entry/comment/:int
 */
$router->addPost(
    '/entry/comment/:int',
    [
        'controller' => 'comment',
        'action' => 'set',
        'id' => 1
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

