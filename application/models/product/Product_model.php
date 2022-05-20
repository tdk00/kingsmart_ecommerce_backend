<?php
class Product_model extends CI_Model
{
	public function __construct()
	{
		$this->load->database();
	}

	public function getProductById( $productId = 0, $userId = 0)
	{
		if( is_integer( $productId ) && is_integer( $userId ) )
		{
			$query = $this->db->query('SELECT product.*, IF( ( product.oldPrice - product.price ) > 0 , product.oldPrice - product.price, 0 ) as discount, IFNULL((SELECT user_favorite.userId FROM user_favorite WHERE user_favorite.userId = '. $userId .' AND  user_favorite.productId = '. $productId .'), 0 ) AS isFavorite FROM `product` WHERE product.id = '. $productId .';');
		}
		else
		{
			exit('wrong parameters');
		}
		$result = $query->result_array();
		return count( $result ) > 0 ? $result[0] : [] ;
	}

	public function getShoppingCartItems( $cart_id = 0)
	{
		$this->db->select('*');
		$this->db->from('cart_item');
		$this->db->where('cartId', $cart_id);
		$query = $this->db->get();
		return $query->result_array();
	}

	public function getFavoriteProducts( $userId = 0, $limit =  0 )
	{
		$limitQuery = $limit > 0 ? "LIMIT ".$limit : "";
		$query = $this->db->query('SELECT product.*, IF( ( product.oldPrice - product.price ) > 0 , product.oldPrice - product.price, 0 ) as discount, 1 AS isFavorite FROM `product` WHERE EXISTS (SELECT user_favorite.userId FROM user_favorite WHERE user_favorite.userId = '. $userId .' AND user_favorite.productId = product.id)'. $limitQuery .';');
		return $query->result_array();
	}
	public function deleteFromFavorites( $userId = 0 , $productId = 0)
	{
		$this->db->delete( 'user_favorite', array('userId' => $userId, 'productId' => $productId) );
		return false;
	}
	public function insertFavorites( $userId = 0 , $productId = 0)
	{
		$this->db->delete( 'user_favorite', array('userId' => $userId, 'productId' => $productId) );
		$this->db->insert('user_favorite', array( 'userId' => $userId, 'productId' => $productId ) );
		return true;
	}
}
