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

	public function getProductByCategoryId($userId, $categoryId, $orderBy )
	{
		if ( $orderBy == 'priceLowToHigh')
		{
			$orderByQuery = ' ORDER by product.price ASC';
		}
		elseif ( $orderBy == 'priceHighToLow' )
		{
			$orderByQuery = ' ORDER by product.price DESC';
		}
		else
		{
			$orderByQuery = '';
		}
		$query = $this->db->query('SELECT product.*,  IF( ( product.oldPrice - product.price ) > 0 , product.oldPrice - product.price, 0 ) as discount, IFNULL(user_favorite.productId, 0) > 0 as isFavorite FROM product LEFT JOIN product_category ON product.id = product_category.productId LEFT JOIN user_favorite ON product.id = user_favorite.productId AND user_favorite.userId = '. (int)$userId .' WHERE product_category.categoryId = '. $categoryId . $orderByQuery .';');
		return $query->result_array();
	}

}
