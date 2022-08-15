<?php
class Admin_news_model extends CI_Model
{
	public function __construct()
	{
		$this->load->database();
	}

	public function getAllNews()
	{
		$this->db->select('*');
		$this->db->from('news');
		$this->db->order_by('id', 'desc');
		$query = $this->db->get();
		return $query->result_array();
	}

	public function getNewsById( $id = 0 )
	{
		$this->db->select('*');
		$this->db->where( 'id', $id );
		$this->db->from('news');
		$query = $this->db->get();
		return $query->result_array();
	}

	public function insertNews( $title, $content, $image )
	{
		$data = [
			"title" => $title,
			"content" => $content,
			"image" => $image
		];
		$this->db->insert('news', $data );
		return $this->db->insert_id();
	}

	public function updateNews( $newsId, $title, $content, $image )
	{
		$data = [
			"title" => $title,
			"content" => $content,
		];

		if( ! empty( $image ) )
		{
			$data['image'] = $image;
		}

		$this->db->where('id', $newsId );
		$this->db->update('news', $data);
		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE)
		{
			return false;
		}
		return true;
	}

	public function deleteNews( $newsId = 0 )
	{
		$removed = $this->db->delete( 'news', array('id' => $newsId ) );

		if( $removed )
		{
			return true;
		}

		return false;

	}
}
?>
