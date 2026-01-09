<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Category_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function loadallcategory(){
        $query = $this->db->get('category');
        return $query->result();
    }
 
}
