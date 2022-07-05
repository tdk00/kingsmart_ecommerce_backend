<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;
/**
 * Class Authentication
 * @property  authentication_model $AuthenticationModel
 * @property $form_validation
 */
class Authentication extends RestController
{

	function __construct()
	{
		parent::__construct();
		$this->load->model('authentication/authentication_model', "AuthenticationModel");
		$this->load->library('form_validation');
	}

	/**
	 * OTP yaradan metoddur
	 * eger user movcud deyilse register edir sonra OTP yaradir
	 * eger user movcuddursa bazadan butun kohne OTP-lerini silir , yenisini yaradir
	 * @return integer otp yaradilarsa id-sini qaytarir yoxsa 0
	 */
	public function create_otp_post()
	{
		$mobile = $this->post( 'mobile' );


		if( empty($mobile) || strlen($mobile) < 8)
		{
			$this->response( [ "status" => FALSE ] , 200 );
		}

		$mobile = "00".str_replace(['+', '-', ' ', '(', ')'],'', $mobile );


		if( ! $this->validate_mobile($mobile))
		{
			$this->response( [ "status" => FALSE ] , 200 );
		}


		if ( ! $this->user_exists( $mobile ) )
		{
			$user_id = $this->AuthenticationModel->insertUser( $mobile );
			$this->generate_otp( $user_id, $mobile );
			$this->response( [ "status" => TRUE ] , 200 );
		}
		else
		{
			$user_id = $this->AuthenticationModel->getUserId( $mobile );
			if( $user_id > 0 ) {
				$this->AuthenticationModel->deleteUserOldOtp( $user_id );
				$this->generate_otp( $user_id, $mobile );
				$this->response( [ "status" => TRUE ] , 200 );

			}

		}
		$this->response( [ "status" => FALSE ] , 200 );

	}

	public function confirm_otp_post()
	{
		$mobile = $this->post( 'mobile' );
		$otp = $this->post( 'otp' );

		$mobile = "00".str_replace(['+', '-', ' ', '(', ')'],'', $mobile );

		if( ! $this->validate_mobile($mobile))
		{
			$this->response( [ "status" => FALSE ] , 200 );
		}

		$user_id = $this->AuthenticationModel->getUserId( $mobile );
		if ( $user_id > 0 )
		{
			$otpIsTrue = $this->AuthenticationModel->checkOtp( $user_id, $otp );
			if( $otpIsTrue )
			{
				$this->load->library('Authorization_Token');
				$token_data['id'] = $user_id;
				$token_data['mobile'] = $mobile;
				$token_data['time'] = time();

				$user_token = $this->authorization_token->generateToken( $token_data );

				$this->response( [
					"status" => TRUE,
					"user_id" => $user_id,
					"user_token" => $user_token
				] , 200 );
			}
		}

		$this->response( [ "status" => FALSE ] , 200 );

	}


	/**
	 * OTP generate eden ve bazaya insert eden metoddur
	 * @param $user_id - OTP nin yaradilacagi user'in id si
	 * @var $expired - OTP nin vaxtinin biteceyi epoch time
	 * @return integer sone elave edilen OTP - idsi ni qaytarir
	 */
	private function generate_otp( $user_id, $mobile )
	{
		$otp = rand(1000, 9999);
		$lifetime = 900; // seconds
		$expired = time() + $lifetime;

//		$otp = 1234; // bu test ucun beledir , silinmelidir setir
		$this->send_otp_sms( $otp, $mobile );
		return $this->AuthenticationModel->insertOtp( $otp, $user_id, $expired );
	}

	private function user_exists( $mobile )
	{
		return $this->AuthenticationModel->getUserId( $mobile );
	}

	private function send_otp_sms( $otp, $mobile )
	{
		$mobile = str_replace('00994', '0', $mobile);

		$xml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
				<SMS-InsRequest>
    			<CLIENT from="Kingsmart" pwd="test" user="test"/>
    			<INSERT datacoding="0" to="'.$mobile.'">
    			<TEXT> Şifrə: '. $otp .'  </TEXT>
    			</INSERT>
				</SMS-InsRequest>';

		$url = "http://89.108.99.126/sendsmsapi/"; // URL to make some test
		$ch = curl_init($url);

		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
		curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		$data = curl_exec($ch);
//		echo '<pre>';
//		echo htmlentities($data);
//		echo '</pre>';

		if (curl_errno($ch))
			curl_error($ch);
		else
			curl_close($ch);


	}

	private function validate_mobile( $mobile )
	{
		$valid_number = filter_var($mobile, FILTER_SANITIZE_NUMBER_INT);
		return $valid_number == $mobile;
	}
}
