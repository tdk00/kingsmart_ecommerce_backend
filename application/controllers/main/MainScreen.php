<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;
/**
 * Class MainScreen
 * @property  main_screen_model $MainScreenModel
 * @property $form_validation
 */
class MainScreen extends RestController
{

	function __construct()
	{
		parent::__construct();
		$this->load->model('main/main_screen_model', "MainScreenModel");
		$this->load->library('form_validation');
	}
	public function fetch_sliders_post()
	{
		$sliders = $this->MainScreenModel->getSliders();
		if( count( $sliders ) == 0 )
		{
			$this->response( [ "status" => FALSE, "data" => [] ] , 200 );
		}
		$this->response( [ "status" => TRUE, "data" => $sliders ] , 200 );
	}
}
