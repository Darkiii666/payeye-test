<?php

namespace Payeye\Woocommerce\Database;

require_once(ABSPATH . 'wp-admin/includes/upgrade.php'); // Needed for dbDelta or maybe_create_table
abstract class AbstractDatabase
{
    protected $wpdb;
    protected string $tableName;
    abstract function initTable();
    abstract function destroyTable();
    public function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;
    }
}