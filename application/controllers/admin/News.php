<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Slider
 * @property  admin_news_model $AdminNewsModel
 */
class News extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->model('admin/news/admin_news_model', 'AdminNewsModel' );
		$this->load->library('session');
		$this->load->helper('url');
		if ( !$this->session->userdata('logged_in'))
		{
			redirect('/admin/login');
		}

		$config['upload_path']          = './assets/images/news/';
		$config['allowed_types']        = 'jpg|png|jpeg';
		$config['max_size']             = 10000;
		$config['max_width']            = 20000;
		$config['max_height']           = 15000;
		$config['encrypt_name'] = true;

		$this->load->library('upload', $config);
	}

	public function index()
	{
		$news = $this->AdminNewsModel->getAllNews();
		$this->load->view( 'admin/news/news_list', [ 'news' => $news ] );
	}

	public function add_new()
	{
		$this->load->view( 'admin/news/add_new');
	}

	public function edit( $id = 0 )
	{
		$newsData = $this->AdminNewsModel->getNewsById( $id );

		if( count( $newsData ) > 0 )
		{
			$this->load->view(
				'admin/news/edit',
				[
					'newsData' => $newsData[0]
				]
			);
		}
		else
		{
			redirect("admin/news", 'refresh');
		}
	}

	public function insert()
	{

			if ( ! $this->upload->do_upload('news_image'))
			{
				$error = array('error' => $this->upload->display_errors());
				echo "Şəklin yüklənməsində səhv yarandı, zəhmət olmasa yenidən yoxlayın <br> <br>";
				echo($error['error']);
			}
			else
			{
				$data = array('upload_data' => $this->upload->data());
				$file_name = ! empty( $data['upload_data']['file_name'] ) ? $data['upload_data']['file_name'] : "";

				$this->AdminNewsModel->insertNews(
					"assets/images/news/".$file_name
				);
				redirect("admin/news", 'refresh');

			}

	}

	public function update( $id = 0 )
	{
			if ( ! $this->upload->do_upload('news_image'))
			{
				$file_name = "";
			}
			else
			{
				$data = array('upload_data' => $this->upload->data());
				$file_name = ! empty( $data['upload_data']['file_name'] ) ? "assets/images/news/".$data['upload_data']['file_name'] : "";
			}

			$this->AdminNewsModel->updateNews(
				$id,
				$file_name
			);
			redirect("admin/news", 'refresh');

	}

	public function delete ( $id = 0 )
	{
		$removed = $this->AdminNewsModel->deleteNews( $id );

		if( $removed )
		{
			redirect("admin/news", 'refresh');
		}

	}



}
