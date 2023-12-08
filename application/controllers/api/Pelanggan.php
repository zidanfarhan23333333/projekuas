<?php

defined('BASEPATH') OR exit('No direct script');

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
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
        exit();
    }

    function index_get() 
    {
        $id = $this->get('id');
        if($id == '') {
            $data = $this->M_Pelanggan->fetch_all();
        } else {
            $data = $this->M_Pelanggan->fetch_single($id);
        }
        $this->response($data, 200);
    }

    function index_post() {
        $this->form_validation->set_rules('nama_pelanggan', 'Nama Pelanggan', 'trim|required');
        $this->form_validation->set_rules('alamat_pelanggan', 'Alamat Pelanggan', 'trim|required');
        $this->form_validation->set_rules('telepon_pelanggan', 'Telepon Pelanggan', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            $response = array(
                'status' => 'fail',
                'message' => validation_errors(),
                'status_code' => 502
            );

            return $this->response($response);
        }

        $data = array(
            'nama_pelanggan' => trim($this->post('nama_pelanggan')),
            'alamat_pelanggan' => trim($this->post('alamat_pelanggan')),
            'telepon_pelanggan' => trim($this->post('telepon_pelanggan')),
        );

        $this->M_Pelanggan->insert_api($data);
        $last_row = $this->db->select('*')->order_by('id', 'DESC')->limit(1)->get('pelanggan')->row();
        $response = array(
            'status' => 'success',
            'data' => $last_row,
            'status_code' => 201
        );

        return $this->response($response);
    }

    function index_put() {
        $id = $this->put('id');
        $check = $this->M_Pelanggan->check_data($id);
        if($check == false) {
            $error = array(
                'status' => 'fail',
                'field' => 'id',
                'message' => 'id is not found',
                'status_code' => 502
            );

            return $this->response($error);
        }

        $this->form_validation->set_rules('nama_pelanggan', 'Nama Pelanggan', 'trim|required');
        $this->form_validation->set_rules('alamat_pelanggan', 'Alamat Pelanggan', 'trim|required');
        $this->form_validation->set_rules('telepon_pelanggan', 'Telepon Pelanggan', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            $response = array(
                'status' => 'fail',
                'message' => validation_errors(),
                'status_code' => 502
            );

            return $this->response($response);
        }

        $data = array(
            'nama_pelanggan' => trim($this->put('nama_pelanggan')),
            'alamat_pelanggan' => trim($this->put('alamat_pelanggan')),
            'telepon_pelanggan' => trim($this->put('telepon_pelanggan')),
        );

        $this->M_Kontak->update_data($id, $data);
        $newData = $this->M_Pelanggan->fetch_single($id);
        $response = array(
            'status' => 'success',
            'data' => $newData,
            'status_code' => 200
        );

        return $this->response($response);
    }

    function index_delete() {
        $id = $this->delete('id');
        $check = $this->M_Pelanggan->check_data($id);
        if($check == false) {
            $error = array(
                'status' => 'fail',
                'field' => 'id',
                'message' => 'id is not found',
                'status_code' => 502
            );

            return $this->response($error);
        }
        $delete = $this->M_Pelanggan->delete_data($id);
        $response = array(
            'status' => 'success',
            'data' => $delete,
            'status_code' => 200
        );
        return $this->response($response);
    }

}
?>
