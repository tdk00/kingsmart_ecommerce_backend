<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Slider
 * @property  admin_product_model $AdminProductModel
 */
class Product extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->model('admin/product/admin_product_model', 'AdminProductModel');
		$this->load->library('session');
		$this->load->helper('url');
		if (!$this->session->userdata('logged_in')) {
			redirect('/admin/login');
		}

		$config['upload_path'] = './assets/images/products/';
		$config['allowed_types'] = 'jpg|gif|png|jpeg|JPG|PNG';
		$config['max_size'] = 100000;
		$config['max_width'] = 200000;
		$config['max_height'] = 150000;
		$config['encrypt_name'] = true;

		$this->load->library('upload', $config);
	}

	public function index()
	{
		$products = $this->AdminProductModel->getAllProducts();
		$this->load->view('admin/product/product_list', ['products' => $products]);
	}

	public function add_new()
	{
		$this->load->view('admin/product/add_new');
	}

	public function edit($id = 0)
	{
		$productData = $this->AdminProductModel->getProductById($id);

		if ( count($productData) > 0 ) {
			$this->load->view(
				'admin/product/edit',
				[
					'productData' => $productData[0]
				]
			);
		} else {
			redirect("admin/product/product_list", 'refresh');
		}
	}

	public function insert(){
		$barkod = $this->input->post('barkod');
		$title = $this->input->post('title');
		$summary = $this->input->post('summary');
		$price =  $this->input->post('price');
		$oldPrice = $this->input->post('old_price');
		$onlineMarket = $this->input->post('online_market') == "on";

		if( !empty( $barkod ) && !empty( $title )  && !empty( $price ) )
		{
			if ( ! $this->upload->do_upload('product_image'))
			{
				$error = array('error' => $this->upload->display_errors());
				echo "Şəklin yüklənməsində səhv yarandı, zəhmət olmasa yenidən yoxlayın <br> <br>";
				echo($error['error']);
			}
			else
			{
				$data = array('upload_data' => $this->upload->data());
				$file_name = ! empty( $data['upload_data']['file_name'] ) ? $data['upload_data']['file_name'] : "";

				$this->AdminProductModel->insertProduct(
					$barkod,
					$title,
					$summary,
					"assets/images/products/".$file_name,
					$price,
					$oldPrice,
					$onlineMarket
				);
				redirect("admin/product", 'refresh');

			}
		}
		else
		{
			echo "Bütün xanaları doldurun ";
		}
	}

	public function update( $id = 0 ) {
		$barkod = $this->input->post('barkod');
		$title = $this->input->post('title');
		$summary = $this->input->post('summary');
		$price =  $this->input->post('price');
		$oldPrice = $this->input->post('old_price');
		$onlineMarket = $this->input->post('online_market') == "on";

		if( !empty( $barkod ) && !empty( $title )  && !empty( $price ) )
		{
			if ( ! $this->upload->do_upload('product_image'))
			{
				$file_name = "";
			}
			else
			{
				$data = array('upload_data' => $this->upload->data());
				$file_name = ! empty( $data['upload_data']['file_name'] ) ? "assets/images/products/".$data['upload_data']['file_name'] : "";
			}
			$this->AdminProductModel->updateProduct(
				$id,
				$barkod,
				$title,
				$summary,
				$file_name,
				$price,
				$oldPrice,
				$onlineMarket
			);
			redirect("admin/product", 'refresh');
		}
		else
		{
			echo "Bütün xanaları doldurun ";
		}
	}

	public function delete( $id ){
		$removed = $this->AdminProductModel->deleteProduct( $id );

		if( $removed )
		{
			redirect("admin/product", 'refresh');
		}
	}
}
