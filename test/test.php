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
//$twig->addExtension($renderFunction);

$twig->addFunction(new Twig_SimpleFunction('renderSass', function (\Twig_Environment $twig,$context, $template) {
    $compiler = new \Leafo\ScssPhp\Compiler();
    // Render twig file
    // TODO: Does TWIG cache this?!
    $renderedFile = $twig->render($template,$context);


    // Render sass
    $sass = $compiler->compile($renderedFile);


    // serve the file
    return $sass;
}, array('needs_context' => true, 'needs_environment' => true)));

echo $twig->render("index.html.twig",['name' => 'world']);


function renderSass(\Twig_Environment $twig,$context, $template) {

    // Render twig file
    // TODO: Does TWIG cache this?!
    $renderedFile = $twig->render($template,$context);


    // Render sass
    $sass = $this->compiler->compile($renderedFile);


    // serve the file
    return $sass;
}