<?php
/**
 * Created by PhpStorm.
 * User: richa
 * Date: 16.11.2016
 * Time: 10.16
 */

namespace Richard87\TwigSass;


use Leafo\ScssPhp\Compiler;
class RenderFunction extends \Twig_Extension
{
    private $compiler;

    /**
     * RenderFunction constructor.
     * @param Compiler $compiler
     * @param $importRootDir
     * @param string $sasFormater Leafo\ScssPhp\Formatter\Expanded | Leafo\ScssPhp\Formatter\Nested (default) | Leafo\ScssPhp\Formatter\Compressed | Leafo\ScssPhp\Formatter\Compact | Leafo\ScssPhp\Formatter\Crunched
     */
    public function __construct(Compiler $compiler, $importRootDir = null, $sasFormater = 'Leafo\ScssPhp\Formatter\Nested')
    {
        $this->compiler = $compiler;
        $this->compiler->addImportPath($importRootDir);
        $this->compiler->setFormatter($sasFormater);
    }


    public function getFilters()
    {
        return array(
            new \Twig_SimpleFunction('renderSass', array($this, 'renderSass'), array('needs_context' => true, 'needs_environment' => true, 'is_safe' => true)),
        );
    }

    public function renderSass(\Twig_Environment $twig,$context, $template) {
        // TODO: Check if it is already in cache

        // Render twig file
        $renderedFile = $twig->render($template,$context);

        // Render sass
        $sass = $this->compiler->compile($renderedFile);

        // TODO: save to catche

        // serve the file
        return $sass;
    }

    public function getName()
    {
        return 'twig_sass_renderer';
    }
}