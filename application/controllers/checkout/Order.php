<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;
/**
 * Class Order
 * @property  order_model $OrderModel
 * @property  product_model $ProductModel
 * @property  shopping_cart_model $ShoppingCartModel
 * @property  address_model $AddressModel
 * @property $form_validation
 */
class Order extends RestController
{

	function __construct()
	{
		parent::__construct();
		$this->load->model('checkout/order_model', "OrderModel");
		$this->load->model('product/product_model', "ProductModel");
		$this->load->model('main/shopping_cart_model', "ShoppingCartModel");
		$this->load->model('account/address_model', "AddressModel");
		$this->load->library('form_validation');
	}

	public function add_order_post()
	{
		$userId = $this->post( 'user_id' );


		if( !empty( $userId ) && (int)$userId > 0  )
		{

			$address = $this->AddressModel->getSelectedAddress( (int)$userId );

			if( count( $address ) == 0 )
			{
				$this->response( [ "status" => FALSE, "error_message" => 'Seçili ünvan yoxdur' ] , 200 );
			}

			$cartId = $this->ShoppingCartModel->getCartId( $userId );
			$items = $this->ShoppingCartModel->getShoppingCartItems( $cartId );

			if( count( $items ) == 0 )
			{
				$this->response( [ "status" => FALSE, "error_message" => 'Alış veriş səbəti boşdur' ] , 200 );
			}

			$items = $this->setItemDetails( $items, $userId );

			$total = $this->getTotal( $items );

			$orderNumber = $userId . time();
			$orderData = [
				'userId' => $userId,
				'orderNumber' => $orderNumber,
				'total' => $total,
				'city' => "Bakı",
				'address' => $address[0]['content'],
				'postal' => $address[0]['postal'],
				'note' => $address[0]['note'],
			];
			$orderId = $this->OrderModel->insertOrder( $orderData );
			if( $orderId > 0) {
				foreach ( $items as $itemKey => $itemValue )
				{
					$productDetails =  $items[$itemKey]['details'];
					if( count( $productDetails ) > 0 )
					{
						$orderItem =
							[
								'productId' => $itemValue['productId'],
								'orderId' => $orderId,
								'quantity' => $itemValue['quantity'],
								'price' => $productDetails['price'],
								'oldPrice' => $productDetails['oldPrice'],

							];
						$this->OrderModel->insertOrderItem( $orderItem );
					}
				}
				$this->ShoppingCartModel->clearCart( $cartId );
				$this->response( [ "status" => TRUE] , 200 );
			}
		}
		$this->response( [ "status" => FALSE, "error_message" => 'Səhv yarandı, yenidən yoxlayın' ] , 200 );

	}

	public function fetch_last_order_post(){
		$userId = $this->post( 'user_id' );
		$lastOrder = $this->OrderModel->getLastOrder( $userId );
		if( count( $lastOrder ) )
		{
			$this->response( [ "status" => TRUE, "data" => $lastOrder[0] ] , 200 );
		}
		$this->response( [ "status" => FALSE, "data" => [] ] , 200 );
	}

	private function setItemDetails( $items, $userId ){
		foreach ( $items as $itemKey => $itemValue )
		{
			$items[$itemKey]['details'] = $this->ProductModel->getProductById( (int)$itemValue['productId'], (int)$userId );
		}
		return $items;
	}

	public function getTotal( $items ){
		$total = 0;
		foreach  ( $items as $item ) {
			$total += ( $item['details']['price'] * $item['quantity'] * 100 );
		}
		return  $total / 100;
	}
}
