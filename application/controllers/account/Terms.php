<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;
/**
 * Class Profile
 * @property  contact_us_model $ContactUsModel
 * @property $form_validation
 */
class Terms extends RestController
{

	function __construct()
	{
		parent::__construct();
		$this->load->model('account/contact_us_model', "ContactUsModel");
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

	public function fetch_terms_post()
	{
		$terms = $this->ContactUsModel->getOption( 'terms' );

		$this->response( [ "status" => TRUE, "data" => $terms ] , 200 );
	}
}
