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
	}

	public function fetch_profile_details_post()
	{
		sleep(2);
		$userId = $this->post('user_id');

		$profileDetails = $this->ProfileModel->getProfileDetails( (int)$userId );
		if( count( $profileDetails ) == 0 )
		{
			$this->response( [ "status" => FALSE, "data" => [] ] , 200 );
		}
		$this->response( [ "status" => TRUE, "data" => $profileDetails[0] ] , 200 );
	}

	public function update_profile_post()
	{
//		sleep(2);
		$userId = $this->post('user_id');
		$profileDetails = $this->post('profile_details');

		if( !empty( $profileDetails['id']))
		{
			unset($profileDetails['id']);
		}
		else
		{
			$this->response( [ "status" => FALSE, "data" => [] ] , 200 );
		}


		$this->ProfileModel->updateProfile( (int)$userId, $profileDetails );

		$this->response( [ "status" => TRUE, "data" => [] ] , 200 );
	}
}
