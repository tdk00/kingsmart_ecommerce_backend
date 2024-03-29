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

	private $cart;

	function __construct()
	{
		parent::__construct();
		$this->load->model('main/shopping_cart_model', "ShoppingCartModel");
		$this->load->model('product/product_model', "ProductModel");
		$this->load->library('form_validation');
		$this->load->library('shoppingCartLibrary');
		$this->cart = new shoppingCartLibrary();

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
	public function get_cart_post()
	{
		$this->cart->setUserId( $this->userId )->setCartId()->setItems()->setTotal();
		$cartItems = $this->cart->getItems();
		$cartId = $this->cart->getCartId();
		$cartTotal = $this->cart->getTotal();
		sleep(2);

		if( $cartId == 0)
		{
			$this->response( [ "status" => FALSE, "data" => [] ] , 200 );
		}

		$this->response( [ "status" => TRUE, "data" => [ "cartId" => $cartId, "items" => $cartItems, "cartTotal" => $cartTotal ] ] , 200 );
	}

	public function update_cart_post()
	{
		$items = $this->post( 'items' );

		$this->cart->setUserId( $this->userId )->setCartId()->updateCart( $items );

		$this->response( [ "status" => TRUE ] , 200 );
	}

}
