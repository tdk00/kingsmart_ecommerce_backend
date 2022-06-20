<?php
class Admin_news_model extends CI_Model
{
	public function __construct()
	{
		$this->load->database();
	}

	public function getAllNews()
	{
		$this->db->select('*');
		$this->db->from('news');
		$this->db->order_by('id', 'desc');
		$query = $this->db->get();
		return $query->result_array();
	}
}
?>
