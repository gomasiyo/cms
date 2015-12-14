<?php

$loader = new \Phalcon\Loader();

/**
 * We're a registering a set of directories taken from the configuration file
 */
$loader->registerDirs(
    array(
        $config->application->controllersDir,
        $config->application->formsDir,
        $config->application->modelsDir
    )
);

/*
// register namespace
$loader->registerNamespaces(
    require($config->application->composerDir . '/autoload_namespaces.php')
);

$loader->registerClasses(
    require($config->application->composerDir . '/autoload_classmap.php')
);
 */

$loader->register();


