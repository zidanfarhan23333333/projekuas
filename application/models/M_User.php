<?php
class M_User extends CI_Model {

    function fetch_all() {
        $this->db->order_by('id', 'DESC');
        $query = $this->db->get('user');
        return $query->result_array();
    }

    function fetch_single($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('user');
        return $query->row();
    }

    function check_data($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('user');

        if($query->row()) {
            return true;
        } else {
            return false;
        }
    }

    function insert_api($data) {
        $this->db->insert('user', $data);
        if($this->db->affected_rows()) {
            return true;
        } else {
            return false;
        }
    }

    function update_data($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('user', $data);
    }

    function delete_data($id) {
        $this->db->where('id', $id);
        $this->db->delete('user');
        if($this->db->affected_rows()>0) {
            return true;
        } else {
            return false;
        }
    }
}
?>
