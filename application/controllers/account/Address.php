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
	}

	public function fetch_addresses_post()
	{
//		sleep(2);
		$userId = $this->post('user_id');

		$addresses = $this->AddressModel->getAddresses( (int)$userId );
		if( count( $addresses ) == 0 )
		{
			$this->response( [ "status" => FALSE, "data" => [] ] , 200 );
		}
		$this->response( [ "status" => TRUE, "data" => $addresses ] , 200 );
	}

	public function fetch_selected_address_post()
	{
//		sleep(2);
		$userId = $this->post('user_id');

		$address = $this->AddressModel->getSelectedAddress( (int)$userId );
		if( count( $address ) == 0 )
		{
			$this->response( [ "status" => FALSE, "data" => [] ] , 200 );
		}
		$this->response( [ "status" => TRUE, "data" => $address[0] ] , 200 );
	}

	public function fetch_address_by_id_post()
	{
		$userId = $this->post('user_id');
		$addressId = $this->post('address_id');

		$address = $this->AddressModel->getAddressById( (int)$userId, (int)$addressId );
		if( count( $address ) == 0 )
		{
			$this->response( [ "status" => FALSE, "data" => [] ] , 200 );
		}
		$this->response( [ "status" => TRUE, "data" => $address[0] ] , 200 );
	}

	public function add_or_edit_address_post()
	{
//		sleep(2);
		$userId = $this->post('user_id');
		$addressDetails = $this->post('address_details');

		if( $addressDetails['id'] > 0 )
		{
			$this->AddressModel->updateAddress( (int)$userId, $addressDetails );
			$this->response( [ "status" => TRUE ] , 200 );
		}
		elseif( $addressDetails['id'] == 0 )
		{
			$this->AddressModel->insertAddress( (int)$userId, $addressDetails );
			$this->response( [ "status" => TRUE ] , 200 );
		}

	}

	public function delete_address_post()
	{
//		sleep(2);
		$userId = $this->post('user_id');
		$addressId = $this->post('address_id');

		if( $addressId > 0 )
		{
			$this->AddressModel->deleteAddress( (int)$userId, $addressId );
			$this->response( [ "status" => TRUE ] , 200 );
		}
		else
		{
			$this->response( [ "status" => false ] );
		}

	}

	public function set_selected_address_post()
	{
//		sleep(2);
		$userId = $this->post('user_id');
		$addressId = $this->post('address_id');

		if( $addressId > 0 )
		{
			$updated = $this->AddressModel->setSelectedAddress( (int)$userId, $addressId );
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
