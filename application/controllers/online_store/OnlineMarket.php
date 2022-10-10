<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;
/**
 * Class MainScreen
 * @property  online_store_model $OnlineStoreModel
 * @property $form_validation
 */
class OnlineMarket extends RestController
{

	function __construct()
	{
		parent::__construct();
		$this->load->model('onlineStore/online_store_model', "OnlineStoreModel");
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

	public function fetch_online_market_products_post()
	{
		$limit = $this->post('limit');
		if( empty( $limit ) || ! (int)$limit > 0 )
		{
			$limit = 0;
		}

		$onlineMarketProducts = $this->OnlineStoreModel->getOnlineMarketProducts( $this->userId, (int)$limit );
		if( count( $onlineMarketProducts ) == 0 )
		{
			$this->response( [ "status" => FALSE, "data" => [] ] , 200 );
		}
		$this->response( [ "status" => TRUE, "data" => $onlineMarketProducts ] , 200 );
	}
}
