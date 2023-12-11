<?php

    defined('BASEPATH') OR exit('No direct script access allowed');

    require APPPATH . '/libraries/REST_Controller.php';
    use Restserver\Libraries\REST_Controller;

    class Pelanggan extends REST_Controller {

        function __construct($config = 'rest') {
            parent::__construct($config);
            header('Access-Control-Allow-Origin:*');
            header("Access-Control-Allow-Headers:X-API-KEY,Origin,X-Requested-With,Content-Type,Accept,Access-Control-Request-Method,Authorization");
            header("Access-Control-Allow-Methods:GET,POST,OPTIONS,PUT,DELETE");
            $method = $_SERVER['REQUEST_METHOD'];
            if ($method == "OPTIONS") {
                die();
            }
            $this->load->database(); 
            $this->load->model('M_Pelanggan');
            $this->load->library('form_validation');
            $this->load->library('jwt');
        }  

        public function options_get() {
            header("Access-Control-Allow-Origin: *");
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT,DELETE");
           header("Access-Control-Allow-Headers: Content-Type,Access-Control-Allow-Headers, Authorization, X-Requested-With");
            exit();
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
           
        function index_get()
        {
            $id = $this->get('id_pelanggan');
            if ($id == ''){
                $data = $this->M_Pelanggan->fetch_all();
            } else {
                $data = $this->M_Pelanggan->fetch_single_data($id);
            }
            $this->response($data,200);
        }    
        function index_post()
        {
            if ($this->post('nama_pelanggan') == '') {
                $response = array(
                    'status' => 'fail',
                    'field' => 'nama_pelanggan',
                    'massage' =>'Isian nama pelanggan tidak boleh kosong!',
                    'status_code' => 502
                );
                return $this->response($response);
            }
            if ($this->post('alamat_pelanggan') =='') {
                $response = array(
                    'status' => 'fail',
                    'field' => 'alamat_pelanggan',
                    'massage' =>'Isian alamat pelanggan tidak boleh kosong!',
                    'status_code' => 502
                );
                return $this->response($response);
            }
            if ($this->post('telepon_pelanggan') =='') {
                $response = array(
                    'status' => 'fail',
                    'field' => 'telepon_pelanggan',
                    'massage' =>'Isian pelanggan tidak boleh kosong!',
                    'status_code' => 502
                );
                return $this->response($response);
            }
            $data = array(
                'nama_pelanggan' => trim($this->post('nama_pelanggan')),
                'alamat_pelanggan' => trim($this->post('alamat_pelanggan')),
                'telepon_pelanggan' => trim($this->post('telepon_pelanggan'))
            );
            $this->M_Pelanggan->insert_api($data);
            $last_row = $this->db->select('*')->order_by('id_pelanggan',"desc")->limit(1)->get('pelanggan')->row();
            $response = array(
                'status' => 'success',
                'data' => $last_row,
                'status_code' => 201,
            );
            return $this->response($response);
        }      
        function index_put()
        {
            $id = $this->put('id_pelanggan');
            $check = $this->M_Pelanggan->check_data($id);
            if ($check == false) {
                $error = array(
                    'status' =>'fail',
                    'field' =>'id_pelanggan',
                    'message' => 'Data tidak ditemukan!',
                    'status_code' => 502
                );
                return $this->response($error);
            }
            if ($this->put('nama_pelanggan') == ''){
                $response = array(
                    'status' =>'fail',
                    'field' => 'nama_pelanggan',
                    'message' => 'Isian nama pelanggan tidak boleh kosong!',
                    'status_code' => 502
                );
                return $this->response($response);
            }
            if ($this->put('alamat_pelanggan') == ''){
                $response = array(
                    'status' =>'fail',
                    'field' => 'alamat_pelanggan',
                    'message' => 'Isian alamat pelanggan tidak boleh kosong!',
                    'status_code' => 502
                );
                return $this->response($response);
            }
            if ($this->put('telepon_pelanggan') == ''){
                $response = array(
                    'status' =>'fail',
                    'field' => 'telepon_pelanggan',
                    'message' => 'Isian telepon pelanggan tidak boleh kosong!',
                    'status_code' => 502
                );
                return $this->response($response);
            }
            $data = array(
                'nama_pelanggan' => trim($this->put('nama_pelanggan')),
                'alamat_pelanggan' => trim($this->put('alamat_pelanggan')),
                'telepon_pelanggan' => trim($this->put('telepon_pelanggan'))
            );
            $this->M_Pelanggan->update_data($id, $data);
            $newData = $this->M_Pelanggan->fetch_single_data($id);
            $response = array(
                'status' => 'success',
                'data' => $newData,
                'status_code' => 200,
            );
            return $this->response($response);
        }        
        function index_delete()
        {
            $id = $this->delete('id_pelanggan');
            $check = $this->M_Pelanggan->check_data($id);
            if ($check == false) {
                $error = array(
                    'status' =>'fail',
                    'field' =>'id_pelanggan',
                    'message' => 'Data tidak ditemukan!',
                    'status_code' => 502
                );
                return $this->response($error);
            }
            $delete = $this->M_Pelanggan->delete_data($id);
            $response = array(
                'status' => 'succes',
                'data' => null,
                'status_code' =>200,
            );
            return $this->response($response);
  }
}
?>
