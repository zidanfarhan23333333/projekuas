<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

require_once FCPATH . 'vendor/autoload.php';

use Restserver\Libraries\REST_Controller;

class Authenticate extends REST_Controller {

    function __construct($config = 'rest') {
        parent::__construct($config);
        $this->load->library('jwt');
    }

    public function generateSecretKey_get() {
        $length = 32;
        $secretKey = bin2hex(random_bytes($length));

        return $this->response(["jwt_secret_key" => $secretKey]);
    }
    public function getToken_post() {
        $data = array(
            "username" => $this->input->post('username'),
            "password" => $this->input->post('password')
        );
        $token = $this->jwt->encode($data);
        $output = [
            'status' => 200,
            'message' => 'Berhasil Login',
            'token' => $token
        ];
        $data = array($output);

        $this->response($data, 200);
    }
    public function decode($param) {
        $key = $this->CI->config->item('jwt_key');
        $algorithm = $this->CI->config->item('jwt_algorithm');
    
        if (isset($param)) {
            $authHeader = $param;
            $arr = explode("Bearer ", $authHeader);
    
            if (count($arr) > 1) {
                $token = $arr[1];
                if ($token) {
                try {
                    $decoded = JWTLib::decode($token,new Key($key, $algorithm));
                    if ($decoded) {
                        return true;
                    }
                } catch (Exception $e) {
                    return false;
                }
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }  
}
