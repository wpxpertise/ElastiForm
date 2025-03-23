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
class Settings
{
    /**
     * @return mixed
     */
    public function tableStylesArray(): array
    {
        $stylesArray = [
        'default-style' => [
        'imgUrl'    => ELASTIFORMBASE_URL . '',
        'inputName' => 'tableStyle',
        'isPro'     => false,
        'isChecked' => true,
        'label'     => 'Default Style',
        ],
        'style-1'       => [
        'imgUrl'    => ELASTIFORMBASE_URL . 'assets/public/images/',
        'inputName' => 'tableStyle',
        'isPro'     => true,
        'isChecked' => false,
        'label'     => 'Style 1',
        ],

        ];

        $stylesArray = apply_filters('ElastiForm_table_styles', $stylesArray);
        return $stylesArray;
    }

    /**
     * @return array
     */
    public function scrollHeightArray(): array
    {
        $scrollHeights = [
        '200'  => [
        'val'   => '200px',
        'isPro' => true,
        ],

        ];

        $scrollHeights = apply_filters('ElastiForm_table_scorll_height', $scrollHeights);

        return $scrollHeights;
    }

    /**
     * @return array
     */
    public function responsiveStyle()
    {
        $responsiveStyles = [
        'default_style'  => [
        'val'   => 'Default Style',
        'isPro' => false,
        ],
        ];

        $responsiveStyles = apply_filters('ElastiForm_responsive_styles', $responsiveStyles);

        return $responsiveStyles;
    }

    /**
     * @return array
     */
    public function displaySettingsArray(): array
    {
        $settingsArray = [
        'table_title'          => [
        'feature_title' => __('Table Title', 'elastiform'),
        'feature_desc'  => __('Enable this to show the table title in <i>h3</i> tag above the table in your website front-end', 'elastiform'),
        'input_name'    => 'show_title',
        'checked'       => false,
        'type'          => 'checkbox',
        'show_tooltip'  => true,
        ],
        'show_info_block'      => [
        'feature_title' => __('Show info block', 'elastiform'),
        'feature_desc'  => __('Show <i>Showing X to Y of Z entries</i>block below the table', 'elastiform'),
        'input_name'    => 'info_block',
        'checked'       => true,
        'type'          => 'checkbox',
        'show_tooltip'  => true,

        ],

        ];

        $settingsArray = apply_filters('ElastiForm_display_settings_arr', $settingsArray);

        return $settingsArray;
    }

    /**
     * @return array
     */
    public function sortAndFilterSettingsArray(): array
    {
        $settingsArray = [
        'allow_sorting' => [
        'feature_title' => __('Allow Sorting', 'elastiform'),
        'feature_desc'  => __('Enable this feature to sort table data for frontend.', 'elastiform'),
        'input_name'    => 'sorting',
        'checked'       => true,
        'type'          => 'checkbox',
        'show_tooltip'  => true,
        ],
        'search_bar'    => [
        'feature_title' => __('Search Bar', 'elastiform'),
        'feature_desc'  => __('Enable this feature to show a search bar in for the table. It will help user to search data in the table', 'elastiform'),
        'input_name'    => 'search_table',
        'checked'       => true,
        'type'          => 'checkbox',
        'show_tooltip'  => true,
        ],
        ];

        $settingsArray = apply_filters('ElastiForm_sortfilter_settings_arr', $settingsArray);

        return $settingsArray;
    }

    /**
     * @return array
     */
    public function rowsPerPage(): array
    {
        $rowsPerPage = [
        '1'   => [
        'val'   => 1,
        'isPro' => false,
        ],
        '5'   => [
        'val'   => 5,
        'isPro' => false,
        ],
        '10'  => [
        'val'   => 10,
        'isPro' => false,
        ],

        ];

        $rowsPerPage = apply_filters('ElastiForm_rows_per_page', $rowsPerPage);

        return $rowsPerPage;
    }

    /**
     * @return array
     */
    public function cellFormattingArray(): array
    {
        $cellFormats = [
        'wrap'   => [
        'val'   => 'Wrap Style',
        'isPro' => true,
        ],
        'expand' => [
        'val'   => 'Expanded Style',
        'isPro' => true,
        ],
        ];

        $cellFormats = apply_filters('ElastiForm_cell_format', $cellFormats);

        return $cellFormats;
    }

    /**
     * @return mixed
     */
    public function redirectionTypeArray(): array
    {
        $redirectionTypes = [
        '_blank' => [
        'val'   => 'Blank Type',
        'isPro' => true,
        ],
        '_self'  => [
        'val'   => 'Self Type',
        'isPro' => true,
        ],
        ];

        $redirectionTypes = apply_filters('ElastiForm_redirection_types', $redirectionTypes);

        return $redirectionTypes;
    }
}
