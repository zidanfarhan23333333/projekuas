<?php
class M_Detail extends CI_Model {

    function fetch_all() {
        $this->db->order_by('id_detail', 'DESC');
        $query = $this->db->get('detail');
        return $query->result_array();
    }

    function fetch_single($id) {
        $this->db->where('id_detail', $id);
        $query = $this->db->get('detail');
        return $query->row();
    }

    function check_data($id) {
        $this->db->where('id_detail', $id);
        $query = $this->db->get('detail');

        if($query->row()) {
            return true;
        } else {
            return false;
        }
    }

    function insert_api($data) {
        $this->db->insert('detail', $data);
        if($this->db->affected_rows()) {
            return true;
        } else {
            return false;
        }
    }

    function update_data($id, $data) {
        $this->db->where('id_detail', $id);
        $this->db->update('detail', $data);
    }

    function delete_data($id) {
        $this->db->where('id_detail', $id);
        $this->db->delete('detail');
        if($this->db->affected_rows()>0) {
            return true;
        } else {
            return false;
        }
    }
}
?>
