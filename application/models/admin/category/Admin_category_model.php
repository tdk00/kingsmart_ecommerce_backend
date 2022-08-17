<?php
class Admin_category_model extends CI_Model
{
	public function __construct()
	{
		$this->load->database();
	}

	public function getAllCategories()
	{
		$this->db->select('*');
		$this->db->from('category');
		$this->db->order_by('id', 'desc');
		$query = $this->db->get();
		return $query->result_array();
	}

	public function getAllProducts( $categoryId )
	{
		$query = $this->db->query("SELECT *, IFNULL(product_category.productId, 0) AS 'inCategory' FROM product LEFT JOIN product_category ON product_category.categoryId = ".$categoryId." AND product_category.productId = product.id ORDER BY inCategory DESC LIMIT 20 ;");
		return $query->result_array();
	}

	public function searchProduct( $keyword, $categoryId )
	{
		$query = $this->db->query("SELECT *, IFNULL(product_category.productId, 0) AS 'inCategory' FROM product LEFT JOIN product_category ON product_category.categoryId = ".$categoryId." AND product_category.productId = product.id WHERE product.title LIKE '%".$keyword."%' ORDER BY inCategory DESC LIMIT 20;");
		return $query->result_array();
	}

	public function addProductToCategory( $productId, $categoryId )
	{
		$data = [
			"productId" => $productId,
			"categoryId" => $categoryId
		];
		$this->db->insert('product_category', $data );
		return $this->db->insert_id();
	}

	public function deleteProductFromCategory($productId,  $categoryId = 0 )
	{
		$removed = $this->db->delete( 'product_category', array("productId" => $productId, "categoryId" => $categoryId ) );

		if( $removed )
		{
			return true;
		}

		return false;

	}

	public function getCategoryById( $id = 0 )
	{
		$this->db->select('*');
		$this->db->where( 'id', $id );
		$this->db->from('category');
		$query = $this->db->get();
		return $query->result_array();
	}

	public function insertCategory( $title, $image )
	{
		$data = [
			"title" => $title,
			"image" => $image
		];
		$this->db->insert('category', $data );
		return $this->db->insert_id();
	}

	public function updateCategory( $categoryId, $title, $image )
	{
		$data = [
			"title" => $title
		];

		if( ! empty( $image ) )
		{
			$data['image'] = $image;
		}

		$this->db->where('id', $categoryId );
		$this->db->update('category', $data);
		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE)
		{
			return false;
		}
		return true;
	}

	public function deleteCategory( $categoryId = 0 )
	{
		$removed = $this->db->delete( 'category', array('id' => $categoryId ) );

		if( $removed )
		{
			return true;
		}

		return false;

	}
}
?>
