<?php
class Main_screen_model extends CI_Model
{
	public function __construct()
	{
		$this->load->database();
	}

	public function getSliders()
	{
		$this->db->select('*');
		$this->db->from('reference_sliders');
		$query = $this->db->get();
		return $query->result_array();
	}

}
