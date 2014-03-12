<?php
/**
 * @package ttf-one
 */

if ( ! function_exists( 'ttf_one_customizer_init' ) ) :
/**
 * Load the customizer files and enqueue scripts
 *
 * @since 1.0
 */
function ttf_one_customizer_init() {
	$path = '/inc/customizer/';

	// Only load these on the backend
	if ( is_admin() ) {
		require_once( get_template_directory() . $path . 'customizer.php' );
	}

	// Always load these
	require_once( get_template_directory() . $path . 'helpers.php' );

	// Hook up functions
	add_action( 'customize_register', 'ttf_one_customizer_add_sections' );
	add_action( 'customize_controls_enqueue_scripts', 'ttf_one_customizer_admin_scripts' );
	add_action( 'customize_controls_print_styles', 'ttf_one_customizer_admin_styles' );
}
endif;

add_action( 'after_setup_theme', 'ttf_one_customizer_init' );

if ( ! function_exists( 'ttf_one_customizer_add_sections' ) ) :
/**
 * Add sections and controls to the customizer.
 *
 * Hooked to 'customize_register' via ttf_one_customizer_init()
 *
 * @since 1.0
 *
 * @param object $wp_customize
 */
function ttf_one_customizer_add_sections( $wp_customize ) {
	$path = '/inc/customizer/';

	// Get the custom controls
	require_once( get_template_directory() . $path . 'controls.php' );

	// Modifications for existing sections
	require_once( get_template_directory() . $path . 'site-title-tagline.php' );
	require_once( get_template_directory() . $path . 'navigation.php' );

	// List of new sections to add
	$sections = array(
		'general'    => __( 'General', 'ttf-one' ),
		'logo'       => __( 'Logo', 'ttf-one' ),
		'background' => __( 'Background', 'ttf-one' ),
		'fonts'      => __( 'Fonts', 'ttf-one' ),
		'colors'     => __( 'Colors', 'ttf-one' ),
		'header'     => __( 'Header', 'ttf-one' ),
		'footer'     => __( 'Footer', 'ttf-one' ),
		'social'     => __( 'Social Profiles &amp; RSS', 'ttf-one' )
	);
	$sections = apply_filters( 'ttf_one_customizer_sections', $sections );

	// Priority for first section
	$priority = new TTF_One_Prioritizer( 200 );

	// Add and populate each section, if it exists
	foreach ( $sections as $section => $title ) {
		// First load the file
		if ( '' !== locate_template( $path . $section . '.php', true ) ) {
			// Then add the section
			if ( function_exists( 'ttf_one_customizer_' . $section ) ) {
				$section_id = 'ttf-one_' . esc_attr( $section );
				if ( ! $title ) {
					$title = ucfirst( esc_attr( $section ) );
				}

				// Add section
				$wp_customize->add_section(
					$section_id,
					array(
						'title'    => $title,
						'priority' => $priority->add(),
					)
				);

				// Callback to populate the section
				call_user_func_array(
					'ttf_one_customizer_' . esc_attr( $section ),
					array(
						$wp_customize,
						$section_id
					)
				);
			}
		}
	}
}
endif;

if ( ! function_exists( 'ttf_one_customizer_admin_scripts' ) ) :
/**
 * Enqueue customizer admin scripts
 *
 * Hooked to 'admin_enqueue_scripts' via ttf_one_customizer_init()
 *
 * @since 1.0
 */
function ttf_one_customizer_admin_scripts() {
	$path = '/inc/customizer/js/';

	wp_enqueue_script(
		'ttf-one-customizer-admin',
		get_template_directory() . $path . 'customizer' . TTF_ONE_SUFFIX . '.js',
		array( 'customize-preview' ),
		TTF_ONE_VERSION,
		true
	);
}
endif;

if ( ! function_exists( 'ttf_one_customizer_admin_styles' ) ) :
/**
 * Styles for our Customizer sections and controls. Prints in the <head>
 *
 * @since 1.0
 */
function ttf_one_customizer_admin_styles() { ?>
	<style type="text/css">
		#customize-theme-controls .control-section[id*="ttf-one_"] .accordion-section-title {
			background-color: #f9f9f9;
		}
	</style>
<?php }
endif;