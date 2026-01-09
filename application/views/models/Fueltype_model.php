<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Fueltype_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function loadalltypes(){
        $query = $this->db->get('fueltypes');
        return $query->result();
    }
 
}
