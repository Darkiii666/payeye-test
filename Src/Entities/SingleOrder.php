<?php

namespace Payeye\Woocommerce\Entities;

use Payeye\Woocommerce\Exceptions\InvalidStatusException;

class SingleOrder extends Entity
{
    protected int $id;
    protected \WC_Order $order;

    public function __construct(int $id) {
        $this->id = $id;
        $this->order = wc_get_order($id);
    }
    public static function getAllowedStatuses(): array {
        return ['SUCCESS', 'REJECTED'];
    }
    public static function getInstance(int $id) {
        $order = wc_get_order($id);
        if (!$order instanceof \WC_Order) {
            return false;
        }
        return new self($id);
    }
    public function setStatus($status): bool
    {
        if (!in_array($status, self::getAllowedStatuses())) {
            return false;
        }
        update_post_meta($this->id, 'payeye_status', $status);
        return true;
    }
    public function getStatus() {
        $status = get_post_meta($this->id, 'payeye_status', true);
        if (!in_array($status, self::getAllowedStatuses())) {
            return 'UNKNOWN';
        }
        return $status;
    }
    public function getId(): int
    {
        return $this->id;
    }
}