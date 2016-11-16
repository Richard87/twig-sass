<?php

require_once "../vendor/autoload.php";
include "NullCache.php";

class Loader implements Twig_LoaderInterface {
    public function getSource($name){return file_get_contents($name);}
    public function getCacheKey($name){return $name;}
    public function isFresh($name, $time){return true;}
}

$twig = new Twig_Environment(new Loader());

$renderFunction = new \Richard87\TwigSass\RenderFunction(new \Leafo\ScssPhp\Compiler(),new Richard87\NullCache\NullCachePool());
$twig->addExtension($renderFunction);

echo $twig->render("index.html.twig",['name' => 'world']);

