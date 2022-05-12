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
	 * eger customer movcud deyilse register edir sonra OTP yaradir
	 * eger customer movcuddursa bazadan butun kohne OTP-lerini silir , yenisini yaradir
	 * @return integer otp yaradilarsa id-sini qaytarir yoxsa 0
	 */
	public function create_otp_post()
	{
		$phone = $this->post( 'phone' );

		$phone = "00".str_replace(['+', '-', ' ', '(', ')'],'', $phone );

		if( ! $this->validate_phone($phone))
		{
			$this->response( [ "status" => FALSE ] , 200 );
		}


		if ( ! $this->customer_exists( $phone ) )
		{
			$customer_id = $this->AuthenticationModel->insertCustomer( $phone );
			$this->generate_otp( $customer_id );
			$this->response( [ "status" => TRUE ] , 200 );
		}
		else
		{
			$customer_id = $this->AuthenticationModel->getCustomerId( $phone );
			if( $customer_id > 0 ) {
				$this->AuthenticationModel->deleteCustomerOldOtp( $customer_id );
				$this->generate_otp( $customer_id );
				$this->response( [ "status" => TRUE ] , 200 );

			}

		}
		$this->response( [ "status" => FALSE ] , 200 );

	}

	public function confirm_otp_post()
	{
		$phone = $this->post( 'phone' );
		$otp = $this->post( 'otp' );

		$phone = "00".str_replace(['+', '-', ' ', '(', ')'],'', $phone );

		if( ! $this->validate_phone($phone))
		{
			$this->response( [ "status" => FALSE ] , 200 );
		}

		$customer_id = $this->AuthenticationModel->getCustomerId( $phone );
		if ( $customer_id > 0 )
		{
			$otpIsTrue = $this->AuthenticationModel->checkOtp( $customer_id, $otp );
			if( $otpIsTrue )
			{
				$this->load->library('Authorization_Token');
				$token_data['customer_id'] = $customer_id;
				$token_data['time'] = time();

				$customer_token = $this->authorization_token->generateToken( $token_data );

				$this->response( [
					"status" => TRUE,
					"customer_id" => $customer_id,
					"customer_token" => $customer_token
				] , 200 );
			}
		}

		$this->response( [ "status" => FALSE ] , 200 );

	}


	/**
	 * OTP generate eden ve bazaya insert eden metoddur
	 * @param $customer_id - OTP nin yaradilacagi customer'in id si
	 * @var $expired - OTP nin vaxtinin biteceyi epch time
	 * @return integer sone elave edilen OTP - idsi ni qaytarir
	 */
	private function generate_otp( $customer_id )
	{
		$otp = rand(1000, 9999);
		$lifetime = 900; // seconds
		$expired = time() + $lifetime;
		return $this->AuthenticationModel->insertOtp( $otp, $customer_id, $expired );
	}

	private function customer_exists( $phone )
	{
		return $this->AuthenticationModel->getCustomerId( $phone );
	}

	private function validate_phone( $phone )
	{
		$valid_number = filter_var($phone, FILTER_SANITIZE_NUMBER_INT);
		return $valid_number == $phone;
	}
}
