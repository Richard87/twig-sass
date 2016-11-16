# twig-sass
A Twig extension/function to render inline SASS files for SEO purposes. You can also use twig variables *inside* your sass files.

## Setup:

Add `RenderFunction` to Twig:

    $twig = new Twig_Environment(new Twig_Loader_Filesystem());
    $renderFunction = new \Richard87\TwigSass\RenderFunction(
        new \Leafo\ScssPhp\Compiler(),
        new Richard87\NullCache\NullCachePool()
        );
    $twig->addExtension($renderFunction);


Or if you are using Symfony, add this to `services.yml`:
    
    app.sass_compiler:
        class: Leafo\ScssPhp\Compiler

    app.twig.sass_renderer:
        class: Richard87\TwigSass\RenderFunction
        arguments: ['@app.sass_compiler','@cache.app']
        tags:
          - { name: twig.extension}
          
## Options

`RenderFunction` takes 4 arguments:
  - `Leafo\ScssPhp\Compiler`: does the actual compilation of your sass file
  - `PSR6 Cache Pool`: A PSR6 enabled Cache pool is requried for any useful performance
  - `importDir`: The location SCSS should look for libraries, usually where your `node_modules` are (mine is in `web`)
  - `sassFormater`: How your output should look like. Takes a classname in string format. Can be any of these values:
    - "Leafo\ScssPhp\Formatter\Expanded"
    - "Leafo\ScssPhp\Formatter\Nested"
    - "Leafo\ScssPhp\Formatter\Compressed"
    - "Leafo\ScssPhp\Formatter\Compact"
    - "Leafo\ScssPhp\Formatter\Crunched"
          
## Usage
          
Then render inline styles like this:

    <style>
        {{ renderScss("styles.scss.twig") }}
    </style>
    
styles.scss.twig:

    $brand-primary: #59cc4a;
    $border-radius: 0;
    $border-radius-lg: 0;
    $border-radius-sm: 0;
    @import "node_modules/bootstrap/scss/bootstrap";
    
Notice that `node_modules` is inside the web directory, but that can be easily changed with the `importRootDir` variable.