<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;
/**
 * Class Profile
 * @property  profile_model $ProfileModel
 * @property $form_validation
 */
class Profile extends RestController
{

	function __construct()
	{
		parent::__construct();
		$this->load->model('account/profile_model', "ProfileModel");
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

	public function fetch_profile_details_post()
	{
		$profileDetails = $this->ProfileModel->getProfileDetails( $this->userId );
		if( count( $profileDetails ) == 0 )
		{
			$this->response( [ "status" => FALSE, "data" => [] ] , 200 );
		}
		$this->response( [ "status" => TRUE, "data" => $profileDetails[0] ] , 200 );
	}

	public function update_profile_post()
	{
		$profileDetails = $this->post('profile_details');

		if( !empty( $profileDetails['id']))
		{
			unset($profileDetails['id']);
		}
		else
		{
			$this->response( [ "status" => FALSE, "data" => [] ] , 200 );
		}


		$this->ProfileModel->updateProfile( $this->userId, $profileDetails );

		$this->response( [ "status" => TRUE, "data" => [] ] , 200 );
	}
}
