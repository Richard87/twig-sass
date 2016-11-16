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
    private $cachePool;

    /**
     * RenderFunction constructor.
     * @param Compiler $compiler
     * @param CacheItemPoolInterface $cachePool
     * @param $importRootDir
     * @param string $sasFormater     Leafo\ScssPhp\Formatter\Expanded
     *                              | Leafo\ScssPhp\Formatter\Nested
     *                              | Leafo\ScssPhp\Formatter\Compressed
     *                              | Leafo\ScssPhp\Formatter\Compact
     *                              | Leafo\ScssPhp\Formatter\Crunched
     */
    public function __construct(Compiler $compiler,CacheItemPoolInterface $cachePool, $importRootDir = null, $sasFormater = 'Leafo\ScssPhp\Formatter\Crunched')
    {
        $this->compiler = $compiler;
        $this->compiler->addImportPath($importRootDir);
        $this->compiler->setFormatter($sasFormater);
        $this->cachePool = $cachePool;
    }


    /**
     * @return \Twig_SimpleFunction[]
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('renderSass', array($this, 'renderSass'), array('needs_context' => true, 'needs_environment' => true,'is_safe' => array('html'))),
        );
    }

    public function renderSass(\Twig_Environment $twig,$context, $template) {

        // Render twig file
        // TODO: Does TWIG cache this?!
        $renderedFile = $twig->render($template,$context);


        // Check if SASS is already in cache
        $cacheKey = $template . "_" . sha1($renderedFile);
        $cacheItem = $this->cachePool->getItem($cacheKey);
        if ($cacheItem->isHit())
            return $cacheItem->getKey();

        // Render sass
        $sass = $this->compiler->compile($renderedFile);

        // Save the rendered sass file to cache
        $cacheItem->set($sass);
        $this->cachePool->save($cacheItem);

        // serve the file
        return $sass;
    }

    public function getName()
    {
        return 'richard87_twig_sass_render_sass';
    }
}