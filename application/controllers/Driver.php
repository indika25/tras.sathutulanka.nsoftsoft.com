<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Driver extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Driver_model');
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->helper('form');
    }
 
   public function getDriver_types(){
    $data['driver_types'] = $this->Driver_model->get_all_driver_types();
    echo json_encode($data);
   }

  public function SaveDriver()
{
    $data = [
        'first_name'          => $this->input->post('first_name'),
        'last_name'           => $this->input->post('last_name'),
        'date_of_birth'       => $this->input->post('date_of_birth'),
        'gender'              => $this->input->post('gender'),
        'license_number'      => $this->input->post('license_number'),
        'license_issue_date'  => $this->input->post('license_issue_date'),
        'license_expiry_date' => $this->input->post('license_expiry_date'),
        'license_type'        => $this->input->post('assigned_lisense_id'),
        'driver_type'         => $this->input->post('assigned_driver_id'),
        'contact_number'      => $this->input->post('contact_number'),
        'email'               => $this->input->post('email'),
        'national_id'         => $this->input->post('national_id'),
        'address'             => $this->input->post('address'),
        'status'              => $this->input->post('status'),        
        'joining_date'        => $this->input->post('joining_date'),
        'salary_percent'      => $this->input->post('salary_rate')
      
    ];

    $driver_id = $this->input->post('driver_id');

    if ($driver_id) {
        $result = $this->Driver_model->update_driver($driver_id, $data);
    } else {
        $result = $this->Driver_model->insert_driver($data);
    }

    if ($result) {
        echo json_encode(['status' => 'success', 'message' => 'Driver saved successfully']);
    } else {
        $error = $this->db->error(); // <-- Get DB error

        // Check if it's a duplicate key error
        if ($error['code'] == 1062) {
            // Extract the conflicting field from the message (optional)
            if (strpos($error['message'], 'license_number') !== false) {
                $message = 'The license number already exists.';
            } elseif (strpos($error['message'], 'email') !== false) {
                $message = 'The email address is already in use.';
            } elseif (strpos($error['message'], 'national_id') !== false) {
                $message = 'The national ID already exists.';
            } else {
                $message = 'Duplicate entry error.';
            }

            echo json_encode([
                'status' => 'error',
                'type' => 'duplicate',
                'message' => $message
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'type' => 'db',
                'message' => 'Database error occurred.'
            ]);
        }
    }
}



public function get_license_types_ajax()
{
    
    $license_types = $this->Driver_model->get_all_license_types();
    echo json_encode(['data' => $license_types]);
}

public function save_license_type()
{
    $license_code = $this->input->post('license_code');
    $description = $this->input->post('description');

    if (empty($license_code) || empty($description)) {
        echo json_encode(['status' => 'error', 'message' => 'All fields are required']);
        return;
    }


    // Check if license code already exists
    if ($this->Driver_model->license_type_exists($license_code)) {
        echo json_encode(['status' => 'error', 'message' => 'License code already exists']);
        return;
    }

    $data = [
        'license_code' => $license_code,
        'description' => $description
    ];

    if ($this->Driver_model->insert_license_type($data)) {
        echo json_encode(['status' => 'success', 'message' => 'License type added successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to insert license type']);
    }
}
public function update_license_type()
{
    $id = $this->input->post('id');
    $data = [
        'license_code' => $this->input->post('license_code'),
        'description'  => $this->input->post('description'),
    ];

    $result = $this->Driver_model->update_license_type($id, $data);

    if ($result) {
        echo json_encode(['status' => 'success', 'message' => 'License type updated successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to update license type']);
    }
}
public function delete_license_type()
{
    $id = $this->input->post('id');

    $result = $this->Driver_model->delete_license_type($id);

    if ($result) {
        echo json_encode(['status' => 'success', 'message' => 'License type deleted successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to delete license type']);
    }
}

public function save_driver_type()
{
    $data = [
        'driver_type_code'   => $this->input->post('ldriver_code'), // still using 'license_code' from form
        'description' => $this->input->post('description'),
    ];

    $result = $this->Driver_model->insert_driver_type($data);

    if ($result) {
        echo json_encode(['status' => 'success', 'message' => 'Driver type saved successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to save driver type.']);
    }
}
public function get_driver_types_ajax()
{
    $driver_types = $this->Driver_model->get_all_driver_types();

    // Build response data array if needed
    // ensure it matches the structure your JS expects (e.g. id, type_code, description)
    $data = [];
    foreach ($driver_types as $dt) {
        $data[] = [
            'id' => $dt->id,
            'driver_type_code' => $dt->driver_type_code,         // or whatever your column is named
            'description' => $dt->description
        ];
    }

    echo json_encode(['data' => $data]);
}

public function getLisence_types(){
$data['license'] = $this->Driver_model->get_all_lisence();
echo json_encode($data);
}

 public function get_Driver_ajax() {
    // Load all driver records from the model
    $drivers = $this->Driver_model->get_all_drivers();

    $data = array();
    foreach ($drivers as $driver) {
        $row = array(
            $driver->id,
            $driver->first_name,
            $driver->last_name,
            $driver->date_of_birth,
            $driver->gender,
            $driver->license_number,
            $driver->license_issue_date,
            $driver->license_expiry_date,
            $driver->license_code,
            $driver->driver_type,
            $driver->contact_number,
            $driver->email,
            $driver->national_id,
            $driver->address,
            $driver->status,
          $driver->joining_date,
            $driver->salary_percent,
            $driver->created_at,
            $driver->updated_at,
           
        );
        $data[] = $row;
    }

    $response = array(
        "draw" => intval($this->input->post('draw')),
        "recordsTotal" => count($drivers),
        "recordsFiltered" => count($drivers),
        "data" => $data
    );

    $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($response));
}
public function get_driver_by_id() {
    $driver_id = $this->input->post('driver_id');
  
    $driver = $this->Driver_model->get_driver_by_id($driver_id);

    if ($driver) {
        echo json_encode(['status' => 'success', 'data' => $driver]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Driver not found']);
    }
}

public function delete_driver() {
    $driver_id = $this->input->post('driver_id');
  

    $deleted = $this->Driver_model->delete_driver($driver_id);

    if ($deleted) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to delete driver']);
    }
}
public function update_driver_type() {
    $id = $this->input->post('id', TRUE); // Get and sanitize input
    $driver_type_code = $this->input->post('ldriver_code', TRUE);
    $description = $this->input->post('description', TRUE);

    if (!$id || !$driver_type_code || !$description) {
        echo json_encode([
            'status' => 'error',
            'message' => 'All fields are required.'
        ]);
        return;
    }

    // Call model to update
    $updated = $this->Driver_model->update_driver_type($id, $driver_type_code, $description);

    if ($updated) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Driver type updated successfully.'
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Failed to update driver type.'
        ]);
    }
}

public function delete_driver_type() {
        $id = $this->input->post('id', TRUE); // Sanitize input

        if (!$id || !is_numeric($id)) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Invalid ID.'
            ]);
            return;
        }

        $deleted = $this->Driver_model->delete_driver_type($id);

        if ($deleted) {
            echo json_encode([
                'status' => 'success',
                'message' => 'Driver type deleted successfully.'
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Failed to delete driver type.'
            ]);
        }
    }

}