<?php
class Shopping_cart_model extends CI_Model
{
	public function __construct()
	{
		$this->load->database();
	}

	public function getCartId( $user_id = 0)
	{
		$this->db->select('id');
		$this->db->from('cart');
		$this->db->where('userId', $user_id);
		$query = $this->db->get();
		$result = $query->result_array();
		return count ( $result ) > 0 ? $result[0]['id'] : 0;
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

	public function insertItem( $insert_data )
	{
		$this->db->insert('cart_item', $insert_data );
		return $this->db->insert_id();
	}

	public function clearCart( $cart_id = 0 )
	{
		$this->db->delete( 'cart_item', array('cartId' => $cart_id) );
	}

	public function updateShoppingCart( $cart_id = 0)
	{
		$this->db->select('*');
		$this->db->from('cart_item');
		$this->db->where('cartId', $cart_id);
		$query = $this->db->get();
		return $query->result_array();
	}
}
