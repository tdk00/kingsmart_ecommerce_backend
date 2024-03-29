<?php
class Admin_order_model extends CI_Model
{
	public function __construct()
	{
		$this->load->database();
	}

	public function getAllOrders()
	{
		$query = $this->db->query('SELECT `order`.*, `user`.`mobile` as `user_phone` FROM `order` JOIN user ON `user`.`id` = `order`.`userId` ORDER BY id desc');
		return $query->result_array();
	}

	public function getOrdersByStatus( $status = 0 )
	{
		$status = $this->db->escape($status);
		$query = $this->db->query('SELECT `order`.*, `user`.`mobile` as `user_phone` FROM `order` JOIN user ON `user`.`id` = `order`.`userId` WHERE `order`.`status` = '.$status .' ORDER BY id desc');
		return $query->result_array();
	}

	public function updateStatus( $status = 0, $order_id = 0 )
	{
		$data = [
			"status" => $status
		];


		$this->db->where('id', $order_id );
		$this->db->update('order', $data);
		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE)
		{
			return false;
		}
		return true;
	}

	public function getOrderDetails( $orderId = 0 ){
		$orderId = $this->db->escape($orderId);
		$query = $this->db->query('SELECT `order`.*, `user`.`mobile` as `user_phone`, concat(`user`.`firstName`, `user`.`lastName`) as `user_fullName` FROM `order` JOIN user ON `user`.`id` = `order`.`userId` WHERE `order`.id = '.$orderId);
		return $query->result_array();
	}

	public function getOrderItems($orderId = 0 ){
		$this->db->select('*');
		$this->db->from('order_item');
		$this->db->where('orderId', $orderId);
		$query = $this->db->get();
		return $query->result_array();
	}
}
?>
