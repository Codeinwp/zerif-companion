<?php
/**
 * Class that handles plugins installation.
 */

/**
 * Class Orbit_Fox_Plugin_Install
 */
class Orbit_Fox_Plugin_Install {

	/**
	 * Get info from wordpress.org api.
	 *
	 * @param string $slug Plugin slug.
	 *
	 * @return array|mixed|object|WP_Error
	 */
	public function call_plugin_api( $slug ) {
		include_once ABSPATH . 'wp-admin/includes/plugin-install.php';

		$call_api = get_transient( 'ti_plugin_info_' . $slug );

		if ( false === $call_api ) {
			$call_api = plugins_api(
				'plugin_information',
				array(
					'slug'   => $slug,
					'fields' => array(
						'downloaded'        => false,
						'rating'            => false,
						'description'       => false,
						'short_description' => true,
						'donate_link'       => false,
						'tags'              => false,
						'sections'          => true,
						'homepage'          => true,
						'added'             => false,
						'last_updated'      => false,
						'compatibility'     => false,
						'tested'            => false,
						'requires'          => false,
						'downloadlink'      => false,
						'icons'             => true,
						'banners'           => true,
					),
				)
			);
			set_transient( 'ti_plugin_info_' . $slug, $call_api, 1 * DAY_IN_SECONDS );
		}

		return $call_api;
	}

	/**
	 * Get plugin icon.
	 *
	 * @param array $arr Icon sizes.
	 *
	 * @return string
	 */
	public function check_for_icon( $arr ) {
		if ( ! empty( $arr['svg'] ) ) {
			$plugin_icon_url = $arr['svg'];
		} elseif ( ! empty( $arr['2x'] ) ) {
			$plugin_icon_url = $arr['2x'];
		} elseif ( ! empty( $arr['1x'] ) ) {
			$plugin_icon_url = $arr['1x'];
		} else {
			$plugin_icon_url = $arr['default'];
		}

		return $plugin_icon_url;
	}

	/**
	 * Check plugin state.
	 *
	 * @param string $slug plugin slug.
	 *
	 * @return bool
	 */
	public function check_plugin_state( $slug ) {

		$plugin_link_suffix = self::get_plugin_path( $slug );

		if ( file_exists( ABSPATH . 'wp-content/plugins/' . $plugin_link_suffix ) ) {
			return is_plugin_active( $plugin_link_suffix ) ? 'deactivate' : 'activate';
		}

		return 'install';
	}


	/**
	 * Get plugin path based on plugin slug.
	 *
	 * @param string $slug Plugin slug.
	 *
	 * @return string
	 */
	public static function get_plugin_path( $slug ) {

		switch ( $slug ) {
			case 'wpforms-lite':
				return $slug . '/wpforms.php';
			case 'translatepress-multilingual':
				return $slug . '/index.php';
			case 'feedzy-rss-feeds':
				return $slug . '/feedzy-rss-feed.php';
			case 'wordpress-seo':
				return $slug . '/wp-seo.php';
			default:
				return $slug . '/' . $slug . '.php';
		}
	}

	/**
	 * Generate action button html.
	 *
	 * @param string $slug     plugin slug.
	 * @param array  $settings button settings.
	 *
	 * @return string
	 */
	public function get_button_html( $slug, $settings = array() ) {
		$button   = '';
		$redirect = '';
		if ( ! empty( $settings ) && array_key_exists( 'redirect', $settings ) ) {
			$redirect = $settings['redirect'];
		}
		$state = $this->check_plugin_state( $slug );
		if ( empty( $slug ) ) {
			return '';
		}

		$additional = '';

		if ( $state === 'deactivate' ) {
			$additional = ' action_button active';
		}

		$button .= '<div class=" plugin-card-' . esc_attr( $slug ) . esc_attr( $additional ) . '" style="padding: 8px 0 5px;">';

		$plugin_link_suffix = self::get_plugin_path( $slug );

		$nonce = add_query_arg(
			array(
				'action'        => 'activate',
				'plugin'        => rawurlencode( $plugin_link_suffix ),
				'plugin_status' => 'all',
				'paged'         => '1',
				'_wpnonce'      => wp_create_nonce( 'activate-plugin_' . $plugin_link_suffix ),
			),
			esc_url( network_admin_url( 'plugins.php' ) )
		);
		switch ( $state ) {
			case 'install':
				$button .= '<a data-redirect="' . esc_url( $redirect ) . '" data-slug="' . esc_attr( $slug ) . '" class="install-now obfx-install-plugin button button-primary" href="' . esc_url( $nonce ) . '" data-name="' . esc_attr( $slug ) . '" aria-label="Install ' . esc_attr( $slug ) . '"><span class="dashicons dashicons-download"></span>' . __( 'Install and activate', 'themeisle-companion' ) . '</a>';
				break;

			case 'activate':
				$button .= '<a  data-redirect="' . esc_url( $redirect ) . '" data-slug="' . esc_attr( $slug ) . '" class="activate-now button button-primary" href="' . esc_url( $nonce ) . '" aria-label="Activate ' . esc_attr( $slug ) . '">' . esc_html__( 'Activate', 'themeisle-companion' ) . '</a>';
				break;

			case 'deactivate':
				$nonce = add_query_arg(
					array(
						'action'        => 'deactivate',
						'plugin'        => rawurlencode( $plugin_link_suffix ),
						'plugin_status' => 'all',
						'paged'         => '1',
						'_wpnonce'      => wp_create_nonce( 'deactivate-plugin_' . $plugin_link_suffix ),
					),
					esc_url( network_admin_url( 'plugins.php' ) )
				);

				$button .= '<a  data-redirect="' . esc_url( $redirect ) . '" data-slug="' . esc_attr( $slug ) . '" class="deactivate-now button button-primary" href="' . esc_url( $nonce ) . '" data-name="' . esc_attr( $slug ) . '" aria-label="Deactivate ' . esc_attr( $slug ) . '">' . esc_html__( 'Deactivate', 'themeisle-companion' ) . '</a>';
				break;

			default:
				break;
		}
		$button .= '</div>';

		return $button;
	}
}
