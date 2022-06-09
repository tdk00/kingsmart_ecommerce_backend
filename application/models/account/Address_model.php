<?php
class Address_model extends CI_Model
{
	public function __construct()
	{
		$this->load->database();
	}

	public function getAddresses( $userId = 0 )
	{
		$this->db->select("*");
		$this->db->from("address");
		$this->db->where("userId", $userId);
		$query = $this->db->get();
		return $query->result_array();
	}

	public function getSelectedAddress( $userId = 0 )
	{
		$this->db->select("*");
		$this->db->from("address");
		$this->db->where("userId", $userId);
		$this->db->where("selected", 1);
		$query = $this->db->get();
		return $query->result_array();
	}

	public function getAddressById( $userId = 0, $addressId = 0 )
	{
		$this->db->select("*");
		$this->db->from("address");
		$this->db->where("userId", $userId);
		$this->db->where("id", $addressId);
		$query = $this->db->get();
		return $query->result_array();
	}

	public function updateAddress( $userId , $addressDetails  )
	{
		$this->resetDefaultAddresses($userId);

		$this->db->where('userId', $userId);
		$this->db->where('id', $addressDetails['id']);
		unset($addressDetails['id']);
		$this->db->update('address', $addressDetails);
		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE)
		{
			return false;
		}
		return true;
	}
	public function insertAddress( $userId, $addressDetails )
	{
		$this->resetDefaultAddresses($userId);
		unset($addressDetails['id']);
		$addressDetails['selected'] = 1;
		$addressDetails['userId'] = $userId;
		$this->db->insert('address', $addressDetails );
		return $this->db->insert_id();
	}

	public function resetDefaultAddresses( $userId = 0 )
	{
		$this->db->where('userId', $userId);
		$this->db->update('address', [ 'selected' => 0 ]);
		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE)
		{
			return false;
		}
		return true;
	}

	public function deleteAddress( $userId = 0, $addressId )
	{
		$this->db->where('id', $addressId);
		$this->db->where('userId', $userId);
		$this->db->delete('address');
		return true;
	}

	public function setSelectedAddress( $userId = 0, $addressId = 0 )
	{

		$this->resetDefaultAddresses( $userId );

		$this->db->where('userId', $userId);
		$this->db->where('id', $addressId);
		$this->db->update('address', [ 'selected' => 1 ]);
		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE)
		{
			return false;
		}
		return true;
	}

}
