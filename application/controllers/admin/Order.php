<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Slider
 * @property  admin_order_model $AdminOrderModel
 */
class Order extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('admin/order/admin_order_model', 'AdminOrderModel');
		$this->load->library('session');
		$this->load->helper('url');
		if (!$this->session->userdata('logged_in')) {
			redirect('/admin/login');
		}
	}
	public function index( $status = 0 ){
		if( $status == 4 )
		{
			$orders = $this->AdminOrderModel->getAllOrders();
		}
		else
		{
			$orders = $this->AdminOrderModel->getOrdersByStatus( $status );
		}
		$this->load->view('admin/orders/orders_list', [ 'orders' => $orders, 'status' => $status ]);
	}

	public function change_status( $status = 0, $order_id = 0 )
	{
		$allowed_statuses = [-1,0,1,2,3];
		if( in_array( $status, $allowed_statuses ) )
		{
			$this->AdminOrderModel->updateStatus( $status, $order_id );
			redirect('admin/order/index/4');
		}
		else
		{
			redirect('admin/order/index/4');
		}
	}
	public function order_details( $orderId = 0 ){
		$orderDetails = $this->AdminOrderModel->getOrderDetails( $orderId );
		$orderProducts = $this->AdminOrderModel->getOrderProducts( $orderId );
		if( count($orderDetails) > 0 ){
			$this->load->view('admin/orders/order_details2', [ 'order_details' => $orderDetails[0], 'order_products' => $orderProducts ]);
		}

	}


}
