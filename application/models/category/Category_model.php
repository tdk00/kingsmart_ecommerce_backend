<?php
class Category_model extends CI_Model
{
	public function __construct()
	{
		$this->load->database();
	}

	public function getTrendCategories()
	{
		$this->db->select('*');
		$this->db->from('category');
		$this->db->where('isTrend', TRUE);
		$this->db->where('parentId', NULL);
		$query = $this->db->get();
		return $query->result_array();
	}

	public function getMainCategories()
	{
		$this->db->select('*');
		$this->db->from('category');
		$this->db->where('parentId', NULL);
		$query = $this->db->get();
		return $query->result_array();
	}

}
