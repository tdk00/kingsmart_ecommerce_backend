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
			$query = $this->db->query('SELECT product.*, IFNULL(user_product_note.note, "") as note, IF( ( product.oldPrice - product.price ) > 0 , product.oldPrice - product.price, 0 ) as discount, IFNULL((SELECT user_favorite.userId FROM user_favorite WHERE user_favorite.userId = '. $userId .' AND  user_favorite.productId = '. $productId .'), 0 ) AS isFavorite FROM `product` LEFT JOIN user_product_note ON product.id = user_product_note.productId AND user_product_note.userId = '. (int)$userId .' WHERE product.id = '. $productId .';');
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

	public function getRelatedProducts( $productId = 0, $userId = 0 )
	{
		$query = $this->db->query('SELECT product.*,  IFNULL(user_product_note.note, "") as note, IF( ( product.oldPrice - product.price ) > 0 , product.oldPrice - product.price, 0 ) as discount, IFNULL(user_favorite.productId, 0) > 0 as isFavorite FROM product LEFT JOIN product_category ON product.id = product_category.productId LEFT JOIN user_favorite ON product.id = user_favorite.productId AND user_favorite.userId = '. (int)$userId .' LEFT JOIN user_product_note ON product.id = user_product_note.productId AND user_product_note.userId = '. (int)$userId . ' WHERE product.id <> '. $productId .' ORDER by RAND() LIMIT 2;');
		return $query->result_array();
	}

	public function getFavoriteProducts( $userId = 0, $limit =  0 )
	{
		$limitQuery = $limit > 0 ? "LIMIT ".$limit : "";
		$query = $this->db->query('SELECT product.*, IFNULL(user_product_note.note, "") as note, IF( ( product.oldPrice - product.price ) > 0 , product.oldPrice - product.price, 0 ) as discount, 1 AS isFavorite FROM `product` LEFT JOIN user_product_note ON product.id = user_product_note.productId AND user_product_note.userId = '. (int)$userId .' WHERE EXISTS (SELECT user_favorite.userId FROM user_favorite WHERE user_favorite.userId = '. $userId .' AND user_favorite.productId = product.id)'. $limitQuery .';');
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
	public function insertNote( $userId = 0 , $productId = 0, $note = "")
	{
		$this->db->delete( 'user_product_note', array('userId' => $userId, 'productId' => $productId) );
		$this->db->insert('user_product_note', array( 'userId' => $userId, 'productId' => $productId, 'note' => $note ) );
		return $note;
	}
}
