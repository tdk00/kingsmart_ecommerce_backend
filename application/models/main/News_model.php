<?php
class News_model extends CI_Model
{
	public function __construct()
	{
		$this->load->database();
	}

	public function getNewsById( $newsId = 0 )
	{
		$this->db->select('*');
		$this->db->from('news');
		$this->db->where('id', $newsId);
		$query = $this->db->get();
		return $query->result_array();
	}

	public function getAllNews()
	{
		$this->db->select('*');
		$this->db->from('news');
		$query = $this->db->get();
		return $query->result_array();
	}

}
