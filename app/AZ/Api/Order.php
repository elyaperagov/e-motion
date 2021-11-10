<?php

namespace AZ\Api;

use AZ\Helpers;

class Order
{
    /**
     * get order request
     *
     * @param array WP_REST_Request
     * @return array $response
     **/
    public static function post(\WP_REST_Request $request)
    {
        $post_data = $request->get_params();
        $file_array = $request->get_file_params();

        foreach ($post_data as $key => &$item) {
            sanitize_text_field(trim($item));
        }

        $post_data = json_decode($post_data['data']);
        $post_data = Helpers::objectToArray($post_data);

        $data = [];

        $data['customer']['phone'] = $post_data['form_data']['phone']['value'];
        $data['customer']['email'] = $post_data['form_data']['email']['value'];
        $data['customer']['message'] = $post_data['form_data']['message']['value'];
        $data['customer']['name'] = $post_data['form_data']['surname']['value'] . ' ' . $post_data['form_data']['name']['value'];
        $data['customer']['address'] = $post_data['form_data']['city']['value'] . ', ' . $post_data['form_data']['country']['value'];

        $data['order']['model'] = $post_data['chosen_model']['name'] ?? '';
        $data['order']['product'] = $post_data['chosen_product']['name']  ?? '';
        $data['order']['product_price'] = (int) $post_data['chosen_product']['price']  ?? '';
        $data['order']['product_price_preorder'] = (int) $post_data['chosen_product']['price_preorder']  ?? '';
        $data['order']['product_code'] = $post_data['chosen_product']['code']  ?? '';

        $errors = [];

        if (!empty($post_data['chosen_equipment']['items'])) {
            foreach ($post_data['chosen_equipment']['items'] as $key => $item) {
                $data['order']['equipment'][$key]['name'] = $item['post_title'];
                $data['order']['equipment'][$key]['article'] = $item['fields']['article'];
                $data['order']['equipment'][$key]['spare_id'] = $item['fields']['spare_id'];
                $data['order']['equipment'][$key]['price'] = (int) $item['fields']['price'];
                $data['order']['equipment'][$key]['installation'] = $item['installation'];
                if (!empty($item['fields']['work'])) {
                    $data['order']['equipment'][$key]['work']['work_id'] = $item['fields']['work']['fields']['work_id'];
                    $data['order']['equipment'][$key]['work']['name'] = $item['fields']['work']['post_title'];
                    $data['order']['equipment'][$key]['work']['price'] = $item['fields']['work']['fields']['price'];
                } else {
                    $data['order']['equipment'][$key]['work'] = [];
                }
            }
        }

        $data['order']['equipment_sum'] = (int) $post_data['chosen_equipment']['sum'] ?? '';
        $data['order']['services_sum'] = (int) $post_data['chosen_services']['sum'] ?? '';
        $data['order']['upgrade'] = (int) $post_data['chosen_services']['upgrade'] ?? '';
        $data['order']['total_sum'] = (int) $post_data['total_sum'] ?? '';

        if (empty($data['customer']['name'])) {
            $errors[] = 'Name is required';
        }
        if (empty($data['customer']['email']) || !filter_var($data['customer']['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Vaild email is required';
        }
        if (empty($data['customer']['phone'])) {
            $errors[] = 'Phone is required';
        }

        if (count($errors)) {
            return new \WP_Error('contact_form_errors', $errors, ['status' => 422]);
        }

        $json_data = json_encode($data);

        $content = '';

        array_walk_recursive($data, function ($item, $key) use (&$content) {
            $content .= $key . ': ' . $item . '<br>';
            if ($key == 'work') {
                $content .= '<br>';
            }
            if ($key == 'product_code') {
                $content .= '<br><b>equipment</b><br><br>';
            }
        });

        $post_data = array(
            'post_type'     => 'form_requests',
            'post_title'    => wp_strip_all_tags($data['order']['product']),
            'post_content'  => $content,
            'post_status'   => 'publish',
            'post_author'   => 1
        );

        $post_id = wp_insert_post($post_data);

        if (!is_wp_error($post_id)) {
            update_field('json_data', addslashes($json_data), $post_id);
        }

        $url = 'https://moto50.ru/acc/api/betaRequest.php?';

        $query = http_build_query(array(
            'data' => $json_data
        ));

        $output = '';
        if ($data['order']['product'] && !empty($data['order']['product'])) {
            if ($curl = curl_init()) {
                curl_setopt($curl, CURLOPT_URL, $url);
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $query);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

                $output = curl_exec($curl);
                curl_close($curl);
                // echo $output;
            }
        }

        $site_settings = get_fields('option');

        $response_data['status'] = 'success';
        $response_data['message'] = $site_settings['form_success_message'];
        $response_data['api_request'] = $output;

        $response = new \WP_REST_Response();

        $response->set_data($response_data);
        $response->header('Content-type', 'application/json');
        $response->set_status(200);
        $response = rest_ensure_response($response);

        return $response;

        die;
    }
}
