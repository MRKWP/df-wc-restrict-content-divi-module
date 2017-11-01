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
        echo  "<script>" .
            "localStorage.removeItem('et_pb_templates_et_pb_df_wc_restrict_content_open_tag');".
            "localStorage.removeItem('et_pb_templates_et_pb_df_wc_restrict_content_close_tag');".
            "</script>";
    }



    /**
     * Register license.
     */
    public function registerLicense()
    {
        // License check.
        // License setup.
        // Load the API Key library if it is not already loaded. Must be placed in the root plugin file.
        if (! class_exists('AM_License_Menu')) {
            require_once($this['plugin_dir'] . '/am-license-menu.php');
        }

        /**
         * @param string $file             Must be __FILE__ from the root plugin file, or theme functions file.
         * @param string $software_title   Must be exactly the same as the Software Title in the product.
         * @param string $software_version This product's current software version.
         * @param string $plugin_or_theme  'plugin' or 'theme'
         * @param string $api_url          The URL to the site that is running the API Manager. Example: https://www.toddlahman.com/
         *
         * @return \AM_License_Submenu|null
         */
        $license = new \AM_License_Menu($this['plugin_file'], $this['plugin_name'], $this['plugin_version'], 'plugin', 'https://www.diviframework.com/', '', '');

        $this['license'] = $license;

        return $license;
    }
}
