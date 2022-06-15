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

		header("Access-Control-Allow-Origin: *");
		$this->load->library('Authorization_Token');
		/**
		 * User Token Validation
		 */
		$this->is_valid_token = $this->authorization_token->validateToken();
		$this->userId = 0;
		if ( ! empty($this->is_valid_token) && $this->is_valid_token['status'] === TRUE )
		{
			$this->userId = (int)$this->is_valid_token['data']->id;
		}
		else
		{
			$this->response( [ "status" => FALSE, "data" => ['message' => "invalid token"] ] , 200 );
		}
	}

	public function fetch_product_by_id_post()
	{
		$productId = $this->post('product_id');
		$product = $this->ProductModel->getProductById( (int)$productId, $this->userId );
		if( count( $product ) == 0 )
		{
			$this->response( [ "status" => FALSE, "data" => [] ] , 200 );
		}
		$this->response( [ "status" => TRUE, "data" => ["product" => $product]  ] , 200 );
	}

	public function fetch_related_products_post()
	{
		$productId = $this->post('product_id');
		$productList = $this->ProductModel->getRelatedProducts( (int)$productId, $this->userId );
		if( count( $productList ) == 0 )
		{
			$this->response( [ "status" => FALSE, "data" => [] ] , 200 );
		}
		$this->response( [ "status" => TRUE, "data" => $productList  ] , 200 );
	}

	public function fetch_favorite_products_post()
	{

		$limit = $this->post('limit');
		if( ! empty( $limit ) && (int)$limit > 0 )
		{
			$limit = 6;
		}
		else
		{
			$limit = 0;
		}

		$favoriteProducts = $this->ProductModel->getFavoriteProducts( $this->userId, $limit );
		if( count( $favoriteProducts ) == 0 )
		{
			$this->response( [ "status" => FALSE, "data" => [] ] , 200 );
		}
		$this->response( [ "status" => TRUE, "data" => $favoriteProducts ] , 200 );
	}

	public function toggle_is_favorite_post()
	{
		$productId = $this->post('product_id');
		$isFavorite = $this->post('is_favorite');

		if( empty( $this->userId ) || ! $this->userId > 0 || empty( $productId ) || ! (int)$productId > 0 && empty( $isFavorite ))
		{
			$this->response( [ "status" => false ] , 200 );
		}

		if( ! is_bool( $isFavorite ))
		{
			$this->response( [ "status" => false ] , 200 );
		}

			if( $isFavorite )
			{
				$isFavorite = $this->ProductModel->insertFavorites( $this->userId, $productId );
			}
			else
			{
				$isFavorite = $this->ProductModel->deleteFromFavorites( $this->userId, $productId );
			}



			$this->response( [ "status" => true, "data" => ["isFavorite" => $isFavorite]  ] , 200 );

	}

	public function edit_note_post()
	{
		$productId = $this->post('product_id');
		$note = $this->post('note');

		if( empty( $this->userId ) || ! $this->userId > 0 || empty( $productId ) || ! (int)$productId > 0 && empty( $note ))
		{
			$this->response( [ "status" => false ] , 200 );
		}

		$note = $this->ProductModel->insertNote( $this->userId, $productId, $note );

		$this->response( [ "status" => true, "data" => ["note" => $note]  ] , 200 );

	}

}
