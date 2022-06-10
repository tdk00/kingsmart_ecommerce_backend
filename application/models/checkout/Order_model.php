<?php
class Order_model extends CI_Model
{
	public function __construct()
	{
		$this->load->database();
	}

	public function insertOrder( $data )
	{
		$this->db->insert('order',$data );
		return $this->db->insert_id();
	}

	public function insertOrderItem( $data )
	{
		$this->db->insert('order_item', $data );
		return $this->db->insert_id();
	}
	public function getLastOrder( $userId )
	{
		$this->db->select('*');
		$this->db->from('order');
		$this->db->where('userId', $userId);
		$this->db->order_by('id', 'DESC');
		$this->db->limit(1);
		$query = $this->db->get();
		return $query->result_array();
	}
}
