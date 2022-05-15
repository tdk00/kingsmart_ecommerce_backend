<?php
class Shopping_cart_model extends CI_Model
{
	public function __construct()
	{
		$this->load->database();
	}

	public function getShoppingCart( $user_id = 0)
	{
		$this->db->select('*');
		$this->db->from('cart');
		$this->db->where('userId', $user_id);
		$query = $this->db->get();
		return $query->result_array();
	}

	public function getShoppingCartItems( $cart_id = 0)
	{
		$this->db->select('*');
		$this->db->from('cart_item');
		$this->db->where('cartId', $cart_id);
		$query = $this->db->get();
		return $query->result_array();
	}
}
