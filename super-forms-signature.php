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
            do_action('SUPER_Signature_loaded');
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

                // Actions since 1.0.0
                
            }
            
            if ( $this->is_request( 'admin' ) ) {
                
                // Filters since 1.0.0
                add_filter( 'super_settings_after_smtp_server_filter', array( $this, 'add_signature_settings' ), 10, 2 );
                add_filter( 'super_enqueue_styles', array( $this, 'add_stylesheet' ), 10, 1 );

                // Actions since 1.0.0
                add_action( 'super_before_load_form_dropdown_hook', array( $this, 'add_ready_to_use_forms' ) );
                add_action( 'super_after_load_form_dropdown_hook', array( $this, 'add_ready_to_use_forms_json' ) );

            }
            
            if ( $this->is_request( 'ajax' ) ) {

                // Filters since 1.0.0

                // Actions since 1.0.0
                add_action( 'super_before_sending_email_hook', array( $this, 'update_signature_subscribers' ) );

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
         * Hook into elements and add Signature element
         * This element specifies the Signature List by it's given ID and retrieves it's Groups
         *
         *  @since      1.0.0
        */
        public static function add_stylesheet( $array ) {
            $suffix         = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
            $assets_path    = str_replace( array( 'http:', 'https:' ), '', plugin_dir_url( __FILE__ ) ) . '/assets/';
            $backend_path   = $assets_path . 'css/backend/';
            $array['super-signature'] = array(
                'src'     => $backend_path . 'signature' . $suffix . '.css',
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
         * Handle the Signature element output
         *
         *  @since      1.0.0
        */
        public static function signature( $tag, $atts ) {
            if( !isset( $atts['display_interests'] ) ) $atts['display_interests'] = 'no';

            if( $atts['display_interests']=='no' ) {
                $atts['label'] = '';
                $atts['description'] = '';
                $atts['icon'] = '';
            }

            $tag = 'checkbox';
            $classes = ' display-' . $atts['display'];
            $result = SUPER_Shortcodes::opening_tag( $tag, $atts, $classes );

            $show_hidden_field = true;

            // Retrieve groups based on the given List ID:
            $settings = get_option('super_settings');

            // Check if the API key has been set
            if( ( !isset( $settings['signature_key'] ) ) || ( $settings['signature_key']=='' ) ) {
                $show_hidden_field = false;
                $result .= '<strong style="color:red;">Please setup your API key in (Super Forms > Settings > Signature)</strong>';
            }else{
                if( ( !isset( $atts['list_id'] ) ) || ( $atts['list_id']=='' ) ) {
                    $show_hidden_field = false;
                    $result .= '<strong style="color:red;">Please enter your List ID and choose wether or not to retrieve Groups based on your List.</strong>';
                }else{
                    $list_id = sanitize_text_field( $atts['list_id'] );
                    $api_key = $settings['signature_key'];
                    $datacenter = explode('-', $api_key);
                    if( !isset( $datacenter[1] ) ) {
                		$result .= '<strong style="color:red;">Your API key seems to be invalid</strong>';
                    }else{
                    	if( $atts['display_interests']=='yes' ) {
	                        $datacenter = $datacenter[1];
	                        $endpoint = 'https://' . $datacenter . '.api.signature.com/3.0/';
	                        $request = 'lists/' . $list_id . '/interest-categories/';
	                        $url = $endpoint . $request;
	                        $ch = curl_init();
	                        curl_setopt( $ch, CURLOPT_URL, $url );
	                        curl_setopt( $ch, CURLOPT_HTTPHEADER, array( 'content-type: application/json' ) );
	                        curl_setopt( $ch, CURLOPT_USERPWD, 'anystring:' . $api_key ); 
	                        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
	                        curl_setopt( $ch, CURLOPT_ENCODING, '' );
	                        $output = curl_exec( $ch );
	                        $output = json_decode( $output );
	                        if( ( isset( $output->status ) ) && ( $output->status==401 ) ) {
								$result .= '<strong style="color:red;">' . $output->detail . '</strong>';
	                        }else{
		                        if( !isset( $output->categories ) ) {
		                            $result .= '<strong style="color:red;">The List ID seems to be invalid, please make sure you entered to correct List ID.</strong>';
		                        }else{
		                            $result .= SUPER_Shortcodes::opening_wrapper( $atts );
		                            foreach( $output->categories as $k => $v ) {
		                                $request = $request . $v->id . '/interests/';
		                                $url = $endpoint.$request;
		                                $ch = curl_init();
		                                curl_setopt( $ch, CURLOPT_URL, $url );
		                                curl_setopt( $ch, CURLOPT_HTTPHEADER, array( 'content-type: application/json' ) );
		                                curl_setopt( $ch, CURLOPT_USERPWD, 'anystring:' . $api_key ); 
		                                curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		                                curl_setopt( $ch, CURLOPT_ENCODING, '' );
		                                $output = curl_exec( $ch );
		                                $output = json_decode( $output );
		                                foreach( $output->interests as $ik => $iv ) {
		                                    $result .= '<label><input type="checkbox" value="' . esc_attr( $iv->id ) . '" />' . $iv->name . '</label>';
		                                }
		                            }
		                            $result .= '<input class="super-shortcode-field" type="hidden"';
		                            $result .= ' name="signature_interests" value=""';
		                            $result .= SUPER_Shortcodes::common_attributes( $atts, $tag );
		                            $result .= ' />';
		                            $result .= '</div>';
		                        }
	                    	}
	                    }
	                }
                }
            }
            $result .= SUPER_Shortcodes::loop_conditions( $atts );
            $result .= '</div>';

            // Add the hidden fields
            if( $atts['send_confirmation']=='yes' ) {
                $atts['label'] = '';
                $atts['description'] = '';
                $atts['icon'] = '';
                $classes = ' hidden';
                $result .= SUPER_Shortcodes::opening_tag( 'hidden', $atts, $classes );
                $result .= '<input class="super-shortcode-field" type="hidden" value="1" name="signature_send_confirmation" data-exclude="2" />';
                $result .= '</div>';
            }
            if( $show_hidden_field==true ) {
                $atts['label'] = '';
                $atts['description'] = '';
                $atts['icon'] = '';
                $classes = ' hidden';
                $result .= SUPER_Shortcodes::opening_tag( 'hidden', $atts, $classes );
                $result .= '<input class="super-shortcode-field" type="hidden" value="' . $list_id . '" name="signature_list_id" data-exclude="2" />';
                $result .= '</div>';
            }

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
            require(SUPER_PLUGIN_DIR.'/includes/shortcodes/predefined-arrays.php' );

            $array['form_elements']['shortcodes']['signature'] = array(
                'callback' => 'SUPER_Signature::signature',
                'name' => __( 'Signature', 'super' ),
                'icon' => 'signature',
                'atts' => array(
                    'general' => array(
                        'name' => __( 'General', 'super' ),
                        'fields' => array(
                            'list_id' => array(
                                'name'=>__( 'Signature List ID', 'super' ), 
                                'desc'=>__( 'Your List ID for example: 9e67587f52', 'super' ),
                                'default'=> (!isset($attributes['list_id']) ? '' : $attributes['list_id']),
                                'required'=>true, 
                            ),
                            'display_interests' => array(
                                'name'=>__( 'Display interests', 'super' ),
                                'desc'=>__( 'Allow users to select one or more interests (retrieved by given List ID)', 'super' ),
                                'type' => 'select',
                                'default'=> (!isset($attributes['interests']) ? 'no' : $attributes['interests']),
                                'values' => array(
                                    'no' => __( 'No', 'super' ), 
                                    'yes' => __( 'Yes', 'super' ), 
                                ),
                            ),
                            'send_confirmation' => array(
                                'name'=>__( 'Send the Signature confirmation email', 'super' ),
                                'desc'=>__( 'Users will receive a confirmation email before they are subscribed', 'super' ),
                                'type' => 'select',
                                'default'=> (!isset($attributes['send_confirmation']) ? 'no' : $attributes['send_confirmation']),
                                'values' => array(
                                    'no' => __( 'No', 'super' ), 
                                    'yes' => __( 'Yes', 'super' ), 
                                ),
                            ),                            
                            'email' => SUPER_Shortcodes::email($attributes, $default='Interests'),
                            'label' => $label,
                            'description'=> $description,
                            'tooltip' => $tooltip,
                            'validation' => $validation_empty,
                            'error' => $error,  
                        )
                    ),
                    'advanced' => array(
                        'name' => __( 'Advanced', 'super' ),
                        'fields' => array(
                            'maxlength' => $maxlength,
                            'minlength' => $minlength,
                            'display' => array(
                                'name'=>__( 'Vertical / Horizontal display', 'super' ), 
                                'type' => 'select',
                                'default'=> (!isset($attributes['display']) ? 'vertical' : $attributes['display']),
                                'values' => array(
                                    'vertical' => __( 'Vertical display ( | )', 'super' ), 
                                    'horizontal' => __( 'Horizontal display ( -- )', 'super' ), 
                                ),
                            ),
                            'grouped' => $grouped,                    
                            'width' => $width,
                            'exclude' => $exclude, 
                            'error_position' => $error_position_left_only,
                            
                        ),
                    ),
                    'icon' => array(
                        'name' => __( 'Icon', 'super' ),
                        'fields' => array(
                            'icon_position' => $icon_position,
                            'icon_align' => $icon_align,
                            'icon' => SUPER_Shortcodes::icon($attributes,'check-square-o'),
                        ),
                    ),
                    'conditional_logic' => $conditional_logic_array
                ),
            );
            return $array;
        }


        /**
         * Hook into settings and add Signature settings
         *
         *  @since      1.0.0
        */
        public static function add_signature_settings( $array, $settings ) {
            $array['signature'] = array(        
                'name' => __( 'Signature', 'super' ),
                'label' => __( 'Signature Settings', 'super' ),
                'fields' => array(
                    'signature_key' => array(
                        'name' => __( 'API key', 'super' ),
                        'default' => SUPER_Settings::get_value( 0, 'signature_key', $settings['settings'], '' ),
                    )
                )
            );
            return $array;
        }


        /**
         * Hook into before sending email and check for subscribe or unsubscribe action
         * After that do a curl request to signature to update the list by the given List ID
         *
         *  @since      1.0.0
        */
        public static function update_signature_subscribers( $atts ) {
            
            $data = $atts['post']['data'];
            if( isset( $data['signature_list_id'] ) ) {

                // Retreive the list ID
                $list_id = sanitize_text_field( $data['signature_list_id']['value'] );
                
                // Setup CURL
                $settings = get_option('super_settings');
                $api_key = $settings['signature_key'];
                $datacenter = explode('-', $api_key);
                $datacenter = $datacenter[1];
                $endpoint = 'https://' . $datacenter . '.api.signature.com/3.0/';
                $request = 'lists/' . $list_id . '/interest-categories/';

                $email = sanitize_email( $data['email']['value'] );
                $email = strtolower($email);
                $email_md5 = md5($email);
                $request = 'lists/' . $list_id . '/members/';
                $url = $endpoint.$request;
                $patch_url = $url . $email_md5;

                // Setup default user data
                $user_data['email_address'] = $email;
                if( isset( $data['first_name'] ) ) {
                    $user_data['merge_fields']['FNAME'] = $data['first_name']['value'];
                }
                if( isset( $data['last_name'] ) ) {
                    $user_data['merge_fields']['LNAME'] = $data['last_name']['value'];
                }
                if( $data['signature_send_confirmation']['value']==1 ) {
                    $user_data['status'] = 'pending';
                }else{
                    $user_data['status'] = 'subscribed';
                }

                // Find out if we have some selected interests
                if( isset( $data['signature_interests'] ) ) {
                    $interests = explode( ', ', $data['signature_interests']['value'] );
                    foreach($interests as $k => $v ){
                        $user_data['interests'][$v] = true;
                    }
                }
                
                $data_string = json_encode($user_data); 
                
                $ch = curl_init();
                curl_setopt( $ch, CURLOPT_URL, $url );
                curl_setopt( $ch, CURLOPT_HTTPHEADER, array( 'content-type: application/json' ) );
                curl_setopt( $ch, CURLOPT_USERPWD, 'anystring:' . $api_key ); 
                curl_setopt( $ch, CURLOPT_POSTFIELDS, $data_string );
                curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
                curl_setopt( $ch, CURLOPT_ENCODING, '' );
                $output = curl_exec( $ch );
                $output = json_decode( $output );

                // User already exists for this list, lets update the user with a PUT request
                if( $output->status==400 ) {

                    // Only delete interests if this for is actually giving the user the option to select interests
                    if( isset( $data['signature_interests'] ) ) {
                        // First get all interests, and set each interests to false
                        $ch = curl_init();
                        curl_setopt( $ch, CURLOPT_URL, $patch_url );
                        curl_setopt( $ch, CURLOPT_HTTPHEADER, array( 'content-type: application/json' ) );
                        curl_setopt( $ch, CURLOPT_USERPWD, 'anystring:' . $api_key ); 
                        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
                        curl_setopt( $ch, CURLOPT_ENCODING, '' );
                        $output = curl_exec( $ch );
                        $output = json_decode( $output );
                        
                        // Create a new object with all interests set to false
                        foreach( $output->interests as $k => $v ) {
                            $deleted_user_data['interests'][$k] = false;
                        }
                        $deleted_data_string = json_encode($deleted_user_data); 
                        
                        // Now update the user with it's new interests
                        $ch = curl_init();
                        curl_setopt( $ch, CURLOPT_URL, $patch_url );
                        curl_setopt( $ch, CURLOPT_HTTPHEADER, array( 'content-type: application/json' ) );
                        curl_setopt( $ch, CURLOPT_USERPWD, 'anystring:' . $api_key ); 
                        curl_setopt( $ch, CURLOPT_POSTFIELDS, $deleted_data_string );
                        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
                        curl_setopt( $ch, CURLOPT_ENCODING, '' );
                        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH' );
                        $output = curl_exec( $ch );
                        $output = json_decode( $output );
                    }

                    // Now update the user with it's new interests
                    $ch = curl_init();
                    curl_setopt( $ch, CURLOPT_URL, $patch_url );
                    curl_setopt( $ch, CURLOPT_HTTPHEADER, array( 'content-type: application/json' ) );
                    curl_setopt( $ch, CURLOPT_USERPWD, 'anystring:' . $api_key ); 
                    curl_setopt( $ch, CURLOPT_POSTFIELDS, $data_string );
                    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
                    curl_setopt( $ch, CURLOPT_ENCODING, '' );
                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH' );
                    $output = curl_exec( $ch );
                    $output = json_decode( $output );

                }
            }
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
