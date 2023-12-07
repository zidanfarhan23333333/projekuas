<?php
class M_Pelanggan extends CI_Model {

    function fetch_all() {
        $this->db->order_by('id_pelanggan', 'DESC');
        $query = $this->db->get('pelanggan');
        return $query->result_array();
    }

    function fetch_single($id) {
        $this->db->where('id_pelanggan', $id);
        $query = $this->db->get('pelanggan');
        return $query->row();
    }

    function check_data($id) {
        $this->db->where('id_pelanggan', $id);
        $query = $this->db->get('pelanggan');

        if($query->row()) {
            return true;
        } else {
            return false;
        }
    }

    function insert_api($data) {
        $this->db->insert('pelanggan', $data);
        if($this->db->affected_rows()) {
            return true;
        } else {
            return false;
        }
    }

    function update_data($id, $data) {
        $this->db->where('id_pelanggan', $id);
        $this->db->update('pelanggan', $data);
    }

    function delete_data($id) {
        $this->db->where('id_pelanggan', $id);
        $this->db->delete('pelanggan');
        if($this->db->affected_rows()>0) {
            return true;
        } else {
            return false;
        }
    }
}
?>
