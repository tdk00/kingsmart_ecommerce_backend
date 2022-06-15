<?php
class Authentication_model extends CI_Model
{
	public function __construct()
	{
		$this->load->database();
	}

	public function insertUser($mobile = "" )
	{
		$data = [
			"mobile" => $mobile
		];
		$this->db->insert('user', $data );
		$insert_id = $this->db->insert_id();
		$this->createUserShoppingCart( $insert_id );
		return $insert_id;
	}

	private function createUserShoppingCart( $userId ){
		$data = [
			"userId" => $userId
		];
		$this->db->insert('cart', $data );
		return $this->db->insert_id();
	}

	public function insertOtp($otp, $userId, $expired )
	{
		$data = [
			"user_id" => $userId,
			"otp" => $otp,
			"expired" => $expired
		];

		if( ! $this->userShoppingCartExists( $userId ) )
		{
			$this->createUserShoppingCart( $userId );
		}

		$this->db->insert('user_authentication', $data );
		return $this->db->insert_id();
	}

	private function userShoppingCartExists( $userId ){
		$this->db->select('*');
		$this->db->from('cart');
		$this->db->where('userId', $userId);
		$query = $this->db->get();
		$result_array = $query->result_array();

		return count($result_array) > 0 ? true : false;
	}

	public function getUserId( $mobile )
	{
		$this->db->select('*');
		$this->db->from('user');
		$this->db->where('mobile', $mobile);
		$query = $this->db->get();
		$result_array = $query->result_array();

		return count($result_array) > 0 ? $result_array[0]['id'] : 0;
	}

	public function checkOtp($user_id, $otp )
	{
		$this->db->select('*');
		$this->db->from('user_authentication');
		$this->db->where('user_id', $user_id);
		$this->db->where('otp', $otp);
		$this->db->where('expired > ', time() );
		$query = $this->db->get();
		$result_array = $query->result_array();

		return count($result_array) > 0;
	}


	public function deleteUserOldOtp( $user_id = 0 )
	{
		$removed_all = $this->db->delete( 'user_authentication', array('user_id' => $user_id) );

		if( $removed_all )
		{
			return true;
		}

		return false;
	}

}
