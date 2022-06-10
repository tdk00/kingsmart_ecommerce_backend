<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Class ShoppingCartLibrary
 * @property  shopping_cart_model $ShoppingCartModel
 * @property  product_model $ProductModel
 */
class ShoppingCartLibrary {

	private $CI;
	private $total;
	private $items;
	private $cartId;
	private $userId;


	public function __construct()
	{
		$this->CI =& get_instance();
		$this->CI->load->model('main/shopping_cart_model', "ShoppingCartModel");
		$this->CI->load->model('product/product_model', "ProductModel");
	}

	/**
	 * @param mixed $userId
	 */
	public function setUserId( $userId )
	{
		$this->userId = $userId;
		return $this;
	}

	public function setCartId()
	{
		$this->cartId = $this->CI->ShoppingCartModel->getCartId( $this->userId );
		return $this;
	}

	public function setItems()
	{
		$this->items = $this->CI->ShoppingCartModel->getShoppingCartItems( $this->cartId );
		$this->setItemDetails();
		return $this;
	}

	private function setItemDetails(){
		foreach ( $this->items as $itemKey => $itemValue )
		{
			$this->items[$itemKey]['details'] = $this->CI->ProductModel->getProductById( (int)$itemValue['productId'], (int)$this->userId );
		}
	}

	public function setTotal(){
		foreach  ( $this->items as $item ) {
			$this->total += ( $item['details']['price'] * $item['quantity'] * 100 );
		}
		$this->total = $this->total / 100;
	}

	public function updateCart( $items  ){
		if( is_array( $items ) ) {

			$this->CI->ShoppingCartModel->clearCart( $this->cartId );

			foreach ( $items as $productId => $quantity ){

				$insertData = [ 'cartId' => $this->cartId, 'productId' => $productId, "quantity"=> $quantity ];
				$this->CI->ShoppingCartModel->insertItem( $insertData );
			}

		}
	}



	/**
	 * @return mixed
	 */
	public function getItems()
	{
		return $this->items;
	}

	/**
	 * @return int|mixed
	 */
	public function getCartId()
	{
		return $this->cartId;
	}



	/**
	 * @return float
	 */
	public function getTotal()
	{
		return $this->total;
	}




}
