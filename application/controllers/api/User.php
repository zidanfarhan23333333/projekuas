<?php 
    defined('BASEPATH') OR exit('No direct script');

    require APPPATH . '/libraries/REST_Controller.php';
    require_once FCPATH . 'vendor/autoload.php';
    use Restserver\Libraries\REST_Controller;

    class User extends REST_Controller {

        function __construct($config = 'rest') {
            parent::__construct($config);
            $this->load->database();
            $this->load->model('M_User');
            $this->load->library('form_validation');
            $this->load->library('jwt');
        }

        function validate()
     {
        $this->form_validation->set_rules('username', 'Username', 'required|trim');
        $this->form_validation->set_rules('password', 'Password', 'required');
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

        function is_login() {
            $authorizationHeader = $this->input->get_request_header('Authorization', true);
    
            if (empty($authorizationHeader) || $this->jwt->decode($authorizationHeader) === false) {
                $this->response(
                    array(
                        'kode' => '401',
                        'pesan' => 'signature tidak sesuai',
                        'data' => []
                    ), '401'
                );
                return false;
            }
    
            return true;
        }

        function index_post()
        {

            $this -> validate();

            if ($this->form_validation->run() === FALSE) {
                $error = $this->form_validation->error_array();
                $response = array(
                    'status_code' => 502,
                    'message' => $error
                );
                return $this->response($response, 502);
            }
    
            $username = $this->input->post('username');
            $password = md5($this->input->post('password'));
    
            $user = $this->M_User->cek_login($username, $password);
    
            if (!$user) {
                $response = array(
                    'status_code' => 401,
                    'message' => 'Invalid email or password'
                );
                return $this->response($response, 401);
            }
    
            $token = $this->jwt->encode($username, $password);
    
            $response = array(
                'status_code' => 200,
                'message' => 'Login successful',
                'user_data' => $user,
                'token' => $token
            );
    
            return $this->response($response);
        }
    }
?>
