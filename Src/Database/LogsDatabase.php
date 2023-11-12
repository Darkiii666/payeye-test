<?php

namespace Payeye\Woocommerce\Database;

class LogsDatabase extends AbstractDatabase
{
    protected string $tableName = 'payeye_logs';

    public function __construct()
    {
        parent::__construct();
        $this->tableName = $this->wpdb->prefix . $this->tableName;
    }

    public function initTable()
    {
        $charsetCollate = $this->wpdb->get_charset_collate();

        $sql = sprintf("CREATE TABLE %s (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            orderid bigint(20) NOT NULL,
            create_date datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
            status varchar(20) NOT NULL,
            additional_info text,
            PRIMARY KEY  (id),
            KEY orderid (orderid),
            KEY create_date (create_date),
            KEY status (status)
        ) %s;", $this->tableName, $charsetCollate);;
        dbDelta($sql);
    }

    public function destroyTable()
    {
        $this->wpdb->query(sprintf("DROP TABLE IF EXISTS %s", $this->tableName));
    }

    public function insertRecord(int $orderId, string $status, $additionalInfo = '')
    {
        $data = [
            'orderId' => $orderId,
            'status' => $status,
            'additional_info' => $additionalInfo
        ];
        $format = ['%d', '%s', '%s'];
        $result = $this->wpdb->insert($this->tableName, $data, $format);
    }
}