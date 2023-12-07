<?php

    defined('BASEPATH') OR exit('No direct script access allowed');

    require APPPATH . '/libraries/REST_Controller.php';
    use Restserver\Libraries\REST_Controller;

    class Detail extends REST_Controller {

        function __construct($config = 'rest') {
            parent::__construct($config);
            header('Access-Control-Allow-Origin:*');
            header("Access-Control-Allow-Headers:X-API-KEY,Origin,X-Requested-With,Content-Type,Accept,Access-Control-Request-Method,Authorization");
            header("Access-Control-Allow-Methods:GET,POST,OPTIONS,PUT,DELETE");
            $method = $_SERVER['REQUEST_METHOD'];
            if ($method == "OPTIONS") {
                die();
            }
            $this->load->database(); //optional
            $this->load->model('M_Detail');
            $this->load->library('form_validation');

            date_default_timezone_set('Asia/Jakarta');
        }  

        public function options_get() {
            header("Access-Control-Allow-Origin: *");
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT,DELETE");
           header("Access-Control-Allow-Headers: Content-Type,Access-Control-Allow-Headers, Authorization, X-Requested-With");
            exit();
           }
           
        function index_get()
        {
            $id = $this->get('id_detail');
            if ($id == ''){
                $data = $this->M_Detail->fetch_all();
            } else {
                $data = $this->M_Detail->fetch_single_data($id);
            }
            $this->response($data,200);
        }    
        function index_post()
        {
            if ($this->post('tanggal_checkin') == '') {
                $response = array(
                    'status' => 'fail',
                    'field' => 'tanggal_checkin',
                    'massage' =>'Isian tanggal checkin tidak boleh kosong!',
                    'status_code' => 502
                );
                return $this->response($response);
            }
            if ($this->post('tanggal_checkout') == '') {
                $response = array(
                    'status' => 'fail',
                    'field' => 'tanggal_checkout',
                    'massage' =>'Isian tanggal checkout tidak boleh kosong!',
                    'status_code' => 502
                );
                return $this->response($response);
            }
            if ($this->post('jumlah_orang') =='') {
                $response = array(
                    'status' => 'fail',
                    'field' => 'jumlah_orang',
                    'massage' =>'Isian jumlah orang tidak boleh kosong!',
                    'status_code' => 502
                );
                return $this->response($response);
            }
            $data = array(
                'tanggal_checkin' => trim($this->post('Y-m-d')),
                'tanggal_checkout' => trim($this->post('Y-m-d')),
                'jumlah_orang' => trim($this->post('jumlah orang')),
                'total_biaya' => trim($this->post('total biaya'))
            );
            $this->M_Detail->insert_api($data);
            $last_row = $this->db->select('*')->order_by('id_detail',"desc")->limit(1)->get('detail')->row();
            $response = array(
                'status' => 'success',
                'data' => $last_row,
                'status_code' => 201,
            );
            return $this->response($response);
        }      
        function index_put()
        {
            $id = $this->put('id_detail');
            $check = $this->M_Detail->check_data($id);
            if ($check == false) {
                $error = array(
                    'status' =>'fail',
                    'field' =>'id_detail',
                    'message' => 'Data tidak ditemukan!',
                    'status_code' => 502
                );
                return $this->response($error);
            }
            if ($this->put('tanggal_checkin') == ''){
                $response = array(
                    'status' =>'fail',
                    'field' => 'tanggal_checkin',
                    'messege' => 'Isian tanggal checkin tidak boleh kosong!',
                    'status_code' => 502
                );
                return $this->response($response);
            }
            if ($this->put('tanggal_checkout') == ''){
                $response = array(
                    'status' =>'fail',
                    'field' => 'tanggal_checkout',
                    'messege' => 'Isian tanggal checkout tidak boleh kosong!',
                    'status_code' => 502
                );
                return $this->response($response);
            }
            if ($this->put('jumlah_orang') == ''){
                $response = array(
                    'status' =>'fail',
                    'field' => 'jumlah_orang',
                    'messege' => 'Isian jumlah orang tidak boleh kosong!',
                    'status_code' => 502
                );
                return $this->response($response);
            }
            if ($this->put('total_biaya') == ''){
                $response = array(
                    'status' =>'fail',
                    'field' => 'total_biaya',
                    'messege' => 'Isian total biaya tidak boleh kosong!',
                    'status_code' => 502
                );
                return $this->response($response);
            }
            $data = array(
                'tanggal_checkin' => trim($this->put('Y-m-d')),
                'tanggal_checkout' => trim($this->put('Y-m-d')),
                'jumlah_orang' => trim($this->put('jumlah_orang')),
                'total_biaya' => trim($this->put('total_biaya'))
            );
            $this->M_Detail->update_data($id,$data);
            $newData = $this->M_Detail->fetch_single_data($id);
            $response = array(
                'status' => 'succes',
                'data' => $newData,
                'status_code' =>200,
            );
            return $this->response($response);
        }
        function index_delete()
        {
            $id = $this->delete('id_detail');
            $check = $this->M_Detail->check_data($id);
            if ($check == false) {
                $error = array(
                    'status' =>'fail',
                    'field' =>'id_detail',
                    'message' => 'Data tidak ditemukan!',
                    'status_code' => 502
                );
                return $this->response($error);
            }
            $delete = $this->M_Detail->delete_data($id);
            $response = array(
                'status' => 'succes',
                'data' => null,
                'status_code' =>200,
            );
            return $this->response($response);
  }
}
?>