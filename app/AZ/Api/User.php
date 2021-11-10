<?php

namespace AZ\Api;

class User
{
    /**
     * user login
     *
     * @param array WP_REST_Request
     * @return array $response
     **/
    public static function post(\WP_REST_Request $request)
    {
        $data = $request->get_params();

        foreach ($data as $key => &$item) {
            sanitize_text_field(trim($item));
        }

        if (!isset($data['method'])) {
            $data['method'] = 'login';
        }

        $user = new \AZ\User();

        switch ($data['method']) {
            case 'login':
                $result = $user->login($data);
                break;
            case 'register':
                $result = $user->insert($data);
                break;
        }

        $response = new \WP_REST_Response();

        $response->set_data($result);
        $response->header('Content-type', 'application/json');
        $response->set_status(200);

        return $response;

        die;
    }
}
