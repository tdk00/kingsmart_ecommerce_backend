<?php
class Online_store_model extends CI_Model
{
	public function __construct()
	{
		$this->load->database();
	}

	public function getOnlineMarketProducts($userId = 0, $limit =  0 )
	{
		$limitQuery = $limit > 0 ? " LIMIT ".$limit : "";
		$query = $this->db->query('SELECT product.*, IFNULL(user_product_note.note, "") as note, IF( ( product.oldPrice - product.price ) > 0 , product.oldPrice - product.price, 0 ) as discount, IFNULL(user_favorite.productId, 0) > 0 as isFavorite FROM product LEFT JOIN user_favorite ON product.id = user_favorite.productId AND user_favorite.userId = '. (int)$userId .' LEFT JOIN user_product_note ON product.id = user_product_note.productId AND user_product_note.userId = '. (int)$userId .' WHERE onlineMarket = 1'. $limitQuery .';');
		return $query->result_array();
	}

}
