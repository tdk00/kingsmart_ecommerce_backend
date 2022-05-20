<?php
class Promo_model extends CI_Model
{
	public function __construct()
	{
		$this->load->database();
	}

	public function getPromoProducts( $userId = 0, $limit =  0 )
	{
		$limitQuery = $limit > 0 ? "LIMIT ".$limit : "";
		$query = $this->db->query('SELECT product.*,  IF( ( product.oldPrice - product.price ) > 0 , product.oldPrice - product.price, 0 ) as discount, IFNULL(user_favorite.productId, 0) > 0 as isFavorite FROM product LEFT JOIN user_favorite ON product.id = user_favorite.productId AND user_favorite.userId = '. (int)$userId .' WHERE product.price < product.Oldprice'. $limitQuery .';');
		return $query->result_array();
	}

}
