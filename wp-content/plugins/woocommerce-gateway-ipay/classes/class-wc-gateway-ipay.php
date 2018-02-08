<?php

class WC_Gateway_iPay extends WC_Payment_Gateway {

    public function __construct() {
        $this->id = 'ipay';
        $this->icon = 'https://i-pay.co.za/images/i-pay-payment-logo-med.png';
        $this->method_title = 'i-Pay';
        $this->has_fields = false;
        $this->description = $this->get_option('description');
        $this->form_url = 'https://i-pay.co.za/payment';
        
        // Setup available countries.
        $this->available_countries = array( 'ZA' );

        // Setup available currency codes.
        $this->available_currencies = array( 'ZAR' );
        
        $this->init_settings();
        $this->init_form_fields();

        $this->title = $this->get_option('title');
        $this->response_url = add_query_arg( 'wc-api', 'WC_Gateway_iPay', home_url( '/' ) );
        $this->cancel_url = $this->response_url;
        $this->success_url = $this->response_url;
        $this->error_url = $this->response_url;
        $this->notify_url = add_query_arg( 'notify', '1', $this->response_url );
        $this->is_test_mode = $this->get_option('is_test_mode');
        $this->enabled = $this->get_option('enabled');

        $this->site_code = $this->get_option('site_code');
        $this->private_key = $this->get_option('private_key');
        $this->api_key = $this->get_option('api_key');
        
        if ($this->is_test_mode) {
            $this->form_fields['is_test_mode']['description'] .= ' <strong>' . __( 'You will not receive payments in test mode, DO NOT HONOUR ANY ORDERS PAID WHILE TEST MODE IS ON.', 'woocommerce-gateway-ipay' ) . '</strong>';
        }

        add_action('woocommerce_update_options_payment_gateways', array(&$this, 'process_admin_options'));
        add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));

        add_action('woocommerce_api_wc_gateway_ipay', array($this, 'process_ipay_response'));

        if (!$this->is_valid_for_use()) {
            $this->enabled = false;
        }
    }

    function is_valid_for_use() {
        return get_woocommerce_currency() == 'ZAR';
    }

    /**
     * Initialise Gateway Settings Form Fields
     */
    function init_form_fields() {
        $this->form_fields = array(
            'title' => array(
                'title' => __('Title', 'ipay_woocommerce'),
                'type' => 'text',
                'description' => __('This is the title that the user will see at checkout', 'ipay_woocommerce'),
                'default' => __('i-Pay', 'ipay_woocommerce')
            ),
            'description' => array(
                'title' => __('Description', 'ipay_woocommerce'),
                'type' => 'textarea',
                'description' => __( 'This controls the description which the user sees during checkout.', 'ipay_woocommerce'),
                'default' => __("Pay with i-Pay real-time EFT, (Your order status will be updated immediately after successful payment).",'ipay_woocommerce')
            ),
            'enabled' => array(
                'title' => __('Enable/Disable', 'ipay_woocommerce'),
                'type' => 'checkbox',
                'label' => __('Enable i-Pay for payment processing', 'ipay_woocommerce'),
                'default' => 'yes'
            ),
            'site_code' => array(
                'title' => __('Site Code', 'ipay_woocommerce'),
                'type' => 'text',
                'default' => ''
            ),
            'private_key' => array(
                'title' => __('Private Key', 'ipay_woocommerce'),
                'type' => 'text',
                'default' => ''
            ),
            'api_key' => array(
                'title' => __('API Key', 'ipay_woocommerce'),
                'type' => 'text',
                'default' => ''
            ),
            'is_test_mode' => array(
                'title' => __('Test Mode', 'ipay_woocommerce'),
                'type' => 'checkbox',
                'label' => __('Enable i-Pay Test Mode', 'ipay_woocommerce'),
                'default' => 'no'
            )
        );
    }

    function admin_options() {
        if ($this->is_valid_for_use()) {
            ?>
            <h2><?php _e('i-Pay', 'ipay_woocommerce'); ?></h2>
            <table class="form-table">
                <?php $this->generate_settings_html(); ?>
            </table> <?php
        } else {
            echo _e('Gateway Disabled', 'ipay_woocommerce') . '<div class="inline error"><p><strong>' . __('i-Pay currently only supports ZAR as a currency.', 'ipay_woocommerce') . '</strong></p></div>';
        }
    }

    function get_post_fields($order_id) {
        $order = new WC_Order($order_id);

        $order_total = $order->get_total();
        $currency_code = get_woocommerce_currency();
        $country_code = $order->billing_country;
        $site_code = $this->site_code;
        $order_key = $order->order_key;
        $private_key = $this->private_key;
        $plugin_name = "WooCommerce";
        $amount = number_format($order_total, 2, '.', '');
        $is_test = ($this->is_test_mode == 'yes') ? 'true' : 'false';

        $string_to_hash = strtolower($site_code . $country_code . $currency_code . $amount . $order_id . $order_id . $order_key . $plugin_name . $this->cancel_url . $this->error_url .
                $this->success_url . $this->notify_url . $is_test . $private_key);

        $hash = hash("sha512", $string_to_hash);

        $args = array(
            'SiteCode' => $site_code,
            'CountryCode' => empty($country_code) ? 'ZA' : $country_code,
            'CurrencyCode' => $currency_code,
            'Amount' => $amount,
            'TransactionReference' => $order_id,
            'BankReference' => $order_id,
            'Optional1' => $order_key,
            'Optional5' => $plugin_name,
            'CancelUrl' => $this->cancel_url,
            'ErrorUrl' => $this->error_url,
            'SuccessUrl' => $this->success_url,
            'NotifyUrl' => $this->notify_url,
            'IsTest' => $is_test,
            'HashCheck' => $hash,
        );

        return $args;
    }

    function process_payment($order_id) {
        $args = $this->get_post_fields($order_id);
        $post_args = http_build_query($args, '', '&');

        return array(
            'result' => 'success',
            'redirect' => $this->form_url . '?' . $post_args
        );
    }

    function api_verify_transaction($status, $amount, $transaction_reference, $transaction_id, $is_test) {
        $site_code = $this->site_code;
        $api_key = $this->api_key;
        $url = "https://i-pay.co.za/api/MerchantApiV1/GetTransaction?siteCode=$site_code&transactionId=$transaction_id&isTest=$is_test";

        $ch = curl_init();
        $headers = array(
            "Accept: application/json",
            "ApiKey: $api_key"
        );
        
        //$f = fopen(wp_upload_dir().'/ipay-api-request-errors.txt', 'w');

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        //curl_setopt($curl, CURLOPT_STDERR, $f);

        $result = curl_exec($ch);
        //fclose($f);

        if ($result === false) {
            return false;
        }

        $transaction = json_decode($result);
        if ($transaction->Amount !== floatval($amount) || $transaction->Status !== $status || $transaction->TransactionReference !== $transaction_reference) {
            return false;
        }
        
        return true;
    }

    function process_ipay_response() {
        global $woocommerce;
        $site_code = $_REQUEST['SiteCode'];
        $transaction_id = $_REQUEST['TransactionId'];
        $transaction_reference = $_REQUEST['TransactionReference'];
        $amount = $_REQUEST['Amount'];
        $status = $_REQUEST['Status'];
        $optional_1 = $_REQUEST['Optional1'];
        $optional_2 = $_REQUEST['Optional2'];
        $optional_3 = $_REQUEST['Optional3'];
        $optional_4 = $_REQUEST['Optional4'];
        $optional_5 = $_REQUEST['Optional5'];
        $currency_code = $_REQUEST['CurrencyCode'];
        $is_test = $_REQUEST['IsTest'];
        $status_message = $_REQUEST['StatusMessage'];
        $hash = $_REQUEST['Hash'];
        $notify = $_REQUEST['notify'];

        if(empty($is_test)) {
            $is_test = 'false';
        }
        
        $string_to_hash = strtolower($site_code . $transaction_id . $transaction_reference . $amount . $status . $optional_1 . $optional_2 . $optional_3 . $optional_4 . $optional_5 . $currency_code . $is_test . $status_message . $this->private_key);
        
        $hash_check = hash("sha512", $string_to_hash);
        $order = new WC_Order($transaction_reference);
        
        if ($order == null) {
            $error = sprintf(__('Order reference %s is invalid', 'ipay_woocommerce'), $transaction_reference);
            wc_add_notice($error, 'error');
            die($error);
        }

        if ($order->needs_payment()) {
            if ($hash != $hash_check) {
                $error = __('Your payment could not be processed. The response returned from i-Pay is invalid.', 'ipay_woocommerce');
                $order->update_status('on-hold', $error);
                wc_add_notice($error, 'error');

            } else if (!$this->api_verify_transaction($status, $amount, $transaction_reference, $transaction_id, $is_test)) {
                $error = __('Your payment could not be processed. We were unable to verify the status of your payment.', 'ipay_woocommerce');
                $order->update_status('on-hold', $error);
                wc_add_notice($error, 'error');

            } else if (floatval($amount) != floatval($order->get_total())) {
                $error = sprintf(__('Amount returned from i-Pay (%1s) does not match order total (%2s) .', 'ipay_woocommerce'), $amount, $order->get_total());
                $order->update_status('on-hold', $error);
                wc_add_notice($error, 'error');

            } else if (strtolower($status) == 'cancelled' || strtolower($status) == 'error') {
                $order->update_status('failed', $status_message);

            } else if (strtolower($status) == "complete") {
                if (strtolower($is_test) == 'true') {
                    wc_add_notice("The response received indicates that payment was successful however payment was made in test mode.", 'error');
                    $order->update_status('on-hold', "The response received indicates that payment was successful however payment was made in test mode. i-Pay Request ID ($transaction_id)");
                } else {
                    $order->payment_complete();
                    $order->add_order_note("Successful payment from i-Pay ($transaction_id)");
                }
                
            } else {
                $order->update_status('on-hold', "Unknown status received from i-Pay ($status) for transaction $transaction_id");

                wc_add_notice("There was a problem processing your payment, please contact us.", 'error');
            }
        }
        
        $thankyou_url = add_query_arg('utm_nooverride', '1', $this->get_return_url($order));
        
        if ($notify == 1) {
            exit();
        }
        
        wp_redirect($thankyou_url);
        exit();
    }
}
