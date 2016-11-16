<?php

require_once "../vendor/autoload.php";
include "NullCache.php";

$twig = new Twig_Environment(new Twig_Loader_Filesystem());
$renderFunction = new \Richard87\TwigSass\RenderFunction(new \Leafo\ScssPhp\Compiler(),new Richard87\NullCache\NullCachePool());
$twig->addExtension($renderFunction);

echo $twig->render("index.html.twig",['name' => 'world']);

