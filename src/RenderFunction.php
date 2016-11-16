<?php
/**
 * Created by PhpStorm.
 * User: richa
 * Date: 16.11.2016
 * Time: 10.16
 */

namespace Richard87\TwigSass;


use Leafo\ScssPhp\Compiler;
use Psr\Cache\CacheItemPoolInterface;

class RenderFunction extends \Twig_Extension
{
    private $compiler;
    private $cache;

    /**
     * RenderFunction constructor.
     * @param Compiler $compiler
     * @param $importRootDir
     * @param string $sasFormater Leafo\ScssPhp\Formatter\Expanded | Leafo\ScssPhp\Formatter\Nested (default) | Leafo\ScssPhp\Formatter\Compressed | Leafo\ScssPhp\Formatter\Compact | Leafo\ScssPhp\Formatter\Crunched
     * @param CacheItemPoolInterface $cache
     */
    public function __construct(Compiler $compiler, $importRootDir = null, $sasFormater = 'Leafo\ScssPhp\Formatter\Nested',CacheItemPoolInterface $cache)
    {
        $this->compiler = $compiler;
        $this->compiler->addImportPath($importRootDir);
        $this->compiler->setFormatter($sasFormater);
        $this->cache = $cache;
    }


    public function getFilters()
    {
        return array(
            new \Twig_SimpleFunction('renderSass', array($this, 'renderSass'), array('needs_context' => true, 'needs_environment' => true, 'is_safe' => true)),
        );
    }

    public function renderSass(\Twig_Environment $twig,$context, $template) {
        // Check if it is already in cache
        $cacheKey = $template . sha1(json_encode($context));
        $item = $this->cache->getItem($cacheKey);
        if ($sass = $item->get())
            return $sass;

        // Render twig file
        $renderedFile = $twig->render($template,$context);

        // Render sass
        $sass = $this->compiler->compile($renderedFile);

        // save to catche
        $item->set($sass);
        $this->cache->save($item);

        // serve the file
        return $sass;
    }

    public function getName()
    {
        return 'twig_sass_renderer';
    }
}