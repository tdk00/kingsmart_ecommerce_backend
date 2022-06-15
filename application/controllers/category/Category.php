<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;
/**
 * Class MainScreen
 * @property  category_model $CategoryModel
 * @property  product_model $ProductModel
 * @property $form_validation
 */
class Category extends RestController
{

	function __construct()
	{
		parent::__construct();
		$this->load->model('category/category_model', "CategoryModel");
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
	public function fetch_trend_categories_post()
	{
		$categories = $this->CategoryModel->getTrendCategories();
		if( count( $categories ) == 0 )
		{
			$this->response( [ "status" => FALSE, "data" => [] ] , 200 );
		}
		$this->response( [ "status" => TRUE, "data" => $categories ] , 200 );
	}

	public function fetch_main_categories_post()
	{
		$categories = $this->CategoryModel->getMainCategories();
		if( count( $categories ) == 0 )
		{
			$this->response( [ "status" => FALSE, "data" => [] ] , 200 );
		}
		$this->response( [ "status" => TRUE, "data" => $categories ] , 200 );
	}

	public function fetch_products_by_category_id_post()
	{
		$sortByWhiteList = [ 'none','priceLowToHigh', 'priceHighToLow' ];
		$sortBy = $this->post('sort_by');
		$categoryId = $this->post('category_id');


		if( ! ( $this->userId > 0 ) )
		{
			$this->response( [ "status" => FALSE, "data" => [] ] , 200 );
		}

		if( ! in_array( $sortBy, $sortByWhiteList ) )
		{
			$sortBy = 'none';
		}

		if( $categoryId == -1 )
		{
			$productList = $this->CategoryModel->getFavoriteProducts( $this->userId, $sortBy );
		}
		elseif ( $categoryId == -2 )
		{
			$productList = $this->CategoryModel->getAllPromoProducts( $this->userId, $sortBy );
		}
		elseif ( $categoryId > 0 )
		{
			$productList = $this->CategoryModel->getProductByCategoryId( $this->userId, $categoryId, $sortBy );
		}
		else
		{
			$this->response( [ "status" => FALSE, "data" => [] ] , 200 );
		}

		if( count( $productList ) == 0 )
		{
			$this->response( [ "status" => FALSE, "data" => [] ] , 200 );
		}
		$this->response( [ "status" => TRUE, "data" => $productList ] , 200 );
	}

	public function fetch_products_by_search_post()
	{
		$sortByWhiteList = [ 'none','priceLowToHigh', 'priceHighToLow' ];
		$sortBy = $this->post('sort_by');
		$searchKeyWord = $this->post('search_keyword');


		if( ! ( $this->userId > 0 ) )
		{
			$this->response( [ "status" => FALSE, "data" => [] ] , 200 );
		}

		if( ! in_array( $sortBy, $sortByWhiteList ) )
		{
			$sortBy = 'none';
		}


		$productList = $this->CategoryModel->getSearchResults( $this->userId, $sortBy, $searchKeyWord );

		if( count( $productList ) == 0 )
		{
			$this->response( [ "status" => FALSE, "data" => [] ] , 200 );
		}
		$this->response( [ "status" => TRUE, "data" => $productList ] , 200 );
	}

}
