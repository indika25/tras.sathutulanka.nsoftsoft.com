 
    <?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customer_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

 public function insert_customer($data) {
        return $this->db->insert('customers', $data);
    }

    // Optional: Get customers
    public function loadallcustomers() {
        return $this->db->get('customers')->result();
    }

     public function deleteCustomerById($id) {
        // Delete customer by ID
        $this->db->where('id', $id);
        $this->db->delete('customers'); // change 'customers' to your table name
        return ($this->db->affected_rows() > 0);
    }
 
public function getCustomerById($id) {
        $query = $this->db->get_where('customers', ['id' => $id]);

        if ($query->num_rows() > 0) {
            return $query->row(); // Return single customer as object
        } else {
            return null;
        }
    }
    public function update_customer($id,$data){
        $this->db->where('id',$id);
        return $this->db->update('customers',$data);
    }

}
