<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Make_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function loadallmakes(){
        $query = $this->db->get('make');
        return $query->result();
    }
    public function insertMake($make) {
        $data = [
            'make' => $make
        ];

        $this->db->insert('make', $data);

        if ($this->db->affected_rows() > 0) {
            return $this->db->insert_id(); // return make_id
        } else {
            return false;
        }
    }
 
}
