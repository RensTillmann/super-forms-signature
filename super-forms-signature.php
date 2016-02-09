<?php
/**
 * Super Forms Signature
 *
 * @package   Super Forms Signature
 * @author    feeling4design
 * @link      http://codecanyon.net/item/super-forms-drag-drop-form-builder/13979866
 * @copyright 2015 by feeling4design
 *
 * @wordpress-plugin
 * Plugin Name: Super Forms Signature
 * Plugin URI:  http://codecanyon.net/item/super-forms-drag-drop-form-builder/13979866
 * Description: Adds an extra element that allows users to sign their signature before submitting the form
 * Version:     1.0.0
 * Author:      feeling4design
 * Author URI:  http://codecanyon.net/user/feeling4design
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if(!class_exists('SUPER_Signature')) :


    /**
     * Main SUPER_Signature Class
     *
     * @class SUPER_Signature
     * @version	1.0.0
     */
    final class SUPER_Signature {
    
        
        /**
         * @var string
         *
         *	@since		1.0.0
        */
        public $version = '1.0.0';

        
        /**
         * @var SUPER_Signature The single instance of the class
         *
         *	@since		1.0.0
        */
        protected static $_instance = null;

        
        /**
         * Contains an array of registered script handles
         *
         * @var array
         *
         *	@since		1.0.0
        */
        private static $scripts = array();
        
        
        /**
         * Contains an array of localized script handles
         *
         * @var array
         *
         *	@since		1.0.0
        */
        private static $wp_localize_scripts = array();
        
        
        /**
         * Main SUPER_Signature Instance
         *
         * Ensures only one instance of SUPER_Signature is loaded or can be loaded.
         *
         * @static
         * @see SUPER_Signature()
         * @return SUPER_Signature - Main instance
         *
         *	@since		1.0.0
        */
        public static function instance() {
            if(is_null( self::$_instance)){
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        
        /**
         * SUPER_Signature Constructor.
         *
         *	@since		1.0.0
        */
        public function __construct(){
            $this->init_hooks();
            do_action('super_signature_loaded');
        }

        
        /**
         * Define constant if not already set
         *
         * @param  string $name
         * @param  string|bool $value
         *
         *	@since		1.0.0
        */
        private function define($name, $value){
            if(!defined($name)){
                define($name, $value);
            }
        }

        
        /**
         * What type of request is this?
         *
         * string $type ajax, frontend or admin
         * @return bool
         *
         *	@since		1.0.0
        */
        private function is_request($type){
            switch ($type){
                case 'admin' :
                    return is_admin();
                case 'ajax' :
                    return defined( 'DOING_AJAX' );
                case 'cron' :
                    return defined( 'DOING_CRON' );
                case 'frontend' :
                    return (!is_admin() || defined('DOING_AJAX')) && ! defined('DOING_CRON');
            }
        }

        
        /**
         * Hook into actions and filters
         *
         *	@since		1.0.0
        */
        private function init_hooks() {
            
            // Filters since 1.0.0

            // Actions since 1.0.0
            add_filter( 'super_shortcodes_after_form_elements_filter', array( $this, 'add_signature_element' ), 10, 2 );

            if ( $this->is_request( 'frontend' ) ) {
                
                // Filters since 1.0.0
            	add_filter( 'super_form_styles_filter', array( $this, 'add_element_styles' ), 10, 2 );

                // Actions since 1.0.0
                
            }
            
            if ( $this->is_request( 'admin' ) ) {
                
                // Filters since 1.0.0
                add_filter( 'super_enqueue_styles', array( $this, 'add_stylesheet' ), 10, 1 );
                add_filter( 'super_enqueue_scripts', array( $this, 'add_scripts' ), 10, 1 );
                add_filter( 'super_form_styles_filter', array( $this, 'add_element_styles' ), 10, 2 );

                // Actions since 1.0.0
                add_action( 'super_before_load_form_dropdown_hook', array( $this, 'add_ready_to_use_forms' ) );
                add_action( 'super_after_load_form_dropdown_hook', array( $this, 'add_ready_to_use_forms_json' ) );

            }
            
            if ( $this->is_request( 'ajax' ) ) {

                // Filters since 1.0.0

                // Actions since 1.0.0

            }
            
        }

        
        /**
         * Hook into the load form dropdown and add some ready to use forms
         *
         *  @since      1.0.0
        */
        public static function add_ready_to_use_forms() {
            $html = '<option value="signature-email">Signature - Subscribe email address only</option>';
            $html .= '<option value="signature-name">Signature - Subscribe with first and last name</option>';
            $html .= '<option value="signature-interests">Signature - Subscribe with interests</option>';
            echo $html;
        }


        /**
         * Hook into the after load form dropdown and add the json of the ready to use forms
         *
         *  @since      1.0.0
        */
        public static function add_ready_to_use_forms_json() {
            $html  = '<textarea hidden name="signature-email">';
            $html .= '[{"tag":"text","group":"form_elements","inner":"","data":{"name":"email","email":"Email","label":"","description":"","placeholder":"Your Email Address","tooltip":"","validation":"email","error":"","grouped":"0","maxlength":"0","minlength":"0","width":"0","exclude":"0","error_position":"","icon_position":"outside","icon_align":"left","icon":"envelope","conditional_action":"disabled","conditional_trigger":"all","conditional_items":[{"field":"name","logic":"contains","value":""}]}},{"tag":"column","group":"layout_elements","inner":[{"tag":"text","group":"form_elements","inner":"","data":{"name":"first_name","email":"First name:","label":"","description":"","placeholder":"Your First Name","tooltip":"","validation":"empty","error":"","grouped":"0","maxlength":"0","minlength":"0","width":"0","exclude":"0","error_position":"","icon_position":"outside","icon_align":"left","icon":"user","conditional_action":"disabled","conditional_trigger":"all","conditional_items":[{"field":"email","logic":"contains","value":""}]}}],"data":{"size":"1/2","margin":"","conditional_action":"disabled"}},{"tag":"column","group":"layout_elements","inner":[{"tag":"text","group":"form_elements","inner":"","data":{"name":"last_name","email":"Last name:","label":"","description":"","placeholder":"Your Last Name","tooltip":"","validation":"empty","error":"","grouped":"0","maxlength":"0","minlength":"0","width":"0","exclude":"0","error_position":"","icon_position":"outside","icon_align":"left","icon":"user","conditional_action":"disabled","conditional_trigger":"all","conditional_items":[{"field":"email","logic":"contains","value":""}]}}],"data":{"size":"1/2","margin":"","conditional_action":"disabled"}},{"tag":"signature","group":"form_elements","inner":"","data":{"list_id":"53e03de9e1","display_interests":"yes","send_confirmation":"yes","email":"","label":"Interests","description":"Select one or more interests","tooltip":"","validation":"empty","error":"","maxlength":"0","minlength":"0","display":"horizontal","grouped":"0","width":"0","exclude":"2","error_position":"","icon_position":"inside","icon_align":"left","icon":"star","conditional_action":"disabled","conditional_trigger":"all","conditional_items":[{"field":"email","logic":"contains","value":""}]}}]';
            $html .= '</textarea>';

            $html .= '<textarea hidden name="signature-name">';
            $html .= '[{"tag":"text","group":"form_elements","inner":"","data":{"name":"email","email":"Email","label":"","description":"","placeholder":"Your Email Address","tooltip":"","validation":"email","error":"","grouped":"0","maxlength":"0","minlength":"0","width":"0","exclude":"0","error_position":"","icon_position":"outside","icon_align":"left","icon":"envelope","conditional_action":"disabled","conditional_trigger":"all","conditional_items":[{"field":"name","logic":"contains","value":""}]}},{"tag":"column","group":"layout_elements","inner":[{"tag":"text","group":"form_elements","inner":"","data":{"name":"first_name","email":"First name:","label":"","description":"","placeholder":"Your First Name","tooltip":"","validation":"empty","error":"","grouped":"0","maxlength":"0","minlength":"0","width":"0","exclude":"0","error_position":"","icon_position":"outside","icon_align":"left","icon":"user","conditional_action":"disabled","conditional_trigger":"all","conditional_items":[{"field":"email","logic":"contains","value":""}]}}],"data":{"size":"1/2","margin":"","conditional_action":"disabled"}},{"tag":"column","group":"layout_elements","inner":[{"tag":"text","group":"form_elements","inner":"","data":{"name":"last_name","email":"Last name:","label":"","description":"","placeholder":"Your Last Name","tooltip":"","validation":"empty","error":"","grouped":"0","maxlength":"0","minlength":"0","width":"0","exclude":"0","error_position":"","icon_position":"outside","icon_align":"left","icon":"user","conditional_action":"disabled","conditional_trigger":"all","conditional_items":[{"field":"email","logic":"contains","value":""}]}}],"data":{"size":"1/2","margin":"","conditional_action":"disabled"}},{"tag":"signature","group":"form_elements","inner":"","data":{"list_id":"53e03de9e1","display_interests":"yes","send_confirmation":"yes","email":"","label":"Interests","description":"Select one or more interests","tooltip":"","validation":"empty","error":"","maxlength":"0","minlength":"0","display":"horizontal","grouped":"0","width":"0","exclude":"2","error_position":"","icon_position":"inside","icon_align":"left","icon":"star","conditional_action":"disabled","conditional_trigger":"all","conditional_items":[{"field":"email","logic":"contains","value":""}]}}]';
            $html .= '</textarea>';

            $html .= '<textarea hidden name="signature-interests">';
            $html .= '[{"tag":"text","group":"form_elements","inner":"","data":{"name":"email","email":"Email","label":"","description":"","placeholder":"Your Email Address","tooltip":"","validation":"email","error":"","grouped":"0","maxlength":"0","minlength":"0","width":"0","exclude":"0","error_position":"","icon_position":"outside","icon_align":"left","icon":"envelope","conditional_action":"disabled","conditional_trigger":"all","conditional_items":[{"field":"name","logic":"contains","value":""}]}},{"tag":"column","group":"layout_elements","inner":[{"tag":"text","group":"form_elements","inner":"","data":{"name":"first_name","email":"First name:","label":"","description":"","placeholder":"Your First Name","tooltip":"","validation":"empty","error":"","grouped":"0","maxlength":"0","minlength":"0","width":"0","exclude":"0","error_position":"","icon_position":"outside","icon_align":"left","icon":"user","conditional_action":"disabled","conditional_trigger":"all","conditional_items":[{"field":"email","logic":"contains","value":""}]}}],"data":{"size":"1/2","margin":"","conditional_action":"disabled"}},{"tag":"column","group":"layout_elements","inner":[{"tag":"text","group":"form_elements","inner":"","data":{"name":"last_name","email":"Last name:","label":"","description":"","placeholder":"Your Last Name","tooltip":"","validation":"empty","error":"","grouped":"0","maxlength":"0","minlength":"0","width":"0","exclude":"0","error_position":"","icon_position":"outside","icon_align":"left","icon":"user","conditional_action":"disabled","conditional_trigger":"all","conditional_items":[{"field":"email","logic":"contains","value":""}]}}],"data":{"size":"1/2","margin":"","conditional_action":"disabled"}},{"tag":"signature","group":"form_elements","inner":"","data":{"list_id":"53e03de9e1","display_interests":"yes","send_confirmation":"yes","email":"","label":"Interests","description":"Select one or more interests","tooltip":"","validation":"empty","error":"","maxlength":"0","minlength":"0","display":"horizontal","grouped":"0","width":"0","exclude":"2","error_position":"","icon_position":"inside","icon_align":"left","icon":"star","conditional_action":"disabled","conditional_trigger":"all","conditional_items":[{"field":"email","logic":"contains","value":""}]}}]';
            $html .= '</textarea>';
            echo $html;
        }


        /**
         * Hook into stylesheets of the form and add styles for the signature element
         *
         *  @since      1.0.0
        */
        public static function add_element_styles( $styles, $attributes ) {
            $s = '.super-form-'.$attributes['id'].' ';
            $v = $attributes['settings'];
            $styles .= $s.'.super-signature-canvas {';
    		$styles .= 'border: solid 1px ' . $v['theme_field_colors_border'] . ';';
    		$styles .= 'background-color: ' . $v['theme_field_colors_top'] . ';';
    		$styles .= '}';
            return $styles;
		}



        /**
         * Hook into stylesheets and add signature stylesheet
         *
         *  @since      1.0.0
        */
        public static function add_stylesheet( $array ) {
            $suffix         = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
            $assets_path    = str_replace( array( 'http:', 'https:' ), '', plugin_dir_url( __FILE__ ) ) . '/assets/';
            $frontend_path   = $assets_path . 'css/frontend/';
            $array['super-signature'] = array(
                'src'     => $frontend_path . 'signature' . $suffix . '.css',
                'deps'    => '',
                'version' => SUPER_VERSION,
                'media'   => 'all',
                'screen'  => array( 
                    'super-forms_page_super_create_form'
                ),
                'method'  => 'enqueue',
            );
            return $array;
        }


        /**
         * Hook into scripts and add signature javascripts
         *
         *  @since      1.0.0
        */
        public static function add_scripts( $array ) {

			$suffix         = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
            $assets_path    = str_replace( array( 'http:', 'https:' ), '', plugin_dir_url( __FILE__ ) ) . '/assets/';
            $frontend_path  = $assets_path . 'js/frontend/';
            $array['super-jquery-signature'] = array(
                'src'     => $frontend_path . 'jquery.signature.js',
                'deps'    => array( 'jquery', 'jquery-ui-mouse' ),
                'version' => SUPER_VERSION,
                'footer'  => false,
                'screen'  => array( 
                    'super-forms_page_super_create_form'
                ),
                'method' => 'enqueue'
            );
            $array['super-signature'] = array(
                'src'     => $frontend_path . 'signature' . $suffix . '.js',
                'deps'    => array( 'super-jquery-signature' ),
                'version' => SUPER_VERSION,
                'footer'  => false,
                'screen'  => array( 
                    'super-forms_page_super_create_form'
                ),
                'method' => 'enqueue'
            );
            return $array;
        }


        /**
         * Handle the Signature element output
         *
         *  @since      1.0.0
        */
        public static function signature( $tag, $atts ) {
        	wp_enqueue_style( 'super-signature', plugin_dir_url( __FILE__ ) . 'assets/css/frontend/signature.min.css', array(), SUPER_VERSION );
			wp_enqueue_script( 'super-jquery-signature', plugin_dir_url( __FILE__ ) . 'assets/js/frontend/jquery.signature.js', array( 'jquery', 'jquery-ui-mouse' ), SUPER_VERSION );
			wp_enqueue_script( 'super-signature', plugin_dir_url( __FILE__ ) . 'assets/js/frontend/signature.min.js', array( 'super-jquery-signature' ), SUPER_VERSION );
			$result = SUPER_Shortcodes::opening_tag( $tag, $atts );
	        $result .= SUPER_Shortcodes::opening_wrapper( $atts );
	        if( ( !isset( $atts['value'] ) ) || ( $atts['value']=='' ) ) {
	            $atts['value'] = '';
	        }else{
	            $atts['value'] = SUPER_Common::email_tags( $atts['value'] );
	        }
	        $styles = '';
	        if( !isset( $atts['height'] ) ) $atts['height'] = 100;
	        if( !isset( $atts['bg_size'] ) ) $atts['bg_size'] = 75;
	        if( !isset( $atts['background_img'] ) ) $atts['background_img'] = 0;
	        $image = wp_prepare_attachment_for_js( $atts['background_img'] );
	        $styles .= 'height:' . $atts['height'] . 'px;';
	        $styles .= 'background-image:url(\'' . $image['url'] . '\');';
	        $styles .= 'background-size:' . $atts['bg_size'] . 'px;';
	        $result .= '<div class="super-signature-canvas" style="' . $styles . '"></div>';
	        $result .= '<span class="super-signature-clear"></span>';
	        $result .= '<textarea style="display:none;" class="super-shortcode-field"';
	        $result .= ' name="' . $atts['name'] . '"';
	        $result .= ' data-thickness="' . $atts['thickness'] . '"';
	        $result .= SUPER_Shortcodes::common_attributes( $atts, $tag );
	        $result .= ' />' . $atts['value'] . '</textarea>';
	        $result .= '</div>';
	        $result .= SUPER_Shortcodes::loop_conditions( $atts );
	        $result .= '</div>';
	        return $result;
        }


        /**
         * Hook into elements and add Signature element
         * This element specifies the Signature List by it's given ID and retrieves it's Groups
         *
         *  @since      1.0.0
        */
        public static function add_signature_element( $array, $attributes ) {

            // Include the predefined arrays
            require( SUPER_PLUGIN_DIR . '/includes/shortcodes/predefined-arrays.php' );

	        $array['form_elements']['shortcodes']['signature'] = array(
	            'callback' => 'SUPER_Signature::signature',
	            'name' => __( 'Signature', 'super' ),
	            'icon' => 'pencil-square-o',
	            'atts' => array(
	                'general' => array(
	                    'name' => __( 'General', 'super' ),
	                    'fields' => array(
	                        'name' => SUPER_Shortcodes::name( $attributes, $default='signature' ),
	                        'email' => SUPER_Shortcodes::email( $attributes, $default='Signature' ),
	                        'label' => $label,
	                        'description'=>$description,
	                        'thickness' => SUPER_Shortcodes::width( $attributes=null, $default=2, $min=1, $max=20, $steps=1, $name=__( 'Line Thickness', 'super' ), $desc=__( 'The thickness of the signature when drawing', 'super' ) ),
	                        'background_img' => array(
				                'name' => __( 'Custom sign here image', 'super' ),
				                'desc' => __( 'Background image to show the user they can draw a signature', 'super' ),
				                'default' => SUPER_Settings::get_value( 1, 'background_img', null, '' ),
				                'type' => 'image',
				            ),
	                        'bg_size' => SUPER_Shortcodes::width( $attributes=null, $default=75, $min=0, $max=1000, $steps=10, $name=__( 'Image background size', 'super' ), $desc=__( 'You can adjust the size of your background image here', 'super' ) ),
				            'tooltip' => $tooltip,
	                        'error' => $error,
	                    ),
	                ),
	                'advanced' => array(
	                    'name' => __( 'Advanced', 'super' ),
	                    'fields' => array(
	                        'grouped' => $grouped,
	                        'width' => SUPER_Shortcodes::width( $attributes=null, $default=350, $min=0, $max=600, $steps=10, $name=null, $desc=null ),
	                        'height' => SUPER_Shortcodes::width( $attributes=null, $default=100, $min=0, $max=600, $steps=10, $name=null, $desc=null ),
	                        'exclude' => $exclude,
	                        'error_position' => $error_position,
	                    ),
	                ),
	                'icon' => array(
	                    'name' => __( 'Icon', 'super' ),
	                    'fields' => array(
	                        'icon_position' => $icon_position,
	                        'icon_align' => $icon_align,
	                        'icon' => SUPER_Shortcodes::icon( $attributes, 'pencil' ),
	                    ),
	                ),
	                'conditional_logic' => $conditional_logic_array
	            ),
	        );
            return $array;
        }
    }
        
endif;


/**
 * Returns the main instance of SUPER_Signature to prevent the need to use globals.
 *
 * @return SUPER_Signature
 */
function SUPER_Signature() {
    return SUPER_Signature::instance();
}


// Global for backwards compatibility.
$GLOBALS['SUPER_Signature'] = SUPER_Signature();