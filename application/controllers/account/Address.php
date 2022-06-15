<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;
/**
 * Class Address
 * @property  address_model $AddressModel
 * @property $form_validation
 */
class Address extends RestController
{

	function __construct()
	{
		parent::__construct();
		$this->load->model('account/address_model', "AddressModel");
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

	public function fetch_addresses_post()
	{

		$addresses = $this->AddressModel->getAddresses( $this->userId );
		if( count( $addresses ) == 0 )
		{
			$this->response( [ "status" => FALSE, "data" => [] ] , 200 );
		}
		$this->response( [ "status" => TRUE, "data" => $addresses ] , 200 );
	}

	public function fetch_selected_address_post()
	{
		$address = $this->AddressModel->getSelectedAddress( $this->userId );
		if( count( $address ) == 0 )
		{
			$this->response( [ "status" => FALSE, "data" => [] ] , 200 );
		}
		$this->response( [ "status" => TRUE, "data" => $address[0] ] , 200 );
	}

	public function fetch_address_by_id_post()
	{
		$addressId = $this->post('address_id');

		$address = $this->AddressModel->getAddressById( $this->userId, (int)$addressId );
		if( count( $address ) == 0 )
		{
			$this->response( [ "status" => FALSE, "data" => [] ] , 200 );
		}
		$this->response( [ "status" => TRUE, "data" => $address[0] ] , 200 );
	}

	public function add_or_edit_address_post()
	{
		$addressDetails = $this->post('address_details');

		if( $addressDetails['id'] > 0 )
		{
			$this->AddressModel->updateAddress( $this->userId, $addressDetails );
			$this->response( [ "status" => TRUE ] , 200 );
		}
		elseif( $addressDetails['id'] == 0 )
		{
			$this->AddressModel->insertAddress( $this->userId, $addressDetails );
			$this->response( [ "status" => TRUE ] , 200 );
		}

	}

	public function delete_address_post()
	{
		$addressId = $this->post('address_id');

		if( $addressId > 0 )
		{
			$this->AddressModel->deleteAddress( $this->userId, $addressId );
			$this->response( [ "status" => TRUE ] , 200 );
		}
		else
		{
			$this->response( [ "status" => false ] );
		}

	}

	public function set_selected_address_post()
	{
		$addressId = $this->post('address_id');

		if( $addressId > 0 )
		{
			$updated = $this->AddressModel->setSelectedAddress( $this->userId, $addressId );
			if( $updated )
			{
				$this->response( [ "status" => TRUE ] , 200 );
			}
			else
			{
				$this->response( [ "status" => false ] );
			}
		}
		else
		{
			$this->response( [ "status" => false ] );
		}

	}
}
