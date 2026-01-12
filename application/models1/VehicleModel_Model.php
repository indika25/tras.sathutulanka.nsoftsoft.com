<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class VehicleModel_Model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function loadmodelstoid($id){
        $this->db->where('makeid',$id);
        $query = $this->db->get('model');
        return $query->result();
    }
   public function insertModel($data){
    return $this->db->insert('model', $data);
}

 
}
