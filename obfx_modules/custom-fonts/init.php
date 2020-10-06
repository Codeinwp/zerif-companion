<?php
/**
 * The class defines the Custom fonts module.
 *
 * @package    Custom_Fonts_OBFX_Module
 * @author     Themeisle <friends@themeisle.com>
 * @codeCoverageIgnore
 */

// Include custom fonts admin
require_once OBX_PATH . '/obfx_modules/custom-fonts/admin/custom_fonts_admin.php';

// Include custom fonts public
require_once OBX_PATH . '/obfx_modules/custom-fonts/public/custom_fonts_public.php';


/**
 * Class Custom_Fonts_OBFX_Module
 */
class Custom_Fonts_OBFX_Module extends Orbit_Fox_Module_Abstract {
	
	/**
	 * Menu_Icons_OBFX_Module constructor.
	 *
	 * @since   2.10
	 * @access  public
	 */
	public function __construct() {
		parent::__construct();
		$this->name = sprintf(
		/* translators: %s is New tag */
			__( 'Custom fonts %s', 'themeisle-companion' ),
			sprintf(
			/* translators: %s is New tag text */
				'<sup class="obfx-title-new">%s</sup>',
				__( 'NEW', 'themeisle-companion' )
			)
		);
		$this->description    = __( 'Upload custom fonts and use them anywhere on your site.', 'themeisle-companion' );
		$this->active_default = true;
	}
	
	/**
	 * Determine if module should be loaded.
	 *
	 * @since   2.10
	 * @access  public
	 * @return bool
	 */
	public function enable_module() {
		return true;
	}
	
	/**
	 * The loading logic for the module.
	 *
	 * @since   2.10
	 * @access  public
	 */
	public function load() {
	}
	
	/**
	 * Method to define hooks needed.
	 *
	 * @since   2.10
	 * @access  public
	 */
	public function hooks() {
		$admin_instance = new Custom_Fonts_Admin();
		$this->loader->add_action( 'init', $admin_instance, 'create_taxonomy' );
		$this->loader->add_action( 'admin_menu', $admin_instance, 'add_to_menu' );
		$this->loader->add_action( 'admin_head', $admin_instance, 'edit_custom_font_form' );
		$this->loader->add_filter( 'manage_edit-obfx_custom_fonts_columns', $admin_instance, 'manage_columns' );
		$this->loader->add_action( 'obfx_custom_fonts_add_form_fields', $admin_instance, 'add_new_taxonomy_data' );
		$this->loader->add_action( 'obfx_custom_fonts_edit_form_fields', $admin_instance, 'edit_taxonomy_data' );
		$this->loader->add_action( 'edited_obfx_custom_fonts', $admin_instance, 'save_metadata' );
		$this->loader->add_action( 'create_obfx_custom_fonts', $admin_instance, 'save_metadata' );
		$this->loader->add_filter( 'upload_mimes', $admin_instance, 'add_fonts_to_allowed_mimes' );
		$this->loader->add_filter( 'wp_check_filetype_and_ext', $admin_instance, 'update_mime_types', 10, 3 );
		
		$public_instance = new Custom_Fonts_Public();
		$this->loader->add_filter( 'neve_react_controls_localization', $public_instance, 'add_custom_fonts' );
		$this->loader->add_action( 'wp_head', $public_instance, 'add_style' );
		if ( is_admin() ) {
			$this->loader->add_action( 'enqueue_block_assets', $this, 'add_style' );
		}
	}
	
	/**
	 * Method to define the options fields for the module
	 *
	 * @since   2.10
	 * @access  public
	 * @return array
	 */
	public function options() {
		return array();
	}
	
	
	/**
	 * Method that returns an array of scripts and styles to be loaded
	 * for the admin part.
	 *
	 * @since   2.10
	 * @access  public
	 * @return array
	 */
	public function admin_enqueue() {
		return array(
			'css' => array( 'admin' => array(), ),
		);
	}
	
	/**
	 * Method that returns an array of scripts and styles to be loaded
	 * for the front end part.
	 *
	 * @since   2.10
	 * @access  public
	 * @return array
	 */
	public function public_enqueue() {
		return array();
	}
	
}
