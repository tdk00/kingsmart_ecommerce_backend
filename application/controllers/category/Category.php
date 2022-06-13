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
	}
	public function fetch_trend_categories_post()
	{
//		sleep(5);
		$categories = $this->CategoryModel->getTrendCategories();
		if( count( $categories ) == 0 )
		{
			$this->response( [ "status" => FALSE, "data" => [] ] , 200 );
		}
		$this->response( [ "status" => TRUE, "data" => $categories ] , 200 );
	}

	public function fetch_main_categories_post()
	{
//		sleep(5);
		$categories = $this->CategoryModel->getMainCategories();
		if( count( $categories ) == 0 )
		{
			$this->response( [ "status" => FALSE, "data" => [] ] , 200 );
		}
		$this->response( [ "status" => TRUE, "data" => $categories ] , 200 );
	}

	public function fetch_products_by_category_id_post()
	{
//		sleep(5);

		$sortByWhiteList = [ 'none','priceLowToHigh', 'priceHighToLow' ];
		$sortBy = $this->post('sort_by');
		$categoryId = $this->post('category_id');
		$userId = $this->post('user_id');


		if( ! ( $userId > 0 ) )
		{
			$this->response( [ "status" => FALSE, "data" => [] ] , 200 );
		}

		if( ! in_array( $sortBy, $sortByWhiteList ) )
		{
			$sortBy = 'none';
		}

		if( $categoryId == -1 )
		{
			$productList = $this->CategoryModel->getFavoriteProducts( (int)$userId, $sortBy );
		}
		elseif ( $categoryId == -2 )
		{
			$productList = $this->CategoryModel->getAllPromoProducts( (int)$userId, $sortBy );
		}
		elseif ( $categoryId > 0 )
		{
			$productList = $this->CategoryModel->getProductByCategoryId( (int)$userId, $categoryId, $sortBy );
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
//		sleep(5);

		$sortByWhiteList = [ 'none','priceLowToHigh', 'priceHighToLow' ];
		$sortBy = $this->post('sort_by');
		$searchKeyWord = $this->post('search_keyword');
		$userId = $this->post('user_id');


		if( ! ( $userId > 0 ) )
		{
			$this->response( [ "status" => FALSE, "data" => [] ] , 200 );
		}

		if( ! in_array( $sortBy, $sortByWhiteList ) )
		{
			$sortBy = 'none';
		}


		$productList = $this->CategoryModel->getSearchResults( (int)$userId, $sortBy, $searchKeyWord );

		if( count( $productList ) == 0 )
		{
			$this->response( [ "status" => FALSE, "data" => [] ] , 200 );
		}
		$this->response( [ "status" => TRUE, "data" => $productList ] , 200 );
	}

}
