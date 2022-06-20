<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Slider
 * @property  admin_slider_model $AdminSliderModel
 * @property  admin_news_model $AdminNewsModel
 */
class Slider extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->model('admin/slider/admin_slider_model', 'AdminSliderModel' );
		$this->load->model('admin/news/admin_news_model', 'AdminNewsModel' );
		$this->load->library('session');
		$this->load->helper('url');
		if ( !$this->session->userdata('logged_in'))
		{
			redirect('/admin/login');
		}

		$config['upload_path']          = './assets/images/main/';
		$config['allowed_types']        = 'jpg|png|jpeg';
		$config['max_size']             = 10000;
		$config['max_width']            = 20000;
		$config['max_height']           = 15000;
		$config['encrypt_name'] = true;

		$this->load->library('upload', $config);
	}

	public function index()
	{
		$sliders = $this->AdminSliderModel->getAllSliders();
		$this->load->view( 'admin/slider/slider_list', [ 'sliders' => $sliders ] );
	}

	public function add_new()
	{
		$news = $this->AdminNewsModel->getAllNews();
		$this->load->view( 'admin/slider/add_new', [ 'news' => $news ]);
	}

	public function edit( $id = 0 )
	{
		$news = $this->AdminNewsModel->getAllNews();
		$sliderData = $this->AdminSliderModel->getSliderById( $id );

		if( count( $sliderData ) > 0 )
		{
			$this->load->view(
				'admin/slider/edit',
				[
					'news' => $news,
					'sliderData' => $sliderData
				]
			);
		}
		else
		{
			redirect("admin/slider", 'refresh');
		}
	}

	public function insert()
	{
		$title = $this->input->post('title');
		$summary = $this->input->post('summary');
		$slider_news_id = $this->input->post('slider_news_id');



		if( !empty( $title ) && !empty( $summary ) && !empty( $slider_news_id ) )
		{
			if ( ! $this->upload->do_upload('slider_image'))
			{
				$error = array('error' => $this->upload->display_errors());
				echo "Şəklin yüklənməsində səhv yarandı, zəhmət olmasa yenidən yoxlayın <br> <br>";
				echo($error['error']);
			}
			else
			{
				$data = array('upload_data' => $this->upload->data());
				$file_name = ! empty( $data['upload_data']['file_name'] ) ? $data['upload_data']['file_name'] : "";

				$this->AdminSliderModel->insertSlider(
						$title,
						$summary,
						"assets/images/main/".$file_name,
						$slider_news_id
					);
				redirect("admin/slider", 'refresh');

			}
		}
		else
		{
			echo "Slider başlığı və məlumat xanaları boş qoyula bilməz ";
		}

	}

	public function update( $id = 0 )
	{
		$title = $this->input->post('title');
		$summary = $this->input->post('summary');
		$slider_news_id = $this->input->post('slider_news_id');


		if(  !empty( $title ) && !empty( $summary ) && !empty( $slider_news_id ) )
		{
			if ( ! $this->upload->do_upload('slider_image'))
			{
				$file_name = "";
			}
			else
			{
				$data = array('upload_data' => $this->upload->data());
				$file_name = ! empty( $data['upload_data']['file_name'] ) ? "assets/images/main/".$data['upload_data']['file_name'] : "";
			}

				$this->AdminSliderModel->updateSlider(
					$id,
					$title,
					$summary,
					$file_name,
					$slider_news_id
				);
			redirect("admin/slider", 'refresh');
		}
		else
		{
			echo "Slider başlığı və məlumat xanaları boş qoyula bilməz ";
		}

	}

	public function delete ( $id = 0 )
	{
		$removed = $this->AdminSliderModel->deleteSlider( $id );

		if( $removed )
		{
			redirect("admin/slider", 'refresh');
		}

	}



}
