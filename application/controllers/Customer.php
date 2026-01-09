<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customer extends CI_Controller {

  public function __construct() {
        parent::__construct();
    
		$this->load->model('Customer_model');
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->helper('form');
    }
	 public function deleteCustomer($id = null) {
        // Ensure ID is provided
        if ($id === null) {
            echo json_encode(['success' => false, 'message' => 'Invalid customer ID']);
            return;
        }

        // Attempt to delete customer
        $deleted = $this->Customer_model->deleteCustomerById($id);

        if ($deleted) {
            echo json_encode(['success' => true, 'message' => 'Customer deleted successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete customer']);
        }
    }
public function loadcustomers() {
    $data['customers'] = $this->Customer_model->loadallcustomers();
    echo json_encode($data);
}
public function saveCustomer() {
    $id = $this->input->post('id'); // This will be set for update

    $data = [
        'title'            => $this->input->post('title'),
        'customer_name'    => $this->input->post('customer_name'),
        'display_name'     => $this->input->post('display_name'),
        // 'customer_no'      => $this->input->post('customer_no'),
        'primary_address'  => $this->input->post('primary_address'),
        'mobile'           => $this->input->post('mobile'),
        'phone'            => $this->input->post('phone'),
        // 'work_phone'       => $this->input->post('work_phone'),
        'email'            => $this->input->post('email'),
        // 'contact_person'   => $this->input->post('contact_person'),
        // 'contact_phone'    => $this->input->post('contact_phone'),
        'is_active'        => $this->input->post('is_active') ? 1 : 0,
        // 'payment_method'   => $this->input->post('payment_method'),
        'remarks'          => $this->input->post('remarks')
    ];

    if ($id) {
        // Update existing customer
        $updated = $this->Customer_model->update_customer($id, $data);

        if ($updated) {
            echo json_encode(['success' => true, 'message' => 'Customer updated successfully!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update customer.']);
        }

    } else {
        // Insert new customer
        $inserted = $this->Customer_model->insert_customer($data);

        if ($inserted) {
            echo json_encode(['success' => true, 'message' => 'Customer added successfully!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to add customer.']);
        }
    }
}



public function getCustomerById($id = null) {
        // Validate ID
        if (!$id || !is_numeric($id)) {
            show_404(); // Or return JSON error
        }

        // Fetch customer data
        $customer = $this->Customer_model->getCustomerById($id);

        if ($customer) {
            echo json_encode($customer);
        } else {
            echo json_encode(null);
        }
    }


}
