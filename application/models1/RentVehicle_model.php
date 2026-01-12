<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class RentVehicle_model extends CI_Model {
protected $table = 'vehicle_rentals';
protected $table2 = 'expenses';

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
public function get_vehicle_rent_rate($vehicle_id, $rate_type)
{
    $this->db->select('rentrate'); // assuming column in DB is `rentrate`
    $this->db->from('vehicledetails');
    $this->db->where('idvehicledetails', $vehicle_id);
    $this->db->where('ratetype', $rate_type);

    $query = $this->db->get();

    if ($query->num_rows() > 0) {
        return $query->row()->rentrate; // <- match the selected column name exactly
    } else {
        return null;
    }
}
// Model function to get oil levels
public function get_oil_levels() {
    return $this->db->order_by('level_value')->get('oil_levels')->result_array();
}
public function get_hire_locations() {
    return $this->db
        ->where('status', 'active')
        ->order_by('location_name', 'ASC')
        ->get('hire_locations')
        ->result_array();
}
  public function delete_location($id) {
        $this->db->where('id', $id);
        return $this->db->delete('hire_locations');
    }
   public function insert_location($data) {
    $this->db->where('LOWER(location_name)', strtolower($data['location_name']));
    $query = $this->db->get('hire_locations');

    if ($query->num_rows() > 0) {
        return 'duplicate';
    }

    return $this->db->insert('hire_locations', $data) ? 'success' : false;
}
public function get_all() {
    return $this->db->get('oil_levels')->result();
}
public function insert_oil_level($data)
{
    return $this->db->insert('oil_levels', $data);
}

public function update_oil_level($id, $data)
{
    return $this->db->where('id', $id)->update('oil_levels', $data);
}

public function oil_level_exists($level_value)
{
    return $this->db->where('level_value', $level_value)->get('oil_levels')->num_rows() > 0;
}

public function insert($data) {
    return $this->db->insert('oil_levels', $data);
}

public function update($id, $data) {
    return $this->db->where('id', $id)->update('oil_levels', $data);
}

public function delete($id) {
    return $this->db->where('id', $id)->delete('oil_levels');
}


public function update_location($id, $data) {
    // Check if another record has the same location_name
    $this->db->where('LOWER(location_name)', strtolower($data['location_name']));
    $this->db->where('id !=', $id); // Exclude the current record
    $query = $this->db->get('hire_locations');

    if ($query->num_rows() > 0) {
        return 'duplicate';
    }

    $this->db->where('id', $id);
    return $this->db->update('hire_locations', $data) ? 'success' : false;
}


 public function get_last_hire_no() {
        $this->db->select_max('HireNo');
        $query = $this->db->get('vehicle_rentals');
        $result = $query->row();

        if ($result && $result->HireNo !== null) {
            return (int)$result->HireNo + 1; // auto-increment for new entry
        } else {
            return 1; // first hire number if table is empty
        }
    }
public function get_rate($vehicle_id, $category_id, $rent_type) {
        $this->db->where('vehicleid', $vehicle_id);
        $this->db->where('category', $category_id);
        $this->db->where('ratename', $rent_type);
        $query = $this->db->get('vehiclehasrates');

        if ($query->num_rows() > 0) {
            return $query->row(); // returns object with rate
        } else {
            return false;
        }
    }
  public function loadallbody_parts() {
    $this->db->select('vehicle_rentals.*, driver.first_name as driver_name, customers.customer_name, vehicledetails.Vehicle_Num'); // add more fields as needed
    $this->db->from('vehicle_rentals');
    $this->db->join('driver', 'vehicle_rentals.driver_id = driver.id', 'left');
    $this->db->join('vehicledetails', 'vehicle_rentals.vehicle_number = vehicledetails.idvehicledetails', 'left');
    $this->db->join('customers', 'vehicle_rentals.renter_name = customers.id', 'left');

    $query = $this->db->get();
    return $query->result();
}

  public function save_rent($data) {
        return $this->db->insert($this->table, $data);
    }
      public function update_rent($id, $data) {
        $this->db->where('hireno', $id);
        return $this->db->update('vehicle_rentals', $data); // replace with your table name
    }

    // Optional: for editing
    public function get_rent_by_id($id) {
        return $this->db->get_where('vehicle_rentals', ['id' => $id])->row_array();
    }
   public function deleteRentById($id)
{
    // Start transaction
    $this->db->trans_start();

    // Delete from main table
    $this->db->delete('vehicle_rentals', ['HireNo' => $id]);

    // Delete from expenses table
    $this->db->delete('hirenoexpenses', ['HireNo' => $id]);

    // Delete from materials table
    $this->db->delete('hirenomaterials', ['HireNo' => $id]);

    // Complete transaction
    $this->db->trans_complete();

    // Return success or failure
    return $this->db->trans_status();
}

    public function getUnits() {
    return $this->db->order_by('name')->get('material')->result_array();
}
public function save_materials($hireno, $materials)
{
    if (!empty($materials) && is_array($materials)) {
        foreach ($materials as $item) {
            if (!empty($item['unit']) && isset($item['qty'])) {
                $this->db->insert('hirenomaterials', [
                    'HireNo' => $hireno,
                    'Unit'   => $item['unit'],
                    'UnitPrice'   => $item['unitprice'],                    
                    'Qty'    => $item['qty'],
                    'Name'    => $item['name'],
                    'Amount'    => $item['amount'],                  
                    
                ]);
            }
        }
    }
}

 public function insertExpense($data) {
        return $this->db->insert($this->table2, $data);
    }

    public function getAllExpenses() {
        return $this->db->get($this->table2)->result();
    }

    public function updateExpense($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update($this->table2, $data);
    }
public function searchExpenses($term = null) {
        $this->db->select('id, expense_name');

        if (!empty($term)) {
            $this->db->like('expense_name', $term);
        }

        $query = $this->db->get($this->table2);
        return $query->result_array();
    }
    public function deleteExpense($id) {
        $this->db->where('id', $id);
        return $this->db->delete($this->table2);
    }

    public function getExpenseById($id) {
        return $this->db->get_where($this->table2, ['id' => $id])->row();
    }


public function get_materials_by_hireno($hireno)
{
    return $this->db
        ->where('HireNo', $hireno)
        ->get('hirenomaterials')
        ->result_array();
}
public function get_expenses_by_hireno($hireno)
{
    return $this->db
        ->select('hirenoexpenses.*, expenses.expense_name') // Select all from hirenoexpenses + expense_name from expenses
        ->from('hirenoexpenses')
        ->join('expenses', 'hirenoexpenses.ExpenseName = expenses.id', 'left') // Use LEFT JOIN or INNER JOIN as needed
        ->where('hirenoexpenses.HireNo', $hireno)
        ->get()
        ->result_array();
}


public function save_expenses($hireno, $materials)
{
    if (!empty($materials) && is_array($materials)) {
        foreach ($materials as $item) {
            if (!empty($item['examount']) && isset($item['examount'])) {
                $this->db->insert('hirenoexpenses', [
                    'HireNo' => $hireno,
                    'ExpenseName'   => $item['id'],
                    'Amount'    => $item['examount'],
                ]);
            }
        }
    }
}


}
