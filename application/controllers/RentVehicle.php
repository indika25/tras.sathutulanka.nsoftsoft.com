<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class RentVehicle extends CI_Controller {

  public function __construct() {
        parent::__construct();
    
		$this->load->model('RentVehicle_model');
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->helper('form');
    }

public function getAllrent(){
   $parts = $this->RentVehicle_model->loadallbody_parts();
 echo json_encode(['data' => $parts]);



}
 public function get_last_hire_no() {
        $lastHireNo = $this->RentVehicle_model->get_last_hire_no();
        echo json_encode(['hire_no' => $lastHireNo]);
    }
public function deleteRent()
{
    $id = $this->input->post('hireNo');

    $deleted = $this->RentVehicle_model->deleteRentById($id);

        if ($deleted) {
            echo json_encode(['status' => 'success', 'message' => 'Rent record deleted successfully.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to delete rent record.']);
        }
}

public function get_rate() {
    $vehicle_id = $this->input->post('vehicle_id');
    $category_id = $this->input->post('category_id');
    $rent_type = $this->input->post('rent_type');

    
    

    $rateData = $this->RentVehicle_model->get_rate($vehicle_id, $category_id, $rent_type);

    if ($rateData) {
        echo json_encode([
            'status' => 'success',
            'rate' => $rateData->rate
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Rate not found'
        ]);
    }
}

public function get_rent_rate()
{
   $vehicle_id = trim($this->input->post('vehicle_id'));
$duration_unit = trim($this->input->post('duration_unit'));

     $rate = $this->RentVehicle_model->get_vehicle_rent_rate($vehicle_id, $duration_unit);
      
        if ($rate !== null) {
            echo json_encode(['status' => 'success', 'rent_rate' => $rate]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Rate not found']);
        }
}

 public function get_oil_levels_ajax() {
        // Fetch oil levels from model
        $oil_levels = $this->RentVehicle_model->get_oil_levels();

        // Return JSON response
        header('Content-Type: application/json');
        echo json_encode(['status' => 'success', 'data' => $oil_levels]);
        exit;
    }
public function get_hire_locations_ajax() {
  
    $locations = $this->RentVehicle_model->get_hire_locations();

    // Return as JSON
    header('Content-Type: application/json');
    echo json_encode(['status' => 'success', 'data' => $locations]);
    exit;
}
 public function delete_location_ajax() {
        $id = $this->input->post('id');

        if (!$id) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid ID']);
            return;
        }

        $deleted = $this->RentVehicle_model->delete_location($id);

        if ($deleted) {
            echo json_encode(['status' => 'success', 'message' => 'Location deleted successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to delete location']);
        }
    }

// Save new location or update existing location
public function save_location_ajax() {
    $id = $this->input->post('id'); // empty for new
    $locationName = $this->input->post('locationName');
    $status = $this->input->post('status');

    $data = [
        'location_name' => $locationName,
        'status' => $status
    ];

    if ($id) {
        // Update
        $result = $this->RentVehicle_model->update_location($id, $data);
        if ($result === 'duplicate') {
            echo json_encode(['status' => 'error', 'message' => 'Location name already exists.']);
        } elseif ($result === 'success') {
            echo json_encode(['status' => 'success', 'message' => 'Location updated successfully.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to update location.']);
        }
    } else {
        // Insert
        $result = $this->RentVehicle_model->insert_location($data);
        if ($result === 'duplicate') {
            echo json_encode(['status' => 'error', 'message' => 'Location name already exists.']);
        } elseif ($result === 'success') {
            echo json_encode(['status' => 'success', 'message' => 'Location added successfully.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to add location.']);
        }
    }
}


public function insert_oil_level() {
    $data = $this->input->post();
    $inserted = $this->RentVehicle_model->insert($data);

    if ($inserted) {
        echo json_encode(['status' => 'success', 'message' => 'Oil level added']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Insert failed']);
    }
}
public function save_oil_level_ajax()
{
    $id = $this->input->post('id');
    $level_value = $this->input->post('level_value');
    $label = $this->input->post('label');

    // Basic validation
    if (empty($level_value) || empty($label)) {
        echo json_encode(['status' => 'error', 'message' => 'Both fields are required']);
        return;
    }

    $data = [
        'level_value' => $level_value,
        'label' => $label
    ];

    if ($id) {
        // Update
        $updated = $this->RentVehicle_model->update_oil_level($id, $data);
        if ($updated) {
            echo json_encode(['status' => 'success', 'message' => 'Oil level updated successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to update']);
        }
    } else {
        // Insert
        // Check for duplicate value
        $exists = $this->RentVehicle_model->oil_level_exists($level_value);
        if ($exists) {
            echo json_encode(['status' => 'error', 'message' => 'This level value already exists']);
            return;
        }

        $inserted = $this->RentVehicle_model->insert_oil_level($data);
        if ($inserted) {
            echo json_encode(['status' => 'success', 'message' => 'Oil level added successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to add']);
        }
    }
}



public function delete_oil_level() {
    $id = $this->input->post('id');
    $deleted = $this->RentVehicle_model->delete($id);

    if ($deleted) {
        echo json_encode(['status' => 'success', 'message' => 'Oil level deleted']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Delete failed']);
    }
}

public function save_rent()
{
    $this->output->set_content_type('application/json');

    $rent_id = $this->input->post('id');
    $hireno = $this->input->post('hireno');

    $data = [
        'vehicle_number'      => $this->input->post('vehicle_id'),
        'renter_name'         => $this->input->post('render_name'),
        'agreement_no'        => $this->input->post('agreement_no'),
        'rent_start_date'     => $this->input->post('rent_start_date'),
        'rent_type'           => $this->input->post('vehicleCategoryRate'),
        'duration'            => $this->input->post('duration'),
        'rent_amount'         => $this->input->post('rent_amount'),
        'driver_id'           => $this->input->post('driver_id'),
        'remarks'             => $this->input->post('remarks'),
        'mileage'             => $this->input->post('mileage'),
        'oillevel'            => $this->input->post('oillevel'),
        'HireNo'              => $hireno,
        'hire_location'       => $this->input->post('hire_start_location'),
        'end_location'        => $this->input->post('hire_end_location'),
        'Start_Milage'        => $this->input->post('mileage'),
        'End_Milage'          => $this->input->post('endmileage'),
        'Difference_Milage'   => $this->input->post('differencemileage'),
    ];

 
    if (!empty($rent_id)) {
        $updated = $this->RentVehicle_model->update_rent($hireno, $data);
        $this->db->where('HireNo', $hireno)->delete('hirenomaterials');
        $this->db->where('HireNo', $hireno)->delete('hirenoexpenses');
        
    } else {
        $updated = $this->RentVehicle_model->save_rent($data);
    }

    // Get and save materials
    $materials = json_decode($this->input->post('materials'), true);
    $expenses = json_decode($this->input->post('expenses'), true);
    

    $this->RentVehicle_model->save_materials($hireno, $materials);
    $this->RentVehicle_model->save_expenses($hireno, $expenses);
    

    // Response
    echo json_encode([
        'status' => $updated ? 'success' : 'error',
        'message' => $updated ? 'Rent info saved successfully.' : 'Failed to save rent info.'
    ]);

    exit;
}
 public function fetchExpenses() {
        $data = $this->RentVehicle_model->getAllExpenses();
        echo json_encode(['status' => true, 'data' => $data]);
    }

   public function fetchExpenseslike() {
    $term = $this->input->post('term'); // get the search term from AJAX

   

    $result = $this->RentVehicle_model->searchExpenses($term);

    echo json_encode(['data' => $result]);
}

public function insertExpense() {
    $input = $this->input->post();

    $data = [
        'expense_name' => $input['expenseNameModel']
    ];

    if (!empty($input['expenseId'])) {
        // Update existing expense
        $updated = $this->RentVehicle_model->updateExpense($input['expenseId'], $data);
        echo json_encode(['status' => $updated, 'action' => 'updated']);
    } else {
        // Insert new expense
        $inserted = $this->RentVehicle_model->insertExpense($data);
        echo json_encode(['status' => $inserted, 'action' => 'inserted']);
    }
}


    // Update
    

    // Delete
    public function deleteExpense($id) {
         $id = $this->input->post('id');
        $deleted = $this->RentVehicle_model->deleteExpense($id);
        echo json_encode(['status' => $deleted]);
    }

    // Get by ID (optional, for edit)
    public function get($id) {
        $expense = $this->RentVehicle_model->getExpenseById($id);
        echo json_encode($expense);
    }
public function get_hire_details()
{
    $this->output->set_content_type('application/json');

    $hireno = $this->input->get('hireno'); // or ->post('hireno') if sent via POST

    if (empty($hireno)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'HireNo is required.'
        ]);
        return;
    }

    // Fetch from model
    $materials = $this->RentVehicle_model->get_materials_by_hireno($hireno);
    $expenses = $this->RentVehicle_model->get_expenses_by_hireno($hireno);

    echo json_encode([
        'status' => 'success',
        'materials' => $materials,
        'expenses' => $expenses
    ]);
}

public function getUnits() {
        // Fetch oil levels from model
        $units = $this->RentVehicle_model->getUnits();

        // Return JSON response
        header('Content-Type: application/json');
        echo json_encode(['status' => 'success', 'data' => $units]);
        exit;
    }






}
