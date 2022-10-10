<?php
class Admin_product_model extends CI_Model
{
	public function __construct()
	{
		$this->load->database();
	}

	public function getAllProducts()
	{
		$this->db->select('*');
		$this->db->from('product');
		$this->db->order_by('id', 'desc');
		$query = $this->db->get();
		return $query->result_array();
	}

	public function getProductById( $id = 0 )
	{
		$this->db->select('*');
		$this->db->where( 'id', $id );
		$this->db->from('product');
		$query = $this->db->get();
		return $query->result_array();
	}

	public function insertProduct( $barkod, $title, $summary, $image, $price, $oldPrice, $onlineMarket )
	{
		$data = [
			"barkod" => $barkod,
			"title" => $title,
			"summary" => $summary,
			"image" => $image,
			"price" => $price,
			"oldPrice" => $oldPrice,
			"onlineMarket" => $onlineMarket
		];
		$this->db->insert('product', $data );
		return $this->db->insert_id();
	}

	public function updateProduct( $productId, $barkod, $title, $summary, $image, $price, $oldPrice, $onlineMarket )
	{
		$data = [
			"barkod" => $barkod,
			"title" => $title,
			"summary" => $summary,
			"price" => $price,
			"oldPrice" => $oldPrice,
			"onlineMarket" => $onlineMarket
		];

		if( ! empty( $image ) )
		{
			$data['image'] = $image;
		}

		$this->db->where('id', $productId );
		$this->db->update('product', $data);
		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE)
		{
			return false;
		}
		return true;
	}

	public function deleteProduct( $productId = 0 )
	{
		$this->db->delete( 'product_category', array('productId' => $productId ) );
//		$this->db->delete( 'order_item', array('productId' => $productId ) );
		$this->db->delete( 'cart_item', array('productId' => $productId ) );
		$this->db->delete( 'user_favorite', array('productId' => $productId ) );
		$removed = $this->db->delete( 'product', array('id' => $productId ) );

		if( $removed )
		{
			return true;
		}

		return false;

	}
}
?>
