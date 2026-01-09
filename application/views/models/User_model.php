<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // Check if user table is empty
    public function is_user_table_empty() {
        $query = $this->db->get('user');
        return ($query->num_rows() == 0); // true if empty
    }

    // Get user by email
    public function get_by_email($email) {
        return $this->db->get_where('user', ['email' => $email])->row();
    }

    // Login check
 public function login($email, $password) {
    if ($this->is_user_table_empty()) {
        return 'no_users';
    }

    $user = $this->get_by_email($email);

    if (!$user) {
        return 'not_found';
    }

    if (empty($password)) {
        return 'empty_password';
    }

    //  echo json_encode($email);
    //  echo json_encode($password);
    //  die();
    if ($user->password !== $password) {
        return 'mismatch';
    }

    return $user;
}


}
