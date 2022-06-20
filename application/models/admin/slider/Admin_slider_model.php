<?php
class Admin_slider_model extends CI_Model
{
	public function __construct()
	{
		$this->load->database();
	}

	public function getAllSliders()
	{
		$this->db->select('*');
		$this->db->from('reference_sliders');
		$this->db->order_by('id', 'desc');
		$query = $this->db->get();
		return $query->result_array();
	}

	public function getSliderById( $id = 0 )
	{
		$this->db->select('*');
		$this->db->where( 'id', $id );
		$this->db->from('reference_sliders');
		$query = $this->db->get();
		return $query->result_array();
	}

	public function insertSlider( $title, $summary, $image, $newsId )
	{
		$data = [
			"title" => $title,
			"summary" => $summary,
			"icon" => $image,
			"newsId" => $newsId
		];
		$this->db->insert('reference_sliders', $data );
		return $this->db->insert_id();
	}

	public function updateSlider( $sliderId = 0, $title = "", $summary = "", $image = "", $newsId = 0 )
	{
		$data = [
			"title" => $title,
			"summary" => $summary,
			"newsId" => $newsId
		];

		if( ! empty( $image ) )
		{
			$data['icon'] = $image;
		}

		$this->db->where('id', $sliderId );
		$this->db->update('reference_sliders', $data);
		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE)
		{
			return false;
		}
		return true;
	}

	public function deleteSlider( $sliderId = 0 )
	{
		$removed = $this->db->delete( 'reference_sliders', array('id' => $sliderId ) );

		if( $removed )
		{
			return true;
		}

		return false;

	}
}
?>
