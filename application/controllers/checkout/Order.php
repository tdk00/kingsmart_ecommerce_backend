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

	public function add_order_post()
	{
		if( !empty( $this->userId ) && $this->userId > 0  )
		{

			$address = $this->AddressModel->getSelectedAddress( $this->userId );

			if( count( $address ) == 0 )
			{
				$this->response( [ "status" => FALSE, "error_message" => 'Seçili ünvan yoxdur' ] , 200 );
			}

			$cartId = $this->ShoppingCartModel->getCartId( $this->userId );
			$items = $this->ShoppingCartModel->getShoppingCartItems( $cartId );

			if( count( $items ) == 0 )
			{
				$this->response( [ "status" => FALSE, "error_message" => 'Alış veriş səbəti boşdur' ] , 200 );
			}

			$items = $this->setItemDetails( $items );

			$total = $this->getTotal( $items );

			$orderNumber = $this->userId . time();
			$orderData = [
				'userId' => $this->userId,
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

		if( empty( $this->userId ) || ! ( $this->userId > 0 )  )
		{
			$this->response( [ "status" => FALSE, "data" => [] ] , 200 );
		}
		$lastOrder = $this->OrderModel->getLastOrder( $this->userId );
		if( count( $lastOrder ) )
		{
			switch ( $lastOrder[0]['status'] ) {
				case 1 :
					$lastOrder[0]['status'] = 'Hazırdır';
					break;
				case 2 :
					$lastOrder[0]['status'] = 'Təhvil verildi';
					break;
				default :
					$lastOrder[0]['status'] = 'Hazırlanır';
					break;
			}
				$this->response( [ "status" => TRUE, "data" => $lastOrder[0] ] , 200 );
		}
		$this->response( [ "status" => FALSE, "data" => [] ] , 200 );
	}

	public function fetch_order_by_id_post(){

		$orderId = $this->post( 'order_id' );
		if( empty( $this->userId ) || ! ( $this->userId > 0 ) || empty( $orderId ) || ! ( (int)$orderId > 0 )  )
		{
			$this->response( [ "status" => FALSE, "data" => [] ] , 200 );
		}
		$orderDetails = $this->OrderModel->getOrderById( $this->userId, $orderId );
		if( count( $orderDetails ) > 0 )
		{
			switch ( $orderDetails[0]['status'] ) {
				case 1 :
					$orderDetails[0]['status'] = 'Hazırdır';
					break;
				case 2 :
					$orderDetails[0]['status'] = 'Təhvil verildi';
					break;
				default :
					$orderDetails[0]['status'] = 'Hazırlanır';
					break;
			}

			$items = $this->OrderModel->getOrderItems( $this->userId, $orderId );

			$items = $this->setOrderItemDetails( $items );


			$this->response( [ "status" => TRUE, "order_details" => $orderDetails[0], "items" => $items ] , 200 );
		}
		$this->response( [ "status" => FALSE, "data" => [] ] , 200 );
	}

	public function fetch_orders_by_date_range_post(){

		$dateFrom = $this->post( 'date_from' );
		$dateTo = $this->post( 'date_to' );
		if( empty( $this->userId ) || ! ( $this->userId > 0 ) || empty( $dateFrom ) || empty( $dateTo ) )
		{
			$this->response( [ "status" => FALSE, "data" => [] ] , 200 );
		}
		$orders = $this->OrderModel->getOrdersByDateRange( $this->userId, $dateFrom, $dateTo );
		if( count( $orders ) )
		{
			foreach ( $orders as $orderKey => $orderValue )
			{
				$orders[$orderKey]['createdAt'] = substr($orderValue['createdAt'],0, 10);
				switch ( $orderValue['status'] ) {
					case 1 :
						$orders[$orderKey]['status'] = 'Hazırdır';
						break;
					case 2 :
						$orders[$orderKey]['status'] = 'Təhvil verildi';
						break;
					default :
						$orders[$orderKey]['status'] = 'Hazırlanır';
						break;
				}
			}
			$this->response( [ "status" => TRUE, "data" => $orders ] , 200 );
		}
		$this->response( [ "status" => FALSE, "data" => [] ] , 200 );
	}

	private function setItemDetails( $items ){
		foreach ( $items as $itemKey => $itemValue )
		{
			$items[$itemKey]['details'] = $this->ProductModel->getProductById( (int)$itemValue['productId'], $this->userId );
		}
		return $items;
	}

	private function setOrderItemDetails( $items ){
		foreach ( $items as $itemKey => $itemValue )
		{
			$productDetails = $this->ProductModel->getProductById( (int)$itemValue['productId'], $this->userId );
			if( count($productDetails) > 0 ){
				$items[$itemKey]['name'] = $productDetails['title'];
				$items[$itemKey]['image'] = $productDetails['image'];
			}
			else{
				$items[$itemKey]['name'] = '';
				$items[$itemKey]['image'] = '';
			}
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
