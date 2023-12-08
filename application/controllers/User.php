<?php 
    defined('BASEPATH') OR exit('No direct script');

    require APPPATH . '/libraries/REST_Controller.php';
    require_once FCPATH . 'vendor/autoload.php';
    use Restserver\Libraries\REST_Controller;

    class user extends REST_Controller {

        function __construct($config = 'rest') {
            parent::__construct($config);
            $this->load->database();
            $this->load->model('M_User');
            $this->load->library('form_validation');
            $this->load->library('jwt');
        }

        function index_get() 
        {
            $id = $this->get('id');
            if($id == '') {
                $data = $this->M_User->fetch_all();
            } else {
                $data = $this->M_User->fetch_single($id);
            }
            $this->response($data, 200);
        }

        function index_post() {
            $username = $this->input->post('username');
            $password = $this->input->post('password');

            $check = $this->M_User->check_data($username, $password);

            if($check == false) {
                $error = array(
                    'status' => 'fail',
                    'message' => 'Kesalahan kresidensial',
                    'status_code' => 401
                );

                return $this->response($error);
            }

            $token = $this->jwt->encode($username, $password);

            $response = array (
                'status' => 'success',
                'message' => 'login was successful',
                'status_code' => 200,
                'token' => $token
            );
            
            return $this->response($response);
        }
      $authorizationHeader = $this->input->get_request_header('Authorization', true);

        if(empty($authorizationHeader) || $this->jwt->decode($authorizationHeader) === false) {
            return $this->response(
                array(
                    'kode' => '401',
                    'pesan' => 'signature tidak sesuai',
                    'data' => []
                ), '401'
            );
        }
        $id = $this->get('id');
    if($id == '') {
      $data = $this->M_Detail->get_all();
    } else{
      $data = $this->M_Detail->get_by_id($id);
    }
}
?>
