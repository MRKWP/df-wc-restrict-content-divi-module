<?php
namespace DF\DF_RESTRICT_CONTENT;

use ET_Builder_Module;
use ET_Builder_Element;
use WP_Query;

/**
 *
 */
class WcRestrictContentCloseTagModule extends ET_Builder_Module
{
    public $name = 'Woo Restrict Content Close';
    public $slug = 'df_wc_restrict_content_close_tag';
    public $fields;
    protected $container;

    protected $defaults = array();

    public function __construct($container)
    {
        $this->container = $container;
        $this->initFields();
        parent::__construct();

        $this->defaults = array(
            'shortcode' => 'wcm_restrict',
        );
    }


    /**
     * Initialise the fields.
     */
    private function initFields()
    {
        $fields = array();

        $fields['shortcode'] = array(
                    'label' => esc_html__('WC Membership Restriction Shortcode', 'et_builder'),
                    'type' => 'select',
                    'options' => array(
                        'wcm_restrict' => 'Restrict Plans',
                        'wcm_nonmember' => 'Non-Member',
                    ),
                    'description' => esc_html__('Select the WooCommerce Membership Restriction Shortcode', 'et_builder'),
                    'default' => $this->getDefault('shortcode'),
                );

        $fields['admin_label'] = array(
            'label'       => __('Admin Label', 'et_builder'),
            'type'        => 'text',
            'description' => __('This will change the label of the module in the builder for easy identification.', 'et_builder'),
            );

        $this->fields = $fields;
    }

    /**
     * Init module.
     */
    public function init()
    {
        $this->whitelisted_fields = array_keys($this->fields);

        if (strpos($this->slug, 'et_pb_') !== 0) {
            $this->slug = 'et_pb_'.$this->slug;
        }

        $defaults = array();

        foreach ($this->fields as $field => $options) {
            if (isset($options['default'])) {
                $defaults[$field] = $options['default'];
            }
        }

        $this->field_defaults = $defaults;
    }

    /**
     * Get Fields
     *
     */
    public function get_fields()
    {
        return $this->fields;
    }

    protected function getDefault($name)
    {
        return isset($this->defaults[$name]) ? $this->defaults[$name] : '';
    }


    public function get_main_tabs()
    {
        $tabs = array(
            'general'    => esc_html__('Content', 'et_builder'),
        );

        return apply_filters('et_builder_main_tabs', $tabs);
    }



    /**
     * Shortcode render.
     */
    public function shortcode_callback($atts, $content = null, $function_name)
    {
        $atts = wp_parse_args($atts, $this->defaults);
        $shortcode = $atts['shortcode'];
        return "[/{$shortcode}]";
    }
}
