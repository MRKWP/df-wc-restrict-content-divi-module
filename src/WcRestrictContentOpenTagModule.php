<?php
namespace DF\DF_RESTRICT_CONTENT;

use ET_Builder_Module;
use ET_Builder_Element;
use WP_Query;

/**
 *
 */
class WcRestrictContentOpenTagModule extends ET_Builder_Module
{
    public $name = 'Woo Restrict Content Open';
    public $slug = 'df_wc_restrict_content_open_tag';
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
            'plans' => '',
            'delay' => '',
            'start_after_trial' => 'on',
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
                    'tab_slug' => 'general',
                    'toggle_slug' => 'shortcode',
                    'description' => esc_html__('Select the WooCommerce Membership Restriction Shortcode', 'et_builder'),
                    'affects' => array(
                        'delay',
                        'start_after_trial',
                    ),
                    'default' => $this->getDefault('shortcode'),
                );

        $fields['plans'] = array(
                    'label' => esc_html__('Plans', 'et_builder'),
                    'type' => 'text',
                    'tab_slug' => 'general',
                    'toggle_slug' => 'shortcode',
                    'description' => esc_html__('Enter the plan slugs or IDs to limit the wrapped content to certain members', 'et_builder'),
                    'affects' => array(),
                    'default' => $this->getDefault('plans'),
                );

        $fields['delay'] = array(
                    'label' => esc_html__('Delay', 'et_builder'),
                    'type' => 'text',
                    'tab_slug' => 'general',
                    'toggle_slug' => 'shortcode',
                    'description' => esc_html__('Enter the delays access to the wrapped content by a certain time, or makes it available on a particular date', 'et_builder'),
                    'affects' => array(),
                    'depends_show_if' => 'wcm_restrict',
                    'default' => $this->getDefault('delay'),
                );

        $fields['start_after_trial'] = array(
                    'label' => esc_html__('Start After Trial', 'et_builder'),
                    'type' => 'yes_no_button',
                    'options' => array(
                        'off' => esc_html__('No', 'et_builder'),
                        'on' => esc_html__('Yes', 'et_builder'),
                    ),
                    'tab_slug' => 'general',
                    'toggle_slug' => 'shortcode',
                    'description' => esc_html__('When selected, delays access to the wrapped content until a trial period is over (when WooCommerce Subscriptions is in use)', 'et_builder'),
                    'affects' => array(),
                    'depends_show_if' => 'wcm_restrict',
                    'default' => $this->getDefault('start_after_trial'),
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


        $this->options_toggles = array(
            'general' => array(
                    'settings' => array(
                        'toggles_disabled' => true,
                    ),
                    'toggles' => array(
                        'shortcode' => esc_html__('Shortcode', 'et_builder'),
                    ),
                ),
        );
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


        $attributes = array();
        $shortcode = $atts['shortcode'];

        switch ($atts['shortcode']) {
            case 'wcm_restrict':
                if ($atts['plans']) {
                    $attributes['plans'] = $atts['plans'];
                }

                if ($atts['delay']) {
                    $attributes['delay'] = $atts['delay'];
                }
                
                if ($atts['start_after_trial']) {
                    $atts['start_after_trial'] = ($atts['start_after_trial'] == 'on') ? 'yes' : 'no';
                }
                break;

            case 'wcm_nonmember':
                if ($atts['plans']) {
                    $attributes['plans'] = $atts['plans'];
                }
                break;
            
            default:
                # code...
                break;
        }

        if (empty($attributes)) {
            return "[{$shortcode}]";
        } else {
            $serialized_attributes = '';

            foreach ($attributes as $key => $value) {
                $serialized_attributes .= sprintf(' %s="%s"', $key, $value);
            }
            
            return sprintf('[%s %s]', $shortcode, $serialized_attributes);
        }
    }
}
