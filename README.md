# twig-sass
A Twig extension/function to render inline SASS files for SEO purposes or to render CSS files with easy customizability

## Usage:

This is an example for Symfony's `services.yml`:
    
    app.sass_compiler:
        class: Leafo\ScssPhp\Compiler

    app.twig.sass_renderer:
        class: Richard87\TwigSass\RenderFunction
        arguments: ['@app.sass_compiler','@cache.app','%kernel.root_dir%/../web','Leafo\ScssPhp\Formatter\Expanded']
        tags:
          - { name: twig.extension}
          
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