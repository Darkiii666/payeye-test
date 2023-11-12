<?php

namespace Payeye\Woocommerce\Api;

use Payeye\Woocommerce\Entities\SingleOrder;
use Payeye\Woocommerce\Logger\DatabaseLogger;
use Payeye\Woocommerce\Traits\AuthApiTrait;
use WP_Error;
use WP_REST_Controller;
use WP_REST_Request;
use WP_REST_Server;

class OrderApi extends WP_REST_Controller
{
    use AuthApiTrait;

    /**
     * The namespace.
     *
     * @var string
     */
    protected $namespace;
    /**
     * Rest base for the current object.
     *
     * @var string
     */
    protected $rest_base;

    public function registerRoutes()
    {
        register_rest_route($this->namespace, '/' . $this->rest_base, [
            [
                'methods' => WP_REST_Server::EDITABLE,
                'callback' => [$this, 'updateOrderStatus'],
                'permission_callback' => [$this, 'checkApiKey'],
                'args' => $this->get_endpoint_args_for_item_schema(false),
            ],
            'schema' => null,
        ]);

    }

    public function updateOrderStatus(WP_REST_Request $request)
    {
        $logger = new DatabaseLogger();
        $data = $request->get_body();

        $context = [];
        if (empty($data)) {
            $logger->error('Empty Data body', $context);
            return new WP_Error( 'invalid_data', __( 'Invalid Data', 'payeye' ) );
        }
        $data = json_decode($data, true);
        $orderId = (isset($data['orderNumber']) && is_numeric($data['orderNumber'])) ? $data['orderNumber'] : null;

        $order = SingleOrder::getInstance($orderId);
        if(!$order instanceof SingleOrder) {
            $logger->error("Order not found: $orderId", $context);
            return new WP_Error('invalid_data', __('Invalid Order Id', 'payeye'));
        }
        $context['orderId'] = $orderId;

        $allowedOrderStatuses = SingleOrder::getAllowedStatuses();
        $orderStatus = (isset($data['orderStatus']) && in_array($data['orderStatus'], $allowedOrderStatuses)) ? $data['orderStatus'] : null;
        if (!in_array($orderStatus, $allowedOrderStatuses)) {
            $logger->error("Wrong order status: " . $data['orderStatus'], $context );
            return new WP_Error('invalid_data', __('Invalid Status', 'payeye'));
        }
        $context['status'] = $orderStatus;
        $order->setStatus($orderStatus);

        $logger->notice( 'Status changed', $context);
        return new \WP_REST_Response(['OK']);
    }

    public function __construct()
    {
        $this->namespace = 'payeye/v1';
        $this->rest_base = 'orders';
        add_action('rest_api_init', [$this, 'registerRoutes']);
    }
}