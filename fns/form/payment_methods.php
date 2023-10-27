<?php

if (role(['permissions' => ['super_privileges' => 'manage_payment_gateways']])) {

    $todo = 'add';

    $form['loaded'] = new stdClass();
    $form['fields'] = new stdClass();

    if (isset($load["payment_gateway_id"])) {

        $todo = 'update';


        $columns = $join = $where = null;
        $columns = [
            'payment_gateways.payment_gateway_id ', 'payment_gateways.identifier', 'payment_gateways.credentials',
            'payment_gateways.disabled'
        ];

        $where["payment_gateways.payment_gateway_id"] = $load["payment_gateway_id"];
        $where["LIMIT"] = 1;

        $method = DB::connect()->select('payment_gateways', $columns, $where);

        if (!isset($method[0])) {
            return false;
        } else {
            $method = $method[0];
        }

        $form['fields']->payment_gateway_id = [
            "tag" => 'input', "type" => 'hidden', "class" => 'd-none', "value" => $load["payment_gateway_id"]
        ];

        $form['loaded']->title = Registry::load('strings')->edit_custom_field;
        $form['loaded']->button = Registry::load('strings')->update;
    } else {
        $form['loaded']->title = Registry::load('strings')->add_payment_method;
        $form['loaded']->button = Registry::load('strings')->add;
    }

    $form['fields']->$todo = [
        "tag" => 'input', "type" => 'hidden', "class" => 'd-none', "value" => "payment_methods"
    ];

    $form['fields']->payment_method = [
        "title" => Registry::load('strings')->payment_method, "tag" => 'select', "class" => 'field toggle_form_fields'
    ];
    $form['fields']->payment_method ["options"] = ['paypal' => 'PayPal', 'stripe' => 'Stripe'];

    $form['fields']->payment_method["attributes"] = [
        "hide_field" => "payment_method_fields",
        "show_fields" => "paypal|paypal_fields,stripe|stripe_fields"
    ];



    $form['fields']->paypal_client_id = [
        "title" => 'Client ID', "tag" => 'input', "type" => "text",
        "class" => 'field payment_method_fields paypal_fields',
    ];

    $form['fields']->paypal_client_secret = [
        "title" => 'Client Secret', "tag" => 'input', "type" => "text",
        "class" => 'field payment_method_fields paypal_fields',
    ];

    $form['fields']->paypal_test_mode = [
        "title" => 'Test Mode', "tag" => 'select',
        "class" => 'field payment_method_fields paypal_fields',
    ];

    $form['fields']->paypal_test_mode ["options"] = [
        "yes" => Registry::load('strings')->yes,
        "no" => Registry::load('strings')->no
    ];


    $form['fields']->strip_secret_key = [
        "title" => 'Secret API Key', "tag" => 'input', "type" => "text",
        "class" => 'field payment_method_fields stripe_fields',
    ];


    $form['fields']->disabled = [
        "title" => Registry::load('strings')->disabled, "tag" => 'select', "class" => 'field'
    ];
    $form['fields']->disabled['options'] = [
        "yes" => Registry::load('strings')->yes,
        "no" => Registry::load('strings')->no,
    ];




    if (isset($load["payment_gateway_id"])) {
        $disabled = 'no';

        if ((int)$method['disabled'] === 1) {
            $disabled = 'yes';
        }

        $form['fields']->payment_method["value"] = $method['identifier'];
        $form['fields']->disabled["value"] = $disabled;

        if (!empty($method['credentials'])) {
            $credentials = json_decode($method['credentials']);

            if (!empty($credentials)) {
                foreach ($credentials as $field_name => $credential) {
                    if (isset($form['fields']->$field_name)) {
                        $form['fields']->$field_name["value"] = $credential;
                    }
                }
            }
        }

    }
}
?>