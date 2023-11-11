<?php

namespace Payeye\Woocommerce\Api;

use Payeye\Woocommerce\Traits\AuthApiTrait;
use WP_REST_Controller;
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

    protected function updateOrderStatus()
    {
        echo 'test';
    }

    public function __construct()
    {
        $this->namespace = 'payeye/v1';
        $this->rest_base = 'orders';
        add_action('rest_api_init', [$this, 'registerRoutes']);
    }
}