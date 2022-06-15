<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;
/**
 * Class MainScreen
 * @property  promo_model $PromoModel
 * @property $form_validation
 */
class Promo extends RestController
{

	function __construct()
	{
		parent::__construct();
		$this->load->model('promo/promo_model', "PromoModel");
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

	public function fetch_promo_products_post()
	{
		$limit = $this->post('limit');
		if( empty( $limit ) || ! (int)$limit > 0 )
		{
			$limit = 0;
		}

		$promoProducts = $this->PromoModel->getPromoProducts( $this->userId, (int)$limit );
		if( count( $promoProducts ) == 0 )
		{
			$this->response( [ "status" => FALSE, "data" => [] ] , 200 );
		}
		$this->response( [ "status" => TRUE, "data" => $promoProducts ] , 200 );
	}
}
