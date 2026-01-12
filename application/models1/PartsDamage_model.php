<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PartsDamage_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
public function insert_damagepart($data) {
    $this->db->insert('damage_parts', $data);
    if ($this->db->affected_rows() > 0) {
        return true;
    } else {
        log_message('error', 'Insert failed: ' . print_r($this->db->error(), true));
        return false;
    }
}


    public function update_damagepart($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('damage_parts', $data);
    }

    public function get_all_damageparts() {
       $this->db->select('dp.id, dp.name, dp.part_no, dp.price, dp.damagedate, dp.created_at, dp.p_condition, dp.remarks, d.first_name, v.Vehicle_Num');
       $this->db->from('damage_parts dp');
        $this->db->join('driver d', 'd.id = dp.Driver', 'left');
        $this->db->join('vehicledetails v', 'v.idvehicledetails = dp.vehicle_num', 'left');
        $query = $this->db->get();
        return $query->result();
    }
public function get_all_damageparts2($vehicleNum = null, $startDate = null, $endDate = null) {
    $this->db->select('dp.id, dp.name, dp.part_no, dp.price, dp.damagedate, dp.created_at, dp.p_condition, dp.remarks, d.first_name, v.Vehicle_Num');
    $this->db->from('damage_parts dp');
    $this->db->join('driver d', 'd.id = dp.Driver', 'left');
    $this->db->join('vehicledetails v', 'v.idvehicledetails = dp.vehicle_num', 'left');

   
    if(!empty($vehicleNum)) {
        $this->db->where('v.idvehicledetails', $vehicleNum);
    }

    if(!empty($startDate)) {
        $this->db->where('dp.damagedate >=', $startDate);
    }

    if(!empty($endDate)) {
        $this->db->where('dp.damagedate <=', $endDate);
    }

    $query = $this->db->get();
    return $query->result();
}

   public function delete_damagepart($id) {
    $this->db->where('id', $id);
    return $this->db->delete('damage_parts'); // Replace with your actual table name
}

   
 
}
