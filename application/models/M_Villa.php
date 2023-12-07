<?php
class M_Villa extends CI_Model {

    function fetch_all() {
        $this->db->order_by('id_vila', 'DESC');
        $query = $this->db->get('villa');
        return $query->result_array();
    }

    function fetch_single($id) {
        $this->db->where('id_vila', $id);
        $query = $this->db->get('villa');
        return $query->row();
    }

    function check_data($id) {
        $this->db->where('id_vila', $id);
        $query = $this->db->get('villa');

        if($query->row()) {
            return true;
        } else {
            return false;
        }
    }

    function insert_api($data) {
        $this->db->insert('villa', $data);
        if($this->db->affected_rows()) {
            return true;
        } else {
            return false;
        }
    }

    function update_data($id, $data) {
        $this->db->where('id_vila', $id);
        $this->db->update('villa', $data);
    }

    function delete_data($id) {
        $this->db->where('id_vila', $id);
        $this->db->delete('villa');
        if($this->db->affected_rows()>0) {
            return true;
        } else {
            return false;
        }
    }
}
?>
