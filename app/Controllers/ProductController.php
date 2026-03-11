<?php

namespace App\Controllers;

use App\Models\Product;
use App\Models\TacGia;
use App\Models\NhaXuatBan;
use App\Controllers\Controller;

class ProductController extends Controller
{

	public function product()
	{
		$_SESSION['menu'] = 0;
		$this->sendPage('products/product_all', ['products' => Product::all()]);
	}
	
	public function productOfType()
	{
		$_SESSION['menu'] = 0;
		if (isset($_POST['all'])) {
			$this->sendPage('products/product_all', ['products' => Product::all()]);
		} else if (isset($_POST['sale'])) {
			$this->sendPage('products/product_all', ['products' => Product::where('khuyen_mai', '>', 0)->get()]);
		} else if (isset($_POST['filter'])) {
			$sort = $_POST['sort'];
			$selected = $_POST['select'];
			if ($selected=='sgk') {
				$ma_lsp = 'B01';
			} else if ($selected=='truyentranh') {
				$ma_lsp = 'B03';
			} else if ($selected=='kynang') {
				$ma_lsp = 'B04';
			} else if ($selected=='tieuthuyet') {
				$ma_lsp = 'B02';
			} else if ($selected=='sale') {
				$ma_lsp = 'sale';
			} else if ($selected=='all') {
				$ma_lsp = 'all';
			}
			if ($sort == 1 && $ma_lsp == 'sale') {
				$_SESSION['menu'] = 'sale';
				$this->sendPage('products/product_all', ['products' => Product::where('khuyen_mai', '>', 0)->orderBy('gia_khuyen_mai', 'ASC')->get()]);
			} else if ($sort == 1 && $ma_lsp == 'all') {
				$_SESSION['menu'] = 'all';
				$this->sendPage('products/product_all', ['products' => Product::orderBy('gia_khuyen_mai', 'ASC')->get()]);
			}else if ($sort == 1 && $ma_lsp!='sale' && $ma_lsp!='all') {
				$_SESSION['menu'] = $selected;
				$this->sendPage('products/product_all', ['products' => Product::where('ma_loai_sach', $ma_lsp)->orderBy('gia_khuyen_mai', 'ASC')->get()]);
			}else if ($sort == 2 && $ma_lsp == 'all') {
				$_SESSION['menu'] = $selected;
				$this->sendPage('products/product_all', ['products' => Product::orderBy('gia_khuyen_mai', 'DESC')->get()]);
			}else if ($sort == 2 && $ma_lsp == 'sale') {
				$_SESSION['menu'] = $selected;
				$this->sendPage('products/product_all', ['products' => Product::where('khuyen_mai', '>', 0)->orderBy('gia_khuyen_mai', 'DESC')->get()]);
			}else if ($sort == 2 && $ma_lsp!='sale' && $ma_lsp!='all') {
				$_SESSION['menu'] = $selected;
				$this->sendPage('products/product_all', ['products' => Product::where('ma_loai_sach', $ma_lsp)->orderBy('gia_khuyen_mai', 'DESC')->get()]);
			}else if ($sort == 3 && $ma_lsp == 'all') {
				$_SESSION['menu'] = $selected;
				$this->sendPage('products/product_all', ['products' => Product::orderBy('sold', 'DESC')->get()]);
			}else if ($sort == 3 && $ma_lsp == 'sale') {
				$_SESSION['menu'] = $selected;
				$this->sendPage('products/product_all', ['products' => Product::where('khuyen_mai', '>', 0)->orderBy('sold', 'DESC')->get()]);
			}else if ($sort == 3 && $ma_lsp!='sale' && $ma_lsp!='all') {
				$_SESSION['menu'] = $selected;
				$this->sendPage('products/product_all', ['products' => Product::where('ma_loai_sach', $ma_lsp)->orderBy('sold', 'DESC')->get()]);
			}
		} else {
			if (isset($_POST['sgk'])) {
				$ma_lsp = 'B01';
			} else if (isset($_POST['truyentranh'])) {
				$ma_lsp = 'B03';
			} else if (isset($_POST['kynang'])) {
				$ma_lsp = 'B04';
			} else if (isset($_POST['tieuthuyet'])) {
				$ma_lsp = 'B02';
			}
			$_SESSION['menu'] = 0;
			$this->sendPage('products/product_all', ['products' => Product::where('ma_loai_sach', $ma_lsp)->get()]);
		}
	}

	public function detailProduct()
	{
    // Kiểm tra tham số 'masp' có tồn tại và không rỗng
    if (!isset($_GET['masp']) || empty($_GET['masp'])) {
        echo "Không tìm thấy sản phẩm!";
        return;
    }

    $maSanPham = $_GET['masp'];

    // Truy vấn sản phẩm kết hợp với thông tin tác giả và nhà xuất bản
    $product = NhaXuatBan::join('sach', 'sach.ma_nxb', '=', 'nhaxuatban.ma_nxb')
        ->join('tacgia', 'tacgia.ma_tac_gia', '=', 'sach.ma_tac_gia')
        ->where('sach.ma_sach', $maSanPham)
        ->select('sach.*', 'nhaxuatban.ten_nxb', 'tacgia.ten_tac_gia') // Chỉ lấy các cột cần thiết
        ->first();

    // Nếu không tìm thấy sản phẩm, trả về thông báo lỗi
    if (!$product) {
        echo "Sản phẩm không tồn tại!";
        return;
    }

    // Gửi dữ liệu sản phẩm đến view để hiển thị
    $this->sendPage('products/detail', ['product' => $product]);
}
}