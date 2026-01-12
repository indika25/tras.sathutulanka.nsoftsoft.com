<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Driver_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

   public function get_all_drivers(){
    $this->db->select('
        driver.*, 
        vehicle_license_types.license_code AS license_code
      
    ');
    $this->db->from('driver');
    $this->db->join('vehicle_license_types', 'driver.license_type = vehicle_license_types.id', 'left');
  
    // if you also want to join driver.vehicle_id to vehicledetails, add:
    // $this->db->join('vehicledetails as v2', 'driver.vehicle_id = v2.idvehicledetails', 'left');
    
    $query = $this->db->get();
    return $query->result();
}

public function get_driver_by_id($id) {
    return $this->db->get_where('driver', ['id' => $id])->row();
}

public function delete_driver($id) {
    return $this->db->delete('driver', ['id' => $id]);
}


  public function get_all_driver_types() {
        $query = $this->db->get('driver_types');
        return $query->result();
    }
    
    public function get_all_lisence() {
        $query = $this->db->get('vehicle_license_types');
        return $query->result();
    }

     public function insert_driver($data)
    {
        return $this->db->insert('driver', $data);
    }

    public function update_driver($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('driver', $data);
    }

       public function insert_license_type($data)
    {
        return $this->db->insert('vehicle_license_types', $data);
    }

    public function license_type_exists($license_code)
    {
        $this->db->where('license_code', $license_code);
        $query = $this->db->get('vehicle_license_types');
        return $query->num_rows() > 0;
    }
public function insert_driver_type($data)
{
    return $this->db->insert('driver_types', $data); // replace with your actual table name
}
 
    public function get_all_license_types()
    {
        $query = $this->db->get('vehicle_license_types');
        return $query->result();
    }
  public function update_license_type($id, $data)
{
    $this->db->where('id', $id);
    return $this->db->update('vehicle_license_types', $data);
}
public function delete_license_type($id)
{
    $this->db->where('id', $id);
    return $this->db->delete('vehicle_license_types');
}
 public function delete_driver_type($id) {
        $this->db->where('id', $id);
        return $this->db->delete('driver_types'); // Replace with your actual table name
    }
    public function update_driver_type($id, $driver_type_code, $description) {
    $data = [
        'driver_type_code' => $driver_type_code,
        'description' => $description
    ];

    $this->db->where('id', $id);
    return $this->db->update('driver_types', $data); // Replace with your actual table name
}


}
