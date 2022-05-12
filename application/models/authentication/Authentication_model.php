<?php
class Authentication_model extends CI_Model
{
	public function __construct()
	{
		$this->load->database();
	}

	public function insertCustomer( $phone = "" )
	{
		$data = [
			"phone" => $phone
		];
		$this->db->insert('customers', $data );
		return $this->db->insert_id();
	}

	public function insertOtp( $otp, $customer_id, $expired )
	{
		$data = [
			"customer_id" => $customer_id,
			"otp" => $otp,
			"expired" => $expired
		];
		$this->db->insert('customer_authentication', $data );
		return $this->db->insert_id();
	}

	public function getCustomerId( $phone )
	{
		$this->db->select('*');
		$this->db->from('customers');
		$this->db->where('phone', $phone);
		$query = $this->db->get();
		$result_array = $query->result_array();

		return count($result_array) > 0 ? $result_array[0]['id'] : 0;
	}

	public function checkOtp( $customer_id, $otp )
	{
		$this->db->select('*');
		$this->db->from('customer_authentication');
		$this->db->where('customer_id', $customer_id);
		$this->db->where('otp', $otp);
		$this->db->where('expired > ', time() );
		$query = $this->db->get();
		$result_array = $query->result_array();

		return count($result_array) > 0;
	}


	public function deleteCustomerOldOtp( $customer_id = 0 )
	{
		$removed_all = $this->db->delete( 'customer_authentication', array('customer_id' => $customer_id) );

		if( $removed_all )
		{
			return true;
		}

		return false;
	}

}
