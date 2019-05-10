<?php
namespace DF\DF_RESTRICT_CONTENT;

use Pimple\Container as PimpleContainer;

/**
 * DI Container.
 */
class Container extends PimpleContainer
{

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->initObjects();
    }

    /**
     * Define dependancies.
     */
    public function initObjects()
    {
        $this['activation'] = function ($container) {
            return new Activation($container);
        };

        $this['divi_modules'] = function ($container) {
            return new DiviModules($container);
        };

        $this['plugins'] = function ($container) {
            return new Plugins($container);
        };

        $this['themes'] = function ($container) {
            return new Themes($container);
        };
    }

    /**
     * Start the plugin
     */
    public function run()
    {
        // divi module register.
        add_action('et_builder_ready', array($this['divi_modules'], 'register'), 1);

        add_filter('the_content', array($this, 'the_content_wcm_restrict_content'), 10000);

        // check for dependancies.
        add_action('plugins_loaded', array($this['plugins'], 'checkDependancies'));
        add_action('plugins_loaded', array($this['themes'], 'checkDependancies'));

        add_action('admin_head', array($this, 'flushLocalStorage'));
    }

    /**
     * Process the shortcode.
     */
    public function the_content_wcm_restrict_content($content)
    {
        if (strpos($content, '[/wcm_restrict]') || strpos($content, '[/wcm_nonmember]')) {
            $content = do_shortcode($content);
        }
        return $content;
    }

    /**
     * Flush local storage items.
     *
     * @return [type] [description]
     */
    public function flushLocalStorage()
    {
        echo "<script>" .
        "localStorage.removeItem('et_pb_templates_et_pb_df_wc_restrict_content_open_tag');" .
        "localStorage.removeItem('et_pb_templates_et_pb_df_wc_restrict_content_close_tag');" .
        "</script>";
    }

}
