<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;
/**
 * Class MainScreen
 * @property  product_model $ProductModel
 * @property $form_validation
 */
class Product extends RestController
{

	function __construct()
	{
		parent::__construct();
		$this->load->model('product/product_model', "ProductModel");
		$this->load->library('form_validation');
	}

	public function fetch_favorite_products_post()
	{
//		sleep(2);
		$userId = $this->post('user_id');
		$limit = $this->post('limit');
		if( ! empty( $limit ) && (int)$limit > 0 )
		{
			$limit = 6;
		}
		else
		{
			$limit = 0;
		}

		$favoriteProducts = $this->ProductModel->getFavoriteProducts( (int)$userId, $limit );
		if( count( $favoriteProducts ) == 0 )
		{
			$this->response( [ "status" => FALSE, "data" => [] ] , 200 );
		}
		$this->response( [ "status" => TRUE, "data" => $favoriteProducts ] , 200 );
	}

	public function toggle_is_favorite_post()
	{
		$userId = $this->post('user_id');
		$productId = $this->post('product_id');
		$isFavorite = $this->post('is_favorite');

		if( empty( $userId ) || ! (int)$userId > 0 || empty( $productId ) || ! (int)$productId > 0 && empty( $isFavorite ))
		{
			$this->response( [ "status" => false ] , 200 );
		}

		if( ! is_bool( $isFavorite ))
		{
			$this->response( [ "status" => false ] , 200 );
		}

			if( $isFavorite )
			{
				$isFavorite = $this->ProductModel->insertFavorites( (int)$userId, $productId );
			}
			else
			{
				$isFavorite = $this->ProductModel->deleteFromFavorites( (int)$userId, $productId );
			}



			$this->response( [ "status" => true, "data" => ["isFavorite" => $isFavorite]  ] , 200 );

	}

}
