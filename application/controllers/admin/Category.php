<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Slider
 * @property  admin_category_model $AdminCategoryModel
 */
class Category extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->model('admin/category/admin_category_model', 'AdminCategoryModel');
		$this->load->library('session');
		$this->load->helper('url');
		if (!$this->session->userdata('logged_in')) {
			redirect('/admin/login');
		}

		$config['upload_path'] = './assets/images/categories/';
		$config['allowed_types'] = 'jpg|gif|png|jpeg|JPG|PNG';
		$config['max_size'] = 100000;
		$config['max_width'] = 200000;
		$config['max_height'] = 150000;
		$config['encrypt_name'] = true;

		$this->load->library('upload', $config);
	}
	public function index()
	{
		$categories = $this->AdminCategoryModel->getAllCategories();
		$this->load->view('admin/category/category_list', ['categories' => $categories]);
	}

	public function search( $categoryId )
	{
		$keyword = $this->input->post('keyword');
		$products = $this->AdminCategoryModel->searchProduct( $keyword, $categoryId );
		$this->load->view('admin/category/search_result', ['products' => $products]);
	}

	public function addProductToCategory( $categoryId )
	{
		$productId = $this->input->post('productId');
		$insertId = $this->AdminCategoryModel->addProductToCategory( $productId, $categoryId );
		echo $insertId;
	}

	public function deleteProductFromCategory( $categoryId )
	{
		$productId = $this->input->post('productId');
		$this->AdminCategoryModel->deleteProductFromCategory( $productId, $categoryId );
	}



	public function add_new()
	{
		$this->load->view('admin/category/add_new');
	}

	public function edit($id = 0)
	{
		$categoryData = $this->AdminCategoryModel->getCategoryById($id);

		$products = $this->AdminCategoryModel->getAllProducts( $id );


		if ( count($categoryData) > 0 ) {
			$this->load->view(
				'admin/category/edit',
				[
					'categoryData' => $categoryData[0],
					'products' => $products
				]
			);
		} else {
			redirect("admin/category/category_list", 'refresh');
		}
	}

	public function insert(){
		$title = $this->input->post('title');

		if( !empty( $title ))
		{
			if ( ! $this->upload->do_upload('category_image'))
			{
				$error = array('error' => $this->upload->display_errors());
				echo "Şəklin yüklənməsində səhv yarandı, zəhmət olmasa yenidən yoxlayın <br> <br>";
				echo($error['error']);
			}
			else
			{
				$data = array('upload_data' => $this->upload->data());
				$file_name = ! empty( $data['upload_data']['file_name'] ) ? $data['upload_data']['file_name'] : "";

				$this->AdminCategoryModel->insertCategory(
					$title,
					"assets/images/categories/".$file_name
				);
				redirect("admin/category", 'refresh');

			}
		}
		else
		{
			echo "Bütün xanaları doldurun ";
		}
	}

	public function update( $id = 0 ) {
		$title = $this->input->post('title');

		if(!empty( $title ))
		{
			if ( ! $this->upload->do_upload('category_image'))
			{
				$file_name = "";
			}
			else
			{
				$data = array('upload_data' => $this->upload->data());
				$file_name = ! empty( $data['upload_data']['file_name'] ) ? "assets/images/categories/".$data['upload_data']['file_name'] : "";
			}
			$this->AdminCategoryModel->updateCategory(
				$id,
				$title,
				$file_name
			);
			redirect("admin/category", 'refresh');
		}
		else
		{
			echo "Bütün xanaları doldurun ";
		}
	}

	public function delete( $id ){
		$removed = $this->AdminCategoryModel->deleteCategory( $id );

		if( $removed )
		{
			redirect("admin/category", 'refresh');
		}
	}
}
