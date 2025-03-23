<?php
/**
 * Registering WordPress shortcode for the plugin.
 *
 * @since   1.0.0
 * @package ElastiForm
 */

namespace ElastiForm;

// If direct access than exit the file.
defined('ABSPATH') || exit;

/**
 * Responsible for registering shortcode.
 *
 * @since   1.0.0
 * @package ElastiForm
 */
class FloatingWidget
{

    /**
     * Class constructor.
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        add_action('wp_footer', [ $this, 'display_floating_widget' ], 99);
    }

    public function display_floating_widget()
    {
        // Get the options from the options table
        $options = get_option('form_settings');

        if (isset($options['formCustomization']) && $options['formCustomization'] === 'true' ) {
            $submittext = isset($options['submitbtntext']) ? esc_html($options['submitbtntext']) : 'Send Message';
            $formheader = isset($options['formheader']) ? esc_html($options['formheader']) : 'Have question? - Submit the Form';

            $submitbtntextcolor = isset($options['submitbtntextcolor']) ? sanitize_hex_color($options['submitbtntextcolor']) : '#ffffff';
            $submitbtnbgcolor = isset($options['submitbtnbgcolor']) ? sanitize_hex_color($options['submitbtnbgcolor']) : 'orange';

            $headerbgcolor = isset($options['headerbackgroundcolor']) ? sanitize_hex_color($options['headerbackgroundcolor']) : 'orange';
            $headertextcolor = isset($options['headertextcolor']) ? sanitize_hex_color($options['headertextcolor']) : '#ffffff';

            $bodytextcolor = isset($options['formfieldtextcolor']) ? sanitize_hex_color($options['formfieldtextcolor']) : '#ffffff';
            $bodybgcolor = isset($options['formbackgroundcolor']) ? sanitize_hex_color($options['formbackgroundcolor']) : 'orange';

            $flotingwidgetsbgcolor = isset($options['flotingwidgetsbgcolor']) ? sanitize_hex_color($options['flotingwidgetsbgcolor']) : 'orange';
            $submitbtntexthovercolor = isset($options['submitbtntexthovercolor']) ? sanitize_hex_color($options['submitbtntexthovercolor']) : '#2196f3';
            $selectedFont = isset($options['selectedFont']) ? esc_html($options['selectedFont']) : 'Arial';
            $formcta = isset($options['formcta']) ? esc_html($options['formcta']) : 'Click to Chat';
        } else {
            $submittext = 'Send Message';
            $formheader = 'Have question? - Submit the Form';

            $headertextcolor = '#ffffff';
            $headerbgcolor = '#293239';

            $submitbtntextcolor = '#ffffff';
            $submitbtnbgcolor = 'orange';

            $bodytextcolor = '#293239';
            $bodybgcolor = '#f7f7f7';

            $flotingwidgetsbgcolor = '#0065a0';
            $submitbtntexthovercolor = '#2196f3';
            $selectedFont = 'Arial';
            $formcta = '';
        }

        // Check if floatingwidgets is true and selectedTable is set
        if (isset($options['floatingwidgets']) && $options['floatingwidgets'] === 'true' && isset($options['selectedTable']) ) {

            $selectedTable = $options['selectedTable'];
            $output = '
			<div class="floating-whatsapp">
				<button type="button" class="whatsapp-icon" id="jumping-whatsapp">
				<svg width="60" height="60" viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg">
					<g fill="none" fill-rule="evenodd">
						<circle cx="16" cy="16" r="16" fill="#1C98F7"/>
						<path fill="#FFF" d="M16.28 23.325a11.45 11.45 0 0 0 2.084-.34a5.696 5.696 0 0 0 2.602.17a.627.627 0 0 1 .104-.008c.31 0 .717.18 1.31.56v-.625a.61.61 0 0 1 .311-.531c.258-.146.498-.314.717-.499c.864-.732 1.352-1.708 1.352-2.742c0-.347-.055-.684-.159-1.006c.261-.487.472-.999.627-1.53A4.59 4.59 0 0 1 26 19.31c0 1.405-.654 2.715-1.785 3.673a5.843 5.843 0 0 1-.595.442v1.461c0 .503-.58.792-.989.493a15.032 15.032 0 0 0-1.2-.81a2.986 2.986 0 0 0-.368-.187c-.34.051-.688.077-1.039.077c-1.412 0-2.716-.423-3.743-1.134zm-7.466-2.922C7.03 18.89 6 16.829 6 14.62c0-4.513 4.258-8.12 9.457-8.12c5.2 0 9.458 3.607 9.458 8.12c0 4.514-4.259 8.121-9.458 8.121c-.584 0-1.162-.045-1.728-.135c-.245.058-1.224.64-2.635 1.67c-.511.374-1.236.013-1.236-.616v-2.492a9.27 9.27 0 0 1-1.044-.765zm4.949.666c.043 0 .087.003.13.01c.51.086 1.034.13 1.564.13c4.392 0 7.907-2.978 7.907-6.589c0-3.61-3.515-6.588-7.907-6.588c-4.39 0-7.907 2.978-7.907 6.588c0 1.746.821 3.39 2.273 4.62c.365.308.766.588 1.196.832c.241.136.39.39.39.664v1.437c1.116-.749 1.85-1.104 2.354-1.104zm-2.337-4.916c-.685 0-1.24-.55-1.24-1.226c0-.677.555-1.226 1.24-1.226c.685 0 1.24.549 1.24 1.226c0 .677-.555 1.226-1.24 1.226zm4.031 0c-.685 0-1.24-.55-1.24-1.226c0-.677.555-1.226 1.24-1.226c.685 0 1.24.549 1.24 1.226c0 .677-.555 1.226-1.24 1.226zm4.031 0c-.685 0-1.24-.55-1.24-1.226c0-.677.555-1.226 1.24-1.226c.685 0 1.24.549 1.24 1.226c0 .677-.555 1.226-1.24 1.226z"/>
					</g>
				</svg>';
            if (! empty($formcta) ) {
                $output .= '<span class="cta-text">' . esc_attr($formcta) . '</span>';
            }
            $output .= '</button>
				<div class="form-content">    
					<header class="clearfix">
						<h4 class="offline"><i class="fa fa-envelope"></i> ' . esc_attr($formheader) . '</h4>
						<span class="sf-close">X</span>
					</header>
					<div class="simple_form_container simple_form_container_floating ' . esc_attr($selectedTable) . '" data-form-id="' . esc_attr($selectedTable) . '" data-nonce="' . esc_attr(wp_create_nonce('ElastiForm_sheet_nonce_action')) . '">
						<form class="simple_form" data-form-id="' . esc_attr($selectedTable) . '" data-nonce="' . esc_attr(wp_create_nonce('ElastiForm_sheet_nonce_action')) . '">
							<div class="simple_form_content">
								<div class="ui segment simple_form_loader" id="' . esc_attr($selectedTable) . '"></div>
								<br>
								<div>
								<button type="button" class="submit-button main-search-btn">' . esc_attr($submittext) . '</button>
								</div>
							</div>
						</form>
					</div>
				</div>
					<style>	
						.simple_form_container_floating{
							background-color: ' . esc_attr($bodybgcolor) . '; 
							color: ' . esc_attr($bodytextcolor) . ';
						}
						header.clearfix{
							background-color: ' . esc_attr($headerbgcolor) . '; 
						}

						.floating-whatsapp .form-content h4{
							color: ' . esc_attr($headertextcolor) . ';
						}

						circle {
							fill: ' . esc_attr($flotingwidgetsbgcolor) . ';
						}
						.floating-whatsapp {
							font-family: ' . esc_attr($selectedFont) . ';
						}	
						.floating-whatsapp .main-search-btn{
							width: 100%;
							background-color: ' . esc_attr($submitbtnbgcolor) . '; 
							color: ' . esc_attr($submitbtntextcolor) . ';
						}
						.floating-whatsapp .main-search-btn:hover {
							background-color: ' . esc_attr($submitbtntexthovercolor) . ';
						}
					</style>
			</div>';

            $output .= '<script type="text/javascript"> var markupId = "' . esc_js($selectedTable) . '"; </script>';
            echo $output;
        }
    }
}
