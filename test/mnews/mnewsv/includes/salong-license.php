<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'AM_License_Menu' ) ) {
	class AM_License_Menu {

		public $file             = '';
		public $software_title   = '';
		public $software_version = '';
		public $plugin_or_theme  = '';
		public $api_url          = '';
		public $data_prefix      = '';
		public $slug             = '';
		public $plugin_name      = '';
		public $text_domain      = '';
		public $extra            = '';

		public $ame_software_product_id;
		public $ame_data_key;
		public $ame_api_key;
		public $ame_activation_email;
		public $ame_product_id_key;
		public $ame_instance_key;
		public $ame_deactivate_checkbox_key;
		public $ame_activated_key;
		public $ame_activation_tab_key;
		public $ame_settings_menu_title;
		public $ame_settings_title;
		public $ame_menu_tab_activation_title;
		public $ame_menu_tab_deactivation_title;
		public $ame_options;
		public $ame_plugin_name;
		public $ame_product_id;
		public $ame_renew_license_url;
		public $ame_instance_id;
		public $ame_domain;
		public $ame_software_version;

		protected static $_instance = null;
		public static function instance( $file, $software_title, $software_version, $plugin_or_theme, $api_url, $text_domain = '', $extra = '' ) {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self( $file, $software_title, $software_version, $plugin_or_theme, $api_url, $text_domain, $extra );
			}

			return self::$_instance;
		}

		public function __construct( $file, $software_title, $software_version, $plugin_or_theme, $api_url, $text_domain, $extra ) {
			$this->file            = $file;
			$this->software_title  = $software_title;
			$this->version         = $software_version;
			$this->plugin_or_theme = $plugin_or_theme;
			$this->api_url         = $api_url;
			$this->text_domain     = $text_domain;
			$this->extra           = $extra;
			$this->data_prefix     = str_ireplace( array( ' ', '_', '&', '?' ), '_', strtolower( $this->software_title ) );

			if ( is_admin() ) {
				if ( ! empty( $this->plugin_or_theme ) && $this->plugin_or_theme == 'theme' ) {
					add_action( 'admin_init', array( $this, 'activation' ) );
				}

				if ( ! empty( $this->plugin_or_theme ) && $this->plugin_or_theme == 'plugin' ) {
					register_activation_hook( $this->file, array( $this, 'activation' ) );
				}

				add_action( 'admin_menu', array( $this, 'register_menu' ) );
				add_action( 'admin_init', array( $this, 'load_settings' ) );

				add_action( 'admin_notices', array( $this, 'check_external_blocking' ) );
				$this->ame_software_product_id = $this->software_title;
				$this->ame_data_key                = $this->data_prefix . '_data';
				$this->ame_api_key                 = 'api_key';
				$this->ame_activation_email        = 'activation_email';
				$this->ame_product_id_key          = $this->data_prefix . '_product_id';
				$this->ame_instance_key            = $this->data_prefix . '_instance';
				$this->ame_deactivate_checkbox_key = $this->data_prefix . '_deactivate_checkbox';
				$this->ame_activated_key           = $this->data_prefix . '_activated';
				$this->ame_deactivate_checkbox         = $this->data_prefix . '_deactivate_checkbox';
				$this->ame_activation_tab_key          = $this->data_prefix . '_dashboard';
				$this->ame_deactivation_tab_key        = $this->data_prefix . '_deactivation';
				$this->ame_settings_menu_title         = $this->software_title . __( ' Activation', 'salong' );
				$this->ame_settings_title              = $this->software_title . __( ' API Key Activation', 'salong' );
				$this->ame_menu_tab_activation_title   = __( 'API Key Activation', 'salong' );
				$this->ame_menu_tab_deactivation_title = __( 'API Key Deactivation', 'salong' );
				$this->ame_options           = get_option( $this->ame_data_key );
				$this->ame_plugin_name       = $this->plugin_or_theme == 'plugin' ? untrailingslashit( plugin_basename( $this->file ) ) : get_stylesheet(); 
				$this->ame_product_id        = get_option( $this->ame_product_id_key );
				$this->ame_renew_license_url = $this->api_url . 'my-account';
				$this->ame_instance_id       = get_option( $this->ame_instance_key );
				$this->ame_domain           = str_ireplace( array( 'http://', 'https://' ), '', home_url() );
				$this->ame_software_version = $this->version;
				$options                    = get_option( $this->ame_data_key );
				if ( ! empty( $options ) && $options !== false ) {
					$this->check_for_update();
				}

				if ( ! empty( $this->ame_activated_key ) && get_option( $this->ame_activated_key ) != 'Activated' ) {
					add_action( 'admin_notices', array( $this, 'inactive_notice' ) );
				}
			}
			if ( $this->plugin_or_theme == 'plugin' ) {
				register_deactivation_hook( $this->file, array( $this, 'uninstall' ) );
			}

			if ( $this->plugin_or_theme == 'theme' ) {
				add_action( 'switch_theme', array( $this, 'uninstall' ) );
			}
		}
		public function register_menu() {
			add_options_page( __( $this->ame_settings_menu_title, 'salong' ), __( $this->ame_settings_menu_title, 'salong' ), 'manage_options', $this->ame_activation_tab_key, array(
				$this,
				'config_page'
			) );
		}
		public function activation() {
			if ( get_option( $this->ame_data_key ) === false || get_option( $this->ame_instance_key ) === false ) {
				$global_options = array(
					$this->ame_api_key          => '',
					$this->ame_activation_email => '',
				);

				update_option( $this->ame_data_key, $global_options );

				$single_options = array(
					$this->ame_product_id_key          => $this->ame_software_product_id,
					$this->ame_instance_key            => wp_generate_password( 12, false ),
					$this->ame_deactivate_checkbox_key => 'on',
					$this->ame_activated_key           => 'Deactivated',
				);

				foreach ( $single_options as $key => $value ) {
					update_option( $key, $value );
				}
			}
		}
		public function uninstall() {
			global $blog_id;

			$this->license_key_deactivation();

			if ( is_multisite() ) {
				switch_to_blog( $blog_id );

				foreach (
					array(
						$this->ame_data_key,
						$this->ame_product_id_key,
						$this->ame_instance_key,
						$this->ame_deactivate_checkbox_key,
						$this->ame_activated_key,
					) as $option
				) {

					delete_option( $option );
				}

				restore_current_blog();
			} else {
				foreach (
					array(
						$this->ame_data_key,
						$this->ame_product_id_key,
						$this->ame_instance_key,
						$this->ame_deactivate_checkbox_key,
						$this->ame_activated_key
					) as $option
				) {

					delete_option( $option );
				}
			}
		}
		public function license_key_deactivation() {
			$activation_status = get_option( $this->ame_activated_key );
			$api_email         = $this->ame_options[ $this->ame_activation_email ];
			$api_key           = $this->ame_options[ $this->ame_api_key ];

			$args = array(
				'email'       => $api_email,
				'licence_key' => $api_key,
			);

			if ( $activation_status == 'Activated' && $api_key != '' && $api_email != '' ) {
				$this->deactivate( $args );
			}
		}
		public function inactive_notice() { ?>
			<?php if ( ! current_user_can( 'manage_options' ) ) {
				return;
			} ?>
			<?php if ( isset( $_GET[ 'page' ] ) && $this->ame_activation_tab_key == $_GET[ 'page' ] ) {
				return;
			} ?>
            <div class="notice notice-error">
                <p><?php printf( __( 'The <strong>%s</strong> API Key has not been activated, so the %s is inactive! %sClick here%s to activate <strong>%s</strong>.', 'salong' ), esc_attr( $this->software_title ), esc_attr( $this->plugin_or_theme ), '<a href="' . esc_url( admin_url( 'options-general.php?page=' . $this->ame_activation_tab_key ) ) . '">', '</a>', esc_attr( $this->software_title ) ); ?></p>
            </div>
			<?php
		}
		public function check_external_blocking() {
			if ( defined( 'WP_HTTP_BLOCK_EXTERNAL' ) && WP_HTTP_BLOCK_EXTERNAL === true ) {
				$host = parse_url( $this->api_url, PHP_URL_HOST );

				if ( ! defined( 'WP_ACCESSIBLE_HOSTS' ) || stristr( WP_ACCESSIBLE_HOSTS, $host ) === false ) {
					?>
                    <div class="notice notice-error">
                        <p><?php printf( __( '<b>Warning!</b> You\'re blocking external requests which means you won\'t be able to get %s updates. Please add %s to %s.', 'salong' ), $this->ame_software_product_id, '<strong>' . $host . '</strong>', '<code>WP_ACCESSIBLE_HOSTS</code>' ); ?></p>
                    </div>
					<?php
				}
			}
		}

		public function config_page() {
			$settings_tabs = array(
				$this->ame_activation_tab_key   => __( $this->ame_menu_tab_activation_title, 'salong' ),
				$this->ame_deactivation_tab_key => __( $this->ame_menu_tab_deactivation_title, 'salong' )
			);
			$current_tab   = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : $this->ame_activation_tab_key;
			$tab           = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : $this->ame_activation_tab_key;
			?>
            <div class='wrap'>
                <h2><?php _e( $this->ame_settings_title, 'salong' ); ?></h2>
                <h2 class="nav-tab-wrapper">
					<?php
					foreach ( $settings_tabs as $tab_page => $tab_name ) {
						$active_tab = $current_tab == $tab_page ? 'nav-tab-active' : '';
						echo '<a class="nav-tab ' . $active_tab . '" href="?page=' . $this->ame_activation_tab_key . '&tab=' . $tab_page . '">' . $tab_name . '</a>';
					}
					?>
                </h2>
                <form action='options.php' method='post'>
                    <div class="main">
						<?php
						if ( $tab == $this->ame_activation_tab_key ) {
							settings_fields( $this->ame_data_key );
							do_settings_sections( $this->ame_activation_tab_key );
							submit_button( __( 'Save Changes', 'salong' ) );
						} else {
							settings_fields( $this->ame_deactivate_checkbox );
							do_settings_sections( $this->ame_deactivation_tab_key );
							submit_button( __( 'Save Changes', 'salong' ) );
						}
						?>
                    </div>
                </form>
            </div>
			<?php
		}

		public function load_settings() {
			register_setting( $this->ame_data_key, $this->ame_data_key, array( $this, 'validate_options' ) );
			add_settings_section( $this->ame_api_key, __( 'API Key Activation', 'salong' ), array(
				$this,
				'wc_am_api_key_text'
			), $this->ame_activation_tab_key );
			add_settings_field( 'status', __( 'API Key Status', 'salong' ), array(
				$this,
				'wc_am_api_key_status'
			), $this->ame_activation_tab_key, $this->ame_api_key );
			add_settings_field( $this->ame_api_key, __( 'API Key', 'salong' ), array(
				$this,
				'wc_am_api_key_field'
			), $this->ame_activation_tab_key, $this->ame_api_key );
			add_settings_field( $this->ame_activation_email, __( 'API Email', 'salong' ), array(
				$this,
				'wc_am_api_email_field'
			), $this->ame_activation_tab_key, $this->ame_api_key );
			register_setting( $this->ame_deactivate_checkbox, $this->ame_deactivate_checkbox, array( $this, 'wc_am_license_key_deactivation' ) );
			add_settings_section( 'deactivate_button', __( 'API Deactivation', 'salong' ), array(
				$this,
				'wc_am_deactivate_text'
			), $this->ame_deactivation_tab_key );
			add_settings_field( 'deactivate_button', __( 'Deactivate API Key', 'salong' ), array(
				$this,
				'wc_am_deactivate_textarea'
			), $this->ame_deactivation_tab_key, 'deactivate_button' );
		}

		public function wc_am_api_key_text() { }

		public function wc_am_api_key_status() {
			$license_status       = $this->license_key_status();
			$license_status_check = ( ! empty( $license_status[ 'status_check' ] ) && $license_status[ 'status_check' ] == 'active' ) ? 'Activated' : 'Deactivated';
			if ( ! empty( $license_status_check ) ) {
				echo $license_status_check;
			}
		}

		public function wc_am_api_key_field() {
			echo "<input id='api_key' name='" . $this->ame_data_key . "[" . $this->ame_api_key . "]' size='25' type='text' value='" . $this->ame_options[ $this->ame_api_key ] . "' />";
			if ( $this->ame_options[ $this->ame_api_key ] ) {
				echo "<span class='dashicons dashicons-yes' style='color: #66ab03;'></span>";
			} else {
				echo "<span class='dashicons dashicons-no' style='color: #ca336c;'></span>";
			}
		}

		public function wc_am_api_email_field() {
			echo "<input id='activation_email' name='" . $this->ame_data_key . "[" . $this->ame_activation_email . "]' size='25' type='text' value='" . $this->ame_options[ $this->ame_activation_email ] . "' />";
			if ( $this->ame_options[ $this->ame_activation_email ] ) {
				echo "<span class='dashicons dashicons-yes' style='color: #66ab03;'></span>";
			} else {
				echo "<span class='dashicons dashicons-no' style='color: #ca336c;'></span>";
			}
		}

		public function validate_options( $input ) {
			$options                                = $this->ame_options;
			$options[ $this->ame_api_key ]          = trim( $input[ $this->ame_api_key ] );
			$options[ $this->ame_activation_email ] = trim( $input[ $this->ame_activation_email ] );
			$api_email                              = trim( $input[ $this->ame_activation_email ] );
			$api_key                                = trim( $input[ $this->ame_api_key ] );
			$activation_status                      = get_option( $this->ame_activated_key );
			$checkbox_status                        = get_option( $this->ame_deactivate_checkbox );
			$current_api_key                        = $this->ame_options[ $this->ame_api_key ];

			if ( $_REQUEST[ 'option_page' ] != $this->ame_deactivate_checkbox ) {
				if ( $activation_status == 'Deactivated' || $activation_status == '' || $api_key == '' || $api_email == '' || $checkbox_status == 'on' || $current_api_key != $api_key ) {
					if ( $current_api_key != $api_key ) {
						$this->replace_license_key( $current_api_key );
					}

					$args             = array(
						'email'       => $api_email,
						'licence_key' => $api_key,
					);
					$activate_results = json_decode( $this->activate( $args ), true );

					if ( $activate_results[ 'activated' ] === true && ! empty( $this->ame_activated_key ) ) {
						add_settings_error( 'activate_text', 'activate_msg', sprintf( __( '%s activated. ', 'salong' ), esc_attr( $this->software_title ) ) . "{$activate_results['message']}.", 'updated' );
						update_option( $this->ame_activated_key, 'Activated' );
						update_option( $this->ame_deactivate_checkbox, 'off' );
					}

					if ( $activate_results == false && ! empty( $this->ame_options ) && ! empty( $this->ame_activated_key ) ) {
						add_settings_error( 'api_key_check_text', 'api_key_check_error', __( 'Connection failed to the License Key API server. Try again later. There may be a problem on your server preventing outgoing requests, or the store is blocking your request to activate the plugin/theme.', 'salong' ), 'error' );
						$options[ $this->ame_api_key ]          = '';
						$options[ $this->ame_activation_email ] = '';
						update_option( $this->ame_options[ $this->ame_activated_key ], 'Deactivated' );
					}

					if ( isset( $activate_results[ 'code' ] ) && ! empty( $this->ame_options ) && ! empty( $this->ame_activated_key ) ) {
						switch ( $activate_results[ 'code' ] ) {
							case '100':
								$additional_info = ! empty( $activate_results[ 'additional info' ] ) ? esc_attr( $activate_results[ 'additional info' ] ) : '';
								add_settings_error( 'api_email_text', 'api_email_error', "{$activate_results['error']}. {$additional_info}", 'error' );
								$options[ $this->ame_activation_email ] = '';
								$options[ $this->ame_api_key ]          = '';
								update_option( $this->ame_options[ $this->ame_activated_key ], 'Deactivated' );
								break;
							case '101':
								$additional_info = ! empty( $activate_results[ 'additional info' ] ) ? esc_attr( $activate_results[ 'additional info' ] ) : '';
								add_settings_error( 'api_key_text', 'api_key_error', "{$activate_results['error']}. {$additional_info}", 'error' );
								$options[ $this->ame_api_key ]          = '';
								$options[ $this->ame_activation_email ] = '';
								update_option( $this->ame_options[ $this->ame_activated_key ], 'Deactivated' );
								break;
							case '102':
								$additional_info = ! empty( $activate_results[ 'additional info' ] ) ? esc_attr( $activate_results[ 'additional info' ] ) : '';
								add_settings_error( 'api_key_purchase_incomplete_text', 'api_key_purchase_incomplete_error', "{$activate_results['error']}. {$additional_info}", 'error' );
								$options[ $this->ame_api_key ]          = '';
								$options[ $this->ame_activation_email ] = '';
								update_option( $this->ame_options[ $this->ame_activated_key ], 'Deactivated' );
								break;
							case '103':
								$additional_info = ! empty( $activate_results[ 'additional info' ] ) ? esc_attr( $activate_results[ 'additional info' ] ) : '';
								add_settings_error( 'api_key_exceeded_text', 'api_key_exceeded_error', "{$activate_results['error']}. {$additional_info}", 'error' );
								$options[ $this->ame_api_key ]          = '';
								$options[ $this->ame_activation_email ] = '';
								update_option( $this->ame_options[ $this->ame_activated_key ], 'Deactivated' );
								break;
							case '104':
								$additional_info = ! empty( $activate_results[ 'additional info' ] ) ? esc_attr( $activate_results[ 'additional info' ] ) : '';
								add_settings_error( 'api_key_not_activated_text', 'api_key_not_activated_error', "{$activate_results['error']}. {$additional_info}", 'error' );
								$options[ $this->ame_api_key ]          = '';
								$options[ $this->ame_activation_email ] = '';
								update_option( $this->ame_options[ $this->ame_activated_key ], 'Deactivated' );
								break;
							case '105':
								$additional_info = ! empty( $activate_results[ 'additional info' ] ) ? esc_attr( $activate_results[ 'additional info' ] ) : '';
								add_settings_error( 'api_key_invalid_text', 'api_key_invalid_error', "{$activate_results['error']}. {$additional_info}", 'error' );
								$options[ $this->ame_api_key ]          = '';
								$options[ $this->ame_activation_email ] = '';
								update_option( $this->ame_options[ $this->ame_activated_key ], 'Deactivated' );
								break;
							case '106':
								$additional_info = ! empty( $activate_results[ 'additional info' ] ) ? esc_attr( $activate_results[ 'additional info' ] ) : '';
								add_settings_error( 'sub_not_active_text', 'sub_not_active_error', "{$activate_results['error']}. {$additional_info}", 'error' );
								$options[ $this->ame_api_key ]          = '';
								$options[ $this->ame_activation_email ] = '';
								update_option( $this->ame_options[ $this->ame_activated_key ], 'Deactivated' );
								break;
						}
					}
				}
			}

			return $options;
		}
		public function license_key_status() {
			$args = array(
				'email'       => $this->ame_options[ $this->ame_activation_email ],
				'licence_key' => $this->ame_options[ $this->ame_api_key ],
			);

			return json_decode( $this->status( $args ), true );
		}
		public function replace_license_key( $current_api_key ) {
			$args = array(
				'email'       => $this->ame_options[ $this->ame_activation_email ],
				'licence_key' => $current_api_key,
			);

			$reset = $this->deactivate( $args );

			if ( $reset == true ) {
				return true;
			}

			add_settings_error( 'not_deactivated_text', 'not_deactivated_error', __( 'The API Key could not be deactivated. Use the API Key Deactivation tab to manually deactivate the API Key before activating a new API Key. If all else fails, go to Plugins, then deactivate and reactivate this plugin, or if a theme change themes, then change back to this theme, then go to the Settings for this plugin/theme and enter the API Key information again to activate it. Also check the My Account dashboard to see if the API Key for this site was still active before the error message was displayed.', 'salong' ), 'updated' );

			return false;
		}

		public function wc_am_license_key_deactivation( $input ) {
			$activation_status = get_option( $this->ame_activated_key );
			$args              = array(
				'email'       => $this->ame_options[ $this->ame_activation_email ],
				'licence_key' => $this->ame_options[ $this->ame_api_key ],
			);

			$options = ( $input == 'on' ? 'on' : 'off' );
			if ( $options == 'on' && $activation_status == 'Activated' && $this->ame_options[ $this->ame_api_key ] != '' && $this->ame_options[ $this->ame_activation_email ] != '' ) {
				$activate_results = json_decode( $this->deactivate( $args ), true );

				if ( $activate_results[ 'deactivated' ] === true ) {
					$update        = array(
						$this->ame_api_key          => '',
						$this->ame_activation_email => ''
					);
					$merge_options = array_merge( $this->ame_options, $update );

					if ( ! empty( $this->ame_activated_key ) ) {
						update_option( $this->ame_data_key, $merge_options );
						update_option( $this->ame_activated_key, 'Deactivated' );
						add_settings_error( 'wc_am_deactivate_text', 'deactivate_msg', __( 'API Key deactivated. ', 'salong' ) . "{$activate_results['activations_remaining']}.", 'updated' );
					}

					return $options;
				}

				if ( isset( $activate_results[ 'code' ] ) && ! empty( $this->ame_options ) && ! empty( $this->ame_activated_key ) ) {
					switch ( $activate_results[ 'code' ] ) {
						case '100':
							$additional_info = ! empty( $activate_results[ 'additional info' ] ) ? esc_attr( $activate_results[ 'additional info' ] ) : '';
							add_settings_error( 'api_email_text', 'api_email_error', "{$activate_results['error']}. {$additional_info}", 'error' );
							$options[ $this->ame_activation_email ] = '';
							$options[ $this->ame_api_key ]          = '';
							update_option( $this->ame_options[ $this->ame_activated_key ], 'Deactivated' );
							break;
						case '101':
							$additional_info = ! empty( $activate_results[ 'additional info' ] ) ? esc_attr( $activate_results[ 'additional info' ] ) : '';
							add_settings_error( 'api_key_text', 'api_key_error', "{$activate_results['error']}. {$additional_info}", 'error' );
							$options[ $this->ame_api_key ]          = '';
							$options[ $this->ame_activation_email ] = '';
							update_option( $this->ame_options[ $this->ame_activated_key ], 'Deactivated' );
							break;
						case '102':
							$additional_info = ! empty( $activate_results[ 'additional info' ] ) ? esc_attr( $activate_results[ 'additional info' ] ) : '';
							add_settings_error( 'api_key_purchase_incomplete_text', 'api_key_purchase_incomplete_error', "{$activate_results['error']}. {$additional_info}", 'error' );
							$options[ $this->ame_api_key ]          = '';
							$options[ $this->ame_activation_email ] = '';
							update_option( $this->ame_options[ $this->ame_activated_key ], 'Deactivated' );
							break;
						case '103':
							$additional_info = ! empty( $activate_results[ 'additional info' ] ) ? esc_attr( $activate_results[ 'additional info' ] ) : '';
							add_settings_error( 'api_key_exceeded_text', 'api_key_exceeded_error', "{$activate_results['error']}. {$additional_info}", 'error' );
							$options[ $this->ame_api_key ]          = '';
							$options[ $this->ame_activation_email ] = '';
							update_option( $this->ame_options[ $this->ame_activated_key ], 'Deactivated' );
							break;
						case '104':
							$additional_info = ! empty( $activate_results[ 'additional info' ] ) ? esc_attr( $activate_results[ 'additional info' ] ) : '';
							add_settings_error( 'api_key_not_activated_text', 'api_key_not_activated_error', "{$activate_results['error']}. {$additional_info}", 'error' );
							$options[ $this->ame_api_key ]          = '';
							$options[ $this->ame_activation_email ] = '';
							update_option( $this->ame_options[ $this->ame_activated_key ], 'Deactivated' );
							break;
						case '105':
							$additional_info = ! empty( $activate_results[ 'additional info' ] ) ? esc_attr( $activate_results[ 'additional info' ] ) : '';
							add_settings_error( 'api_key_invalid_text', 'api_key_invalid_error', "{$activate_results['error']}. {$additional_info}", 'error' );
							$options[ $this->ame_api_key ]          = '';
							$options[ $this->ame_activation_email ] = '';
							update_option( $this->ame_options[ $this->ame_activated_key ], 'Deactivated' );
							break;
						case '106':
							$additional_info = ! empty( $activate_results[ 'additional info' ] ) ? esc_attr( $activate_results[ 'additional info' ] ) : '';
							add_settings_error( 'sub_not_active_text', 'sub_not_active_error', "{$activate_results['error']}. {$additional_info}", 'error' );
							$options[ $this->ame_api_key ]          = '';
							$options[ $this->ame_activation_email ] = '';
							update_option( $this->ame_options[ $this->ame_activated_key ], 'Deactivated' );
							break;
					}
				}
			} else {

				return $options;
			}

			return false;
		}

		public function wc_am_deactivate_text() { }

		public function wc_am_deactivate_textarea() {
			echo '<input type="checkbox" id="' . $this->ame_deactivate_checkbox . '" name="' . $this->ame_deactivate_checkbox . '" value="on"';
			echo checked( get_option( $this->ame_deactivate_checkbox ), 'on' );
			echo '/>';
			?><span class="description"><?php _e( 'Deactivates an API Key so it can be used on another blog.', 'salong' ); ?></span>
			<?php
		}
		public function create_software_api_url( $args ) {
			return add_query_arg( 'wc-api', 'am-software-api', $this->api_url ) . '&' . http_build_query( $args );
		}
		public function activate( $args ) {
			$defaults = array(
				'request'          => 'activation',
				'product_id'       => $this->ame_product_id,
				'instance'         => $this->ame_instance_id,
				'platform'         => $this->ame_domain,
				'software_version' => $this->ame_software_version
			);

			$args       = wp_parse_args( $defaults, $args );
			$target_url = esc_url_raw( $this->create_software_api_url( $args ) );
			$request    = wp_safe_remote_get( $target_url );


			if ( is_wp_error( $request ) || wp_remote_retrieve_response_code( $request ) != 200 ) {
				return false;
			}

			$response = wp_remote_retrieve_body( $request );

			return $response;
		}
		public function deactivate( $args ) {
			$defaults = array(
				'request'    => 'deactivation',
				'product_id' => $this->ame_product_id,
				'instance'   => $this->ame_instance_id,
				'platform'   => $this->ame_domain
			);

			$args       = wp_parse_args( $defaults, $args );
			$target_url = esc_url_raw( $this->create_software_api_url( $args ) );
			$request    = wp_safe_remote_get( $target_url );


			if ( is_wp_error( $request ) || wp_remote_retrieve_response_code( $request ) != 200 ) {
				return false;
			}

			$response = wp_remote_retrieve_body( $request );

			return $response;
		}
		public function status( $args ) {
			$defaults = array(
				'request'    => 'status',
				'product_id' => $this->ame_product_id,
				'instance'   => $this->ame_instance_id,
				'platform'   => $this->ame_domain
			);

			$args       = wp_parse_args( $defaults, $args );
			$target_url = esc_url_raw( $this->create_software_api_url( $args ) );
			$request    = wp_safe_remote_get( $target_url );


			if ( is_wp_error( $request ) || wp_remote_retrieve_response_code( $request ) != 200 ) {
				return false;
			}

			$response = wp_remote_retrieve_body( $request );

			return $response;
		}
		public function check_for_update() {
			$this->plugin_name = $this->ame_plugin_name;

			if ( strpos( $this->plugin_name, '.php' ) !== 0 ) {
				$this->slug = dirname( $this->plugin_name );
			} else {
				$this->slug = $this->plugin_name;
			}
			if ( $this->plugin_or_theme == 'plugin' ) {
				add_filter( 'pre_set_site_transient_update_plugins', array( $this, 'update_check' ) );
				add_filter( 'plugins_api', array( $this, 'request' ), 10, 3 );
			} else if ( $this->plugin_or_theme == 'theme' ) {
				add_filter( 'pre_set_site_transient_update_themes', array( $this, 'update_check' ) );

			}
		}
		private function create_upgrade_api_url( $args ) {
			return add_query_arg( 'wc-api', 'upgrade-api', $this->api_url ) . '&' . http_build_query( $args );
		}
		public function update_check( $transient ) {
			if ( empty( $transient->checked ) ) {
				return $transient;
			}

			$args = array(
				'request'          => 'pluginupdatecheck',
				'slug'             => $this->slug,
				'plugin_name'      => $this->plugin_name,
				'version'          => $this->ame_software_version,
				'product_id'       => $this->ame_product_id,
				'api_key'          => $this->ame_options[ $this->ame_api_key ],
				'activation_email' => $this->ame_options[ $this->ame_activation_email ],
				'instance'         => $this->ame_instance_id,
				'domain'           => $this->ame_domain,
				'software_version' => $this->ame_software_version,
				'extra'            => $this->extra,
			);

			$response = $this->plugin_information( $args );
			$this->check_response_for_errors( $response );

			if ( isset( $response ) && is_object( $response ) && $response !== false ) {
				$new_ver = (string) $response->new_version;
				$curr_ver = (string) $this->ame_software_version;
			}

			if ( isset( $new_ver ) && isset( $curr_ver ) ) {
				if ( $response !== false && version_compare( $new_ver, $curr_ver, '>' ) ) {
					if ( $this->plugin_or_theme == 'plugin' ) {
						$transient->response[ $this->plugin_name ] = $response;
					} else if ( $this->plugin_or_theme == 'theme' ) {
						$transient->response[ $this->plugin_name ][ 'new_version' ] = $response->new_version;
						$transient->response[ $this->plugin_name ][ 'url' ]         = $response->url;
						$transient->response[ $this->plugin_name ][ 'package' ]     = $response->package;
					}
				}
			}

			return $transient;
		}
		public function plugin_information( $args ) {
			$target_url = esc_url_raw( $this->create_upgrade_api_url( $args ) );
			$request    = wp_safe_remote_get( $target_url );

			if ( is_wp_error( $request ) || wp_remote_retrieve_response_code( $request ) != 200 ) {
				return false;
			}

			$response = unserialize( wp_remote_retrieve_body( $request ) );

			if ( is_object( $response ) ) {
				return $response;
			} else {
				return false;
			}
		}
		public function request( $result, $action, $args ) {
			if ( isset( $args->slug ) ) {
				if ( $args->slug != $this->slug ) {
					return $result;
				}
			} else {
				return $result;
			}

			$args = array(
				'request'          => 'plugininformation',
				'plugin_name'      => $this->plugin_name,
				'version'          => $this->ame_software_version,
				'product_id'       => $this->ame_product_id,
				'api_key'          => $this->ame_options[ $this->ame_api_key ],
				'activation_email' => $this->ame_options[ $this->ame_activation_email ],
				'instance'         => $this->ame_instance_id,
				'domain'           => $this->ame_domain,
				'software_version' => $this->ame_software_version,
				'extra'            => $this->extra,
			);

			$response = $this->plugin_information( $args );

			if ( isset( $response ) && is_object( $response ) && $response !== false ) {
				return $response;
			}

			return $result;
		}
		public function check_response_for_errors( $response ) {
			if ( ! empty( $response ) && is_object( $response ) ) {
				if ( isset( $response->errors[ 'no_key' ] ) && $response->errors[ 'no_key' ] == 'no_key' && isset( $response->errors[ 'no_subscription' ] ) && $response->errors[ 'no_subscription' ] == 'no_subscription' ) {
					add_action( 'admin_notices', array( $this, 'no_key_error_notice' ) );
					add_action( 'admin_notices', array( $this, 'no_subscription_error_notice' ) );
				} else if ( isset( $response->errors[ 'exp_license' ] ) && $response->errors[ 'exp_license' ] == 'exp_license' ) {
					add_action( 'admin_notices', array( $this, 'expired_license_error_notice' ) );
				} else if ( isset( $response->errors[ 'hold_subscription' ] ) && $response->errors[ 'hold_subscription' ] == 'hold_subscription' ) {
					add_action( 'admin_notices', array( $this, 'on_hold_subscription_error_notice' ) );
				} else if ( isset( $response->errors[ 'cancelled_subscription' ] ) && $response->errors[ 'cancelled_subscription' ] == 'cancelled_subscription' ) {
					add_action( 'admin_notices', array( $this, 'cancelled_subscription_error_notice' ) );
				} else if ( isset( $response->errors[ 'exp_subscription' ] ) && $response->errors[ 'exp_subscription' ] == 'exp_subscription' ) {
					add_action( 'admin_notices', array( $this, 'expired_subscription_error_notice' ) );
				} else if ( isset( $response->errors[ 'suspended_subscription' ] ) && $response->errors[ 'suspended_subscription' ] == 'suspended_subscription' ) {
					add_action( 'admin_notices', array( $this, 'suspended_subscription_error_notice' ) );
				} else if ( isset( $response->errors[ 'pending_subscription' ] ) && $response->errors[ 'pending_subscription' ] == 'pending_subscription' ) {
					add_action( 'admin_notices', array( $this, 'pending_subscription_error_notice' ) );
				} else if ( isset( $response->errors[ 'trash_subscription' ] ) && $response->errors[ 'trash_subscription' ] == 'trash_subscription' ) {
					add_action( 'admin_notices', array( $this, 'trash_subscription_error_notice' ) );
				} else if ( isset( $response->errors[ 'no_subscription' ] ) && $response->errors[ 'no_subscription' ] == 'no_subscription' ) {
					add_action( 'admin_notices', array( $this, 'no_subscription_error_notice' ) );
				} else if ( isset( $response->errors[ 'no_activation' ] ) && $response->errors[ 'no_activation' ] == 'no_activation' ) {
					add_action( 'admin_notices', array( $this, 'no_activation_error_notice' ) );
				} else if ( isset( $response->errors[ 'no_key' ] ) && $response->errors[ 'no_key' ] == 'no_key' ) {
					add_action( 'admin_notices', array( $this, 'no_key_error_notice' ) );
				} else if ( isset( $response->errors[ 'download_revoked' ] ) && $response->errors[ 'download_revoked' ] == 'download_revoked' ) {
					add_action( 'admin_notices', array( $this, 'download_revoked_error_notice' ) );
				} else if ( isset( $response->errors[ 'switched_subscription' ] ) && $response->errors[ 'switched_subscription' ] == 'switched_subscription' ) {
					add_action( 'admin_notices', array( $this, 'switched_subscription_error_notice' ) );
				}
			}
		}
		public function expired_license_error_notice() {
			echo sprintf( '<div class="notice notice-info"><p>' . __( 'The license key for %s has expired. You can reactivate or purchase a license key from your account <a href="%s" target="_blank">dashboard</a>.', 'salong' ) . '</p></div>', esc_attr( $this->ame_software_product_id ), esc_url( $this->ame_renew_license_url ) );
		}
		public function on_hold_subscription_error_notice() {
			echo sprintf( '<div class="notice notice-info"><p>' . __( 'The subscription for %s is on-hold. You can reactivate the subscription from your account <a href="%s" target="_blank">dashboard</a>.', 'salong' ) . '</p></div>', esc_attr( $this->ame_software_product_id ), esc_url( $this->ame_renew_license_url ) );
		}
		public function cancelled_subscription_error_notice() {
			echo sprintf( '<div class="notice notice-info"><p>' . __( 'The subscription for %s has been cancelled. You can renew the subscription from your account <a href="%s" target="_blank">dashboard</a>. A new license key will be emailed to you after your order has been completed.', 'salong' ) . '</p></div>', esc_attr( $this->ame_software_product_id ), esc_url( $this->ame_renew_license_url ) );
		}
		public function expired_subscription_error_notice() {
			echo sprintf( '<div class="notice notice-info"><p>' . __( 'The subscription for %s has expired. You can reactivate the subscription from your account <a href="%s" target="_blank">dashboard</a>.', 'salong' ) . '</p></div>', esc_attr( $this->ame_software_product_id ), esc_url( $this->ame_renew_license_url ) );
		}
		public function suspended_subscription_error_notice() {
			echo sprintf( '<div class="notice notice-info"><p>' . __( 'The subscription for %s has been suspended. You can reactivate the subscription from your account <a href="%s" target="_blank">dashboard</a>.', 'salong' ) . '</p></div>', esc_attr( $this->ame_software_product_id ), esc_url( $this->ame_renew_license_url ) );
		}
		public function pending_subscription_error_notice() {
			echo sprintf( '<div class="notice notice-info"><p>' . __( 'The subscription for %s is still pending. You can check on the status of the subscription from your account <a href="%s" target="_blank">dashboard</a>.', 'salong' ) . '</p></div>', esc_attr( $this->ame_software_product_id ), esc_url( $this->ame_renew_license_url ) );
		}
		public function trash_subscription_error_notice() {
			echo sprintf( '<div class="notice notice-info"><p>' . __( 'The subscription for %s has been placed in the trash and will be deleted soon. You can purchase a new subscription from your account <a href="%s" target="_blank">dashboard</a>.', 'salong' ) . '</p></div>', esc_attr( $this->ame_software_product_id ), esc_url( $this->ame_renew_license_url ) );
		}
		public function no_subscription_error_notice() {
			echo sprintf( '<div class="notice notice-info"><p>' . __( 'A subscription for %s could not be found. You can purchase a subscription from your account <a href="%s" target="_blank">dashboard</a>.', 'salong' ) . '</p></div>', esc_attr( $this->ame_software_product_id ), esc_url( $this->ame_renew_license_url ) );
		}
		public function no_key_error_notice() {
			echo sprintf( '<div class="notice notice-info"><p>' . __( 'A license key for %s could not be found. Maybe you forgot to enter a license key when setting up %s, or the key was deactivated in your account. You can reactivate or purchase a license key from your account <a href="%s" target="_blank">dashboard</a>.', 'salong' ) . '</p></div>', esc_attr( $this->ame_software_product_id ), esc_attr( $this->ame_software_product_id ), esc_url( $this->ame_renew_license_url ) );
		}
		public function download_revoked_error_notice() {
			echo sprintf( '<div class="notice notice-info"><p>' . __( 'Download permission for %s has been revoked possibly due to a license key or subscription expiring. You can reactivate or purchase a license key from your account <a href="%s" target="_blank">dashboard</a>.', 'salong' ) . '</p></div>', esc_attr( $this->ame_software_product_id ), esc_url( $this->ame_renew_license_url ) );
		}
		public function no_activation_error_notice() {
			echo sprintf( '<div class="notice notice-info"><p>' . __( '%s has not been activated. Go to the settings page and enter the license key and license email to activate %s.', 'salong' ) . '</p></div>', esc_attr( $this->ame_software_product_id ), esc_attr( $this->ame_software_product_id ) );
		}
		public function switched_subscription_error_notice() {
			echo sprintf( '<div class="notice notice-info"><p>' . __( 'You changed the subscription for %s, so you will need to enter your new API License Key in the settings page. The License Key should have arrived in your email inbox, if not you can get it by logging into your account <a href="%s" target="_blank">dashboard</a>.', 'salong' ) . '</p></div>', esc_attr( $this->ame_software_product_id ), esc_url( $this->ame_renew_license_url ) );
		}

	}
}