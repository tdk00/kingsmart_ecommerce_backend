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
	}

	public function fetch_terms_post()
	{
		$terms = $this->ContactUsModel->getOption( 'terms' );

		$this->response( [ "status" => TRUE, "data" => $terms ] , 200 );
	}
}
