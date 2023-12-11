<?php

    defined('BASEPATH') OR exit('No direct script access allowed');

    require APPPATH . '/libraries/REST_Controller.php';
    use Restserver\Libraries\REST_Controller;

    class Villa extends REST_Controller {

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
            $this->load->model('M_Villa');
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
            $id = $this->get('id_villa');
            if ($id == ''){
                $data = $this->M_Villa->fetch_all();
            } else {
                $data = $this->M_Villa->fetch_single_data($id);
            }
            $this->response($data,200);
        }    
        function index_post()
        {
            if ($this->post('nama_villa') == '') {
                $response = array(
                    'status' => 'fail',
                    'field' => 'nama_villa',
                    'massage' =>'Isian nama villa tidak boleh kosong!',
                    'status_code' => 502
                );
                return $this->response($response);
            }
            if ($this->post('fasilitas_villa') =='') {
                $response = array(
                    'status' => 'fail',
                    'field' => 'fasilitas_villa',
                    'massage' =>'Isian fasilitas villa tidak boleh kosong!',
                    'status_code' => 502
                );
                return $this->response($response);
            }
            if ($this->post('harga_per_malam') =='') {
                $response = array(
                    'status' => 'fail',
                    'field' => 'harga_per_malam',
                    'massage' =>'Isian harga per malam tidak boleh kosong!',
                    'status_code' => 502
                );
                return $this->response($response);
            }
            $data = array(
                'nama_villa' => trim($this->post('nama_villa')),
                'fasilitas_villa' => trim($this->post('fasilitas_villa')),
                'harga_per_malam' => trim($this->post('harga_per_malam'))
            );
            $this->M_Villa->insert_api($data);
            $last_row = $this->db->select('*')->order_by('id_villa',"desc")->limit(1)->get('villa')->row();
            $response = array(
                'status' => 'success',
                'data' => $last_row,
                'status_code' => 201,
            );
            return $this->response($response);
        }      
        function index_put()
        {
            $id = $this->put('id_villa');
            $check = $this->M_Villa->check_data($id);
            if ($check == false) {
                $error = array(
                    'status' =>'fail',
                    'field' =>'id_villa',
                    'message' => 'Data tidak ditemukan!',
                    'status_code' => 502
                );
                return $this->response($error);
            }
            if ($this->put('nama_villa') == ''){
                $response = array(
                    'status' =>'fail',
                    'field' => 'nama_villa',
                    'messege' => 'Isian nama villa tidak boleh kosong!',
                    'status_code' => 502
                );
                return $this->response($response);
            }
            if ($this->put('fasilitas_villa') == ''){
                $response = array(
                    'status' =>'fail',
                    'field' => 'fasilitas_villa',
                    'messege' => 'Isian fasilitas villa tidak boleh kosong!',
                    'status_code' => 502
                );
                return $this->response($response);
            }
            if ($this->put('harga_per_malam') == ''){
                $response = array(
                    'status' =>'fail',
                    'field' => 'harga_per_malam',
                    'messege' => 'Isian harga per malam tidak boleh kosong!',
                    'status_code' => 502
                );
                return $this->response($response);
            }
            $data = array(
                'nama_villa' => trim($this->put('nama_villa')),
                'fasilitas_villa' => trim($this->put('fasilitas_villa')),
                'harga_per_malam' => trim($this->put('harga_per_malam'))
            );
            $this->M_Villa->update_data($id,$data);
            $newData = $this->M_Villa->fetch_single_data($id);
            $response = array(
                'status' => 'success',
                'data' => $newData,
                'status_code' =>200,
            );
            return $this->response($response);
        }
        function index_delete()
        {
            $id = $this->delete('id_villa');
            $check = $this->M_Villa->check_data($id);
            if ($check == false) {
                $error = array(
                    'status' =>'fail',
                    'field' =>'id_villa',
                    'message' => 'Data tidak ditemukan!',
                    'status_code' => 502
                );
                return $this->response($error);
            }
            $delete = $this->M_Villa->delete_data($id);
            $response = array(
                'status' => 'succes',
                'data' => null,
                'status_code' =>200,
            );
            return $this->response($response);
  }
}
?>
