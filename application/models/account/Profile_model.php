<?php
class Profile_model extends CI_Model
{
	public function __construct()
	{
		$this->load->database();
	}

	public function getProfileDetails( $userId = 0 )
	{
		$this->db->select("*");
		$this->db->from("user");
		$this->db->where("id", $userId);
		$query = $this->db->get();
		return $query->result_array();
	}

	public function updateProfile( $userId , $profileDetails  )
	{
		$this->db->where('id', $userId);
		$this->db->update('user', $profileDetails);
		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE)
		{
			return false;
		}
		return true;
	}

}
