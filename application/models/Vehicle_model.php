<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vehicle_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // Insert vehicle
    public function insert_vehicle($data) {
        return $this->db->insert('vehicledetails', $data);
    }
    public function is_vehicle_num_exists($vehicle_num, $exclude_id = null) {
    $this->db->where('Vehicle_Num', $vehicle_num);
    if ($exclude_id !== null) {
        $this->db->where('idvehicledetails !=', $exclude_id); // exclude current id during update
    }
    $query = $this->db->get('vehicledetails');
    return $query->num_rows() > 0;
}

 public function update_vehicle($id, $data) {
    $this->db->where('idvehicledetails', $id);
    return $this->db->update('vehicledetails', $data);
}

    public function get_vehiclebyid($id){
     $this->db->where('idvehicledetails', $id);
     $query = $this->db->get('vehicledetails');
     return $query->row();
    }

    public function delete_vehiclebyid($id)
{
    $this->db->where('idvehicledetails', $id);
    return $this->db->delete('vehicledetails');
}
public function get_vehicle_by_id($id) {
    $this->db->select('driver.*');
    $this->db->from('driver');
   
    $this->db->where('vehicledetails.idvehicledetails', $id);

    $query = $this->db->get();
    return $query->result_array(); // Return all matching drivers
}

 public function insert_rate($data)
{
    return $this->db->insert('vehiclehasrates', $data);
}

public function update_rate($id, $data)
{
    return $this->db->where('id', $id)->update('vehiclehasrates', $data);
}


    public function delete_rate($vehicle_number) {
        // Add WHERE conditions
        $this->db->where('id', $vehicle_number);
       return $this->db->delete('vehiclehasrates'); // Replace 'vehicle_rates' with your actual table name
    }
public function get_all_rates()
{
    $this->db->select('v.Vehicle_Num, c.Category as category_name, r.rate,r.id,r.ratename');
    $this->db->from('vehiclehasrates r');
    $this->db->join('vehicledetails v', 'v.idvehicledetails = r.vehicleid', 'left');
    $this->db->join('category c', 'c.idCategory = r.category', 'left');
    $query = $this->db->get();

    return $query->result();
}


  

    // Get all vehicles
public function get_all_vehicles() {
    $this->db->select('
        vehicledetails.idvehicledetails,
        make.make AS Vehicle_Make,
        model.model AS Vehicle_Model,
        vehicledetails.Chassis_No,
        vehicledetails.Vehicle_Num,
        fueltypes.typename,
        vehicledetails.Seats,
        vehicledetails.status,
        vehicledetails.Category_idCategory,
        category.Category AS Category
    ');
    
    $this->db->from('vehicledetails');    
    $this->db->join('make', 'make.make_id = vehicledetails.Vehicle_Make', 'left');
    $this->db->join('model', 'model.model_id = vehicledetails.Vehicle_Model', 'left');
    $this->db->join('category', 'category.idCategory = vehicledetails.Category_idCategory', 'left');
    $this->db->join('fueltypes', 'fueltypes.id = vehicledetails.Fuel_Type', 'left');
   
    
    
    $query = $this->db->get();
    return $query->result();
}



    // Get vehicle by ID
    public function get_vehicle($id) {
        return $this->db->get_where('vehicledetails', ['idvehicledetails' => $id])->row();
    }

 

    // Delete vehicle
    public function delete_vehicle($id) {
        $this->db->where('idvehicledetails', $id);
        return $this->db->delete('vehicledetails');
    }
}
