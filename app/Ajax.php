<?php
/**
 * Responsible for managing ajax endpoints.
 *
 * @since   1.0.0
 * @package ElastiForm
 */

namespace ElastiForm;

// If direct access than exit the file.
defined('ABSPATH') || exit;

/**
 * Responsible for handling ajax endpoints.
 *
 * @since   1.0.0
 * @package ElastiForm
 */
class Ajax
{

    /**
     * Contains promotional wppool products.
     *
     * @var \ElastiForm\Ajax\FetchProducts
     */
    public $products;

    /**
     * Contains plugins notices ajax operations.
     *
     * @var \ElastiForm\Ajax\ManageNotices
     */
    public $notices;

    /**
     * Contains table delete ajax operations.
     *
     * @var \ElastiForm\Ajax\UdTables
     */
    public $ud_tables;

    /**
     * Contains plugin tables ajax operations.
     *
     * @var mixed
     */
    public $tables;

    /**
     * Contains plugin tabs ajax operations.
     *
     * @var mixed
     */
    public $tabs;

    /**
     * Contains plugin settings ajax endpoints.
     *
     * @var mixed
     */
    public $settings;

    /**
     * Class constructor.
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        $this->tables   = new \ElastiForm\Ajax\Tables();
    }
}
