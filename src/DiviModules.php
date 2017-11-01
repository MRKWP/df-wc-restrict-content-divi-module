<?php
namespace DF\DF_RESTRICT_CONTENT;

/**
 * Register divi modules
 */
class DiviModules
{
    
    protected $container;


    public function __construct($container)
    {
        $this->container = $container;
    }



    /**
     * Register divi modules.
     */
    public function register()
    {
        new WcRestrictContentOpenTagModule($this->container);
        new WcRestrictContentCloseTagModule($this->container);
    }
}
