<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;
/**
 * Class MainScreen
 * @property  category_model $CategoryModel
 * @property $form_validation
 */
class Category extends RestController
{

	function __construct()
	{
		parent::__construct();
		$this->load->model('category/category_model', "CategoryModel");
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
}
