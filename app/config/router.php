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
        'controller' => 'Index',
        'action' => 'index'
    )
);
/**
 *  末尾のスラッシュを取り除く
 */
$router->removeExtraSlashes(true);

