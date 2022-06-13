<?php
class Contact_us_model extends CI_Model
{
	public function __construct()
	{
		$this->load->database();
	}

	public function getOption( $key = '' )
	{
		$this->db->select('*');
		$this->db->from('options');
		$this->db->where('option_key', $key);
		$query = $this->db->get();
		$result = $query->result_array();
		return count( $result ) > 0 ? $result[0]['option_value'] : '';
	}

}
