<?php


class WC_Gateway_Online extends WC_Payment_Gateway
{
    protected string $instructions;

    /**
     * Constructor for the gateway.
     */
    public function __construct()
    {
        $this->id                 = 'online_gateway';
        $this->icon               = apply_filters('woocommerce_online_icon', '');
        $this->has_fields         = false;
        $this->method_title       = __('Online payment', 'wc-online-payment-emulator');
        $this->method_description = __('Online payment without any API action.', 'wc-online-payment-emulator');

        // Load the settings.
        $this->init_form_fields();
        $this->init_settings();

        // Define user set variables
        $this->title        = $this->get_option('title');
        $this->description  = $this->get_option('description');
        $this->instructions = $this->get_option('instructions', $this->description);

        // Actions
        add_action('woocommerce_update_options_payment_gateways_' . $this->id, [$this, 'process_admin_options']);
        add_action('woocommerce_thankyou_' . $this->id, [$this, 'thankyou_page']);

        add_action('woocommerce_receipt_'.$this->id, array($this, 'receipt_page'));

        // Customer Emails
        add_action('woocommerce_email_before_order_table', [$this, 'email_instructions'], 10, 3);
    }


    /**
     * Initialize Gateway Settings Form Fields
     */
    public function init_form_fields()
    {
        $this->form_fields = apply_filters('wc_online_form_fields', [

            'enabled' => [
                'title'   => __('Enable/Disable', 'wc-online-payment-emulator'),
                'type'    => 'checkbox',
                'label'   => __('Enable Online Payment', 'wc-online-payment-emulator'),
                'default' => 'yes'
            ],

            'title' => [
                'title'       => __('Title', 'wc-online-payment-emulator'),
                'type'        => 'text',
                'description' => __('This controls the title.', 'wc-online-payment-emulator'),
                'default'     => __('Online Payment', 'wc-online-payment-emulator'),
                'desc_tip'    => true,
            ],

            'description' => [
                'title'       => __('Description', 'wc-online-payment-emulator'),
                'type'        => 'textarea',
                'description' => __(
                    'Payment method description that the customer will see on your checkout.',
                    'wc-online-payment-emulator'
                ),
                'default'     => __('Please send me some money.', 'wc-online-payment-emulator'),
                'desc_tip'    => true,
            ],

            'instructions' => [
                'title'       => __('Instructions', 'wc-online-payment-emulator'),
                'type'        => 'textarea',
                'description' => __(
                    'Instructions that will be added to the thank you page and emails.',
                    'wc-online-payment-emulator'
                ),
                'default'     => '',
                'desc_tip'    => true,
            ],
        ]);
    }


    /**
     * Output for the order received page.
     */
    public function thankyou_page()
    {
        if (isset($this->instructions)) {
            echo wpautop(wptexturize($this->instructions));
        }
    }


    /**
     * Add content to the WC emails.
     *
     * @access public
     *
     * @param WC_Order $order
     * @param bool $sent_to_admin
     * @param bool $plain_text
     */
    public function email_instructions($order, $sent_to_admin, $plain_text = false)
    {
//        if (isset($this->instructions) && ! $sent_to_admin && $this->id === $order->get_payment_method()
//            && $order->has_status('on-hold'))
//        {
//           echo wpautop( wptexturize( $this->instructions ) ) . PHP_EOL;
//        }
    }

    public function receipt_page($order_id)
    {
        $order = wc_get_order($order_id);

        if ($order instanceof WC_Order) {
            $result = $this->virtual_pay($order);

            if (! empty($result['redirect'])) {
                wp_safe_redirect($result['redirect']);
                exit;
            }
        }

        wp_safe_redirect($this->get_return_url($order));
        exit;
    }


    /**
     * Process the payment and return the result
     */
    public function process_payment($order_id): array
    {
        $order = wc_get_order($order_id);

        return [
            'result'   => 'success',
            'redirect' => $order->get_checkout_payment_url(true),
        ];
    }

    private function real_pay()
    {

    }

    private function virtual_pay($order)
    {
        if (! ($order instanceof WC_Order)) {
            return [
                'result'   => 'failure',
                'redirect' => '',
            ];
        }

        if ($order->get_status() === 'pending') {
            $bill_id = wp_rand(0, 99999999999999999);
            $order->set_transaction_id((string) $bill_id);

            $order->add_order_note(__('The client started paying.', 'wc-online-payment-emulator'));

            wc_reduce_stock_levels($order->get_id());
            wc_empty_cart();

            $order->add_order_note(__('The client completed the payment.', 'wc-online-payment-emulator'));
            $order->payment_complete();

            if ($order->get_status() !== 'processing') {
                $order->update_status('processing', __('Payment emulator: set status to processing.', 'wc-online-payment-emulator'));
            }
        } elseif (! $order->is_paid() && $order->get_status() === 'cancelled') {
            error_log('fail');
        } else {
            error_log('..');
        }

        return [
            'result'   => 'success',
            'redirect' => $order->get_checkout_order_received_url(),
        ];
    }

}

