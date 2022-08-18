<?php
class Admin_order_model extends CI_Model
{
	public function __construct()
	{
		$this->load->database();
	}

	public function getAllOrders()
	{
		$query = $this->db->query('SELECT `order`.*, `user`.`mobile` as `user_phone` FROM `order` JOIN user ON `user`.`id` = `order`.`userId`');
		return $query->result_array();
	}

	public function getOrdersByStatus( $status = 0 )
	{
		$query = $this->db->query('SELECT `order`.*, `user`.`mobile` as `user_phone` FROM `order` JOIN user ON `user`.`id` = `order`.`userId` WHERE `order`.status = '.$status);
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
}
?>
