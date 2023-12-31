<?php

namespace Payeye\Woocommerce\Helpers;

use Payeye\Woocommerce\Api\OrderApi;
use Payeye\Woocommerce\Database\AbstractDatabase;
use Payeye\Woocommerce\Exceptions\MissingPluginException;
class Plugin
{
    public function checkDependencies(): bool
    {
        if (!class_exists('woocommerce')) {
            throw new MissingPluginException(__('This plugins needs WooCommerce to work', 'payeye'));
        }
        return true;
    }
    public function init() {
        try {
            if (!$this->checkDependencies()) {
                return;
            }
            $this->initApi();
            $this->initTables();

        } catch (MissingPluginException $missingPluginException) {
            add_action('admin_notices', function () use ($missingPluginException) {
                echo sprintf('<div class="notice notice-error"><p>%s</p></div>', $missingPluginException->getMessage());
            });
        }
    }
    public function __construct()
    {
        add_action('plugins_loaded', [$this, 'init']);
    }

    protected function initApi()
    {
        new OrderApi();
    }
    protected function initTables() {
        $databases = [
            'LogsDatabase',
        ];
        $databaseNameSpace = "Payeye\Woocommerce\Database\\";
        foreach ($databases as $database) {
            $className = $databaseNameSpace . $database;
            if (!class_exists($className)) {
                continue;
            }
            $db = new $className;
            if (!$db instanceof AbstractDatabase) {
                continue;
            }
            $db->initTable();
        }
    }
}