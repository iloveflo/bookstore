<?php

namespace App\Controllers;

use App\Models\Product;

use App\SessionGuard as Guard;

class HomeController extends Controller
{
	// public function __construct()
	// {
	// 	if (!Guard::isUserLoggedIn()) {
	// 		redirect('/login');
	// 	}

	// 	parent::__construct();
	// }

	public function index()
	{
		$this->sendPage('home', ['product' => Product::all(), 'product_sale' => Product::where('khuyen_mai', '>', 0)->get()]);
	}

	public function about()
	{
		$this->sendPage('about/about');
	}

	public function search()
	{
		if(isset($_POST['search']) && $_POST['search'] != ''){
			$this->sendPage('layouts/search',['result'=>Product::where('ten_sach', 'like', '%' . $_POST['search']. '%')->get()]);
		}else {
			redirect('home');
		}
	}
}
