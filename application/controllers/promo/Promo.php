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
	}

	public function fetch_promo_products_post()
	{
//		sleep(2);
		$userId = $this->post('user_id');
		$limit = $this->post('limit');
		if( empty( $limit ) || ! (int)$limit > 0 )
		{
			$limit = 0;
		}

		$promoProducts = $this->PromoModel->getPromoProducts( (int)$userId, (int)$limit );
		if( count( $promoProducts ) == 0 )
		{
			$this->response( [ "status" => FALSE, "data" => [] ] , 200 );
		}
		$this->response( [ "status" => TRUE, "data" => $promoProducts ] , 200 );
	}
}
