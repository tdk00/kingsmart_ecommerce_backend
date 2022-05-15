<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;
/**
 * Class ShoppingCart
 * @property  shopping_cart_model $ShoppingCartModel
 * @property  product_model $ProductModel
 * @property $form_validation
 */
class ShoppingCart extends RestController
{

	function __construct()
	{
		parent::__construct();
		$this->load->model('main/shopping_cart_model', "ShoppingCartModel");
		$this->load->model('product/product_model', "ProductModel");
		$this->load->library('form_validation');
	}
	public function get_cart_post()
	{
		$userId = $this->post( 'user_id' );
		$cart = $this->ShoppingCartModel->getShoppingCart( $userId );
		if( count( $cart ) > 0 )
		{
			$cartItems = $this->ShoppingCartModel->getShoppingCartItems( $cart[0]['id'] );
			foreach ( $cartItems as $itemKey => $itemValue )
			{
				$cartItems[$itemKey]['details'] = $this->ProductModel->getProductById( (int)$itemValue['productId'], (int)$userId );
			}
			$cart[0]['items'] = $cartItems;
		}
		else
		{
			$this->response( [ "status" => FALSE, "data" => [] ] , 200 );
		}

		$this->response( [ "status" => TRUE, "data" => $cart[0] ] , 200 );
	}

	public function update_cart_post()
	{
		var_dump($this->post());
		die();
		$userId = $this->post( 'user_id' );
		$cart = $this->ShoppingCartModel->getShoppingCart( $userId );
		if( count( $cart ) > 0 )
		{
			$cartItems = $this->ShoppingCartModel->getShoppingCartItems( $cart[0]['id'] );
			foreach ( $cartItems as $itemKey => $itemValue )
			{
				$cartItems[$itemKey]['details'] = $this->ProductModel->getProductById( (int)$itemValue['productId'], (int)$userId );
			}
			$cart[0]['items'] = $cartItems;
		}
		else
		{
			$this->response( [ "status" => FALSE, "data" => [] ] , 200 );
		}

		$this->response( [ "status" => TRUE, "data" => $cart[0] ] , 200 );
	}
}
