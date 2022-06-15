<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;
/**
 * Class News
 * @property  news_model $NewsModel
 * @property $form_validation
 */
class News extends RestController
{

	function __construct()
	{
		parent::__construct();
		$this->load->model('main/news_model', "NewsModel");
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

	public function fetch_news_by_id_post()
	{
		$newsId = $this->post('news_id');
		if( empty( $newsId ) || ! (int)$newsId > 0 )
		{
			$this->response( [ "status" => FALSE, "data" => [] ] , 200 );
		}

		$news = $this->NewsModel->getNewsById( (int)$newsId );
		if( count( $news ) == 0 )
		{
			$this->response( [ "status" => FALSE, "data" => [] ] , 200 );
		}
		$this->response( [ "status" => TRUE, "data" => $news[0] ] , 200 );
	}
}
