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
    array(
        'controller' => 'register',
        'action' => 'signup'
    )
);
/**
 *  末尾のスラッシュを取り除く
 */
$router->removeExtraSlashes(true);

