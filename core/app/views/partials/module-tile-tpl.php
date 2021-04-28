<?php
/**
 * Tile modules template.
 * Imported via the Orbit_Fox_Render_Helper.
 *
 * @link       https://themeisle.com
 * @since      1.0.0
 *
 * @package    Orbit_Fox
 * @subpackage Orbit_Fox/app/views/partials
 */

if ( ! isset( $name ) ) {
	$name = __( 'Module Name', 'themeisle-companion' );
}

if ( ! isset( $description ) ) {
	$description = __( 'Module Description ...', 'themeisle-companion' );
}

if ( ! isset( $checked ) ) {
	$checked = '';
}
if ( ! isset( $beta ) ) {
	$beta = false;
}

$toggle_class = 'obfx-mod-switch';

if ( ! empty( $confirm_intent ) ) {

	$toggle_class .= ' obfx-mod-confirm-intent';
	$modal         = '
        <div id="' . esc_attr( $slug ) . '" class="modal">
            <a href="#close" class="close-confirm-intent modal-overlay" aria-label="Close"></a>
            <div class="modal-container"> 
                <div class="modal-header">
                    <a href="#" class="btn btn-clear float-right close-confirm-intent" aria-label="Close"></a>
                </div>
                <div class="modal-body">' . wp_kses_post( $confirm_intent ) . '</div>
                <div class="modal-footer">
                        <button class="btn btn-primary accept-confirm-intent">' . __( 'Got it!', 'themeisle-companion' ) . '</button>
                </div>
            </div>
        </div>';
}

$noance = wp_create_nonce( 'obfx_activate_mod_' . $slug );

?>
<div class="tile <?php echo 'obfx-tile-' . esc_attr( $slug ); ?>" >
	<div class="tile-icon">
		<div class="example-tile-icon">
			<i class="dashicons dashicons-admin-plugins centered"></i>
		</div>
	</div>
	<div class="tile-content">
		<p class="tile-title"><?php echo wp_kses_post( $name ); ?></p>
		<p class="tile-subtitle"><?php echo wp_kses_post( $description ); ?></p>
	</div>
	<div class="tile-action">
		<div class="form-group">
			<label class="form-switch <?php echo empty( $checked ) ? '' : 'activated'; ?>">
				<input class="<?php echo esc_attr( $toggle_class ); ?>" type="checkbox" name="<?php echo esc_attr( $slug ); ?>" value="<?php echo esc_attr( $noance ); ?>" <?php echo esc_attr( $checked ); ?> >
				<i class="form-icon"></i>
				<span class="inactive"><?php echo esc_html__( 'Activate', 'themeisle-companion' ); ?></span>
				<i class="dashicons dashicons-yes"></i>

			</label>
			<?php if ( $beta ) { ?>
				<p class="obfx-beta-module"><?php echo esc_html__( 'Beta module', 'themeisle-companion' ); ?></p>
			<?php } ?>
			<?php
			if ( ! empty( $modal ) ) {
				echo ( $modal ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
			?>
			<?php do_action( 'obfx_activate_btn_before', $slug, $checked === 'checked' ); ?>
		</div>
	</div>
	<?php do_action( 'obfx_module_tile_after', $slug, $checked === 'checked' ); ?>
</div>

