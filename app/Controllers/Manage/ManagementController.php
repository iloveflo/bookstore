<?php

namespace App\Controllers\Manage;

use App\Controllers\Controller;
use App\SessionGuard as Guard;
use App\Models\Product;
use App\Models\User;
use App\Models\ProductType;
use App\Models\TacGia;
use App\Models\NhaXuatBan;
use App\Models\Bill;
use App\Models\BillDetail;
use App\Models\Cart;
use App\Models\Article;
use Carbon\Carbon;

class ManagementController extends Controller
{
    public function __construct()
    {
        if (!Guard::isUserLoggedIn()) {
            redirect('home');
        }

        parent::__construct();
    }

    /*** MANAGE ALL PRODUCTS ***/
    public function getAllProducts()
    {
        $products_manage = Product::join('loaisach', 'loaisach.ma_loai_sach', '=', 'sach.ma_loai_sach')->orderBy('sach.ma_sach', 'ASC')->get();
        $this->sendPage('manage/manageProduct', [
            'products_manage' => $products_manage
        ]);
    }


    /*** SORT PRODUCT ***/
    public function sortAllProducts()
    {
        if (isset($_POST['sort-price'])) {
            $sort_price = $_POST['sort-price'];
            if ($sort_price == 1) {
                $old_selected = array("val" => "1");
                $this->sendPage('manage/manageProduct', [
                    'products_manage' => Product::join('loaisach', 'loaisach.ma_loai_sach', '=', 'sach.ma_loai_sach')
                        ->orderBy('ma_sach', 'ASC')->get(),
                    'old_selected' => $old_selected
                ]);
            } else if ($sort_price == 2) {
                $old_selected = array("val" => "2");
                $this->sendPage('manage/manageProduct', [
                    'products_manage' => Product::join('loaisach', 'loaisach.ma_loai_sach', '=', 'sach.ma_loai_sach')
                        ->orderBy('gia_khuyen_mai', 'ASC')->get(),
                    'old_selected' => $old_selected
                ]);
            } else if ($sort_price == 3) {
                $old_selected = array("val" => "3");
                $this->sendPage('manage/manageProduct', [
                    'products_manage' => Product::join('loaisach', 'loaisach.ma_loai_sach', '=', 'sach.ma_loai_sach')
                        ->orderBy('gia_khuyen_mai', 'DESC')->get(),
                    'old_selected' => $old_selected
                ]);
            } else if ($sort_price == 4) {
                $old_selected = array("val" => "4");
                $this->sendPage('manage/manageProduct', [
                    'products_manage' => Product::join('loaisach', 'loaisach.ma_loai_sach', '=', 'sach.ma_loai_sach')
                        ->orderBy('sold', 'DESC')->get(),
                    'old_selected' => $old_selected
                ]);
            }
        }
    }

    /*** CREATE NEW PRODUCT ***/
    public function showCreatePage()
    {
        $last_product = $this->createNewProductId();

        $product_type = ProductType::all();
        $tacgia = TacGia::all();
        $nxb = NhaXuatBan::all();

        $this->sendPage('manage/create', [
            'errors' => session_get_once('errors'),
            'old' => $this->getSavedFormValues(),
            'ma_sp' => $last_product,
            'loai_sp' => $product_type,
            'tacgia' => $tacgia,
            'nxb' => $nxb
        ]);
    }

    public function createNewProductId()
    {


        date_default_timezone_set('Asia/Ho_Chi_Minh'); // fix lệch giờ
        $new_product_id = 'S' . date('dmYHis'); // ví dụ: S1206250911
        return ['ma_sach' => $new_product_id];
    }

    public function createProduct()
    {
        // 1. Lấy dữ liệu văn bản từ Form
        $data = $this->filterProductData($_POST);

        // 2. GỌI HÀM UPLOAD VÀ HỨNG KẾT QUẢ
        // Hàm này trả về: ['hinh_anh' => '...', 'anh_1' => '...', 'anh_2' => '...']
        $uploadedImages = $this->uploadImage();

        // 3. Gán tên ảnh vào mảng $data (để chuẩn bị lưu DB)
        if (isset($uploadedImages['hinh_anh'])) {
            $data['hinh_anh'] = $uploadedImages['hinh_anh'];
        }

        if (isset($uploadedImages['anh_1'])) {
            $data['anh_1'] = $uploadedImages['anh_1'];
        }

        if (isset($uploadedImages['anh_2'])) {
            $data['anh_2'] = $uploadedImages['anh_2'];
        }

        // 4. VALIDATE (Quan trọng: Phải chuyển xuống dưới bước gán ảnh)
        // Lúc này $data đã có đủ thông tin text + ảnh thì mới validate chính xác được
        $model_errors = Product::validate($data);

        // 5. Tính toán giá khuyến mãi
        if (isset($data['khuyen_mai']) && $data['khuyen_mai'] >= 0) {
            $data['gia_khuyen_mai'] = $data['gia_sach'] - $data['gia_sach'] * ($data['khuyen_mai'] / 100);
        } else {
            $data['gia_khuyen_mai'] = $data['gia_sach'];
        }

        // 6. Ngày tạo
        // Lưu ý: Đảm bảo bạn đã use Carbon\Carbon; ở đầu file
        $data['created_at'] = Carbon::now()->tz('Asia/Ho_Chi_Minh');

        // 7. Lưu vào DB nếu không có lỗi
        if (empty($model_errors)) {
            $product = new Product();
            $product->fill($data); // Hàm fill sẽ tự động điền các cột khớp tên trong $data
            $product->save();

            redirect('/bookstore/public/manageProduct');
            exit;
        }

        // 8. Nếu có lỗi: Lưu dữ liệu form và quay lại trang Create
        $this->saveFormValues($_POST);
        redirect('/bookstore/public/create', ['errors' => $model_errors]);
    }

    protected function filterProductData(array $data)
    {
        return [
            'ma_sach' => $data['ma_sach'] ?? null,
            'ten_sach' => $data['ten_sach'] ?? null,
            'gia_sach' => $data['gia_sach'] ?? null,
            'khuyen_mai' => $data['khuyen_mai'] ?? null,
            'ma_loai_sach' => $data['ma_loai_sach'] ?? null,
            'ma_tac_gia' => $data['ma_tac_gia'] ?? null,
            'ma_nxb' => $data['ma_nxb'] ?? null,
            'so_luong' => $data['so_luong'] ?? null,
            'sold' => 0,
            'mo_ta' => $data['mo_ta'] ?? null
        ];
    }

  protected function uploadImage()
{
    $allow_type = ['jpg', 'png', 'jpeg', 'webp'];
    $target_dir = ROOTDIR . "public/img/product/"; // Đảm bảo đường dẫn đúng
    
    // Mảng chứa kết quả trả về
    $result = [];

    // --- 1. XỬ LÝ ẢNH CHÍNH (hinh_anh) ---
    if (isset($_FILES["hinh_anh"]) && $_FILES["hinh_anh"]["error"] === UPLOAD_ERR_OK) {
        
        $file = $_FILES["hinh_anh"];
        // Lấy tên gốc của file (VD: sach-hay.jpg)
        $originalName = basename($file["name"]); 
        $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

        if (in_array($ext, $allow_type)) {
            $target_file = $target_dir . $originalName;

            // KIỂM TRA TỒN TẠI
            if (file_exists($target_file)) {
                // Nếu file đã có, ta chỉ lấy tên file đưa vào mảng kết quả
                // Không cần upload đè lên
                $result['hinh_anh'] = $originalName;
            } else {
                // Nếu chưa có, tiến hành upload
                if (move_uploaded_file($file["tmp_name"], $target_file)) {
                    $result['hinh_anh'] = $originalName; 
                }
            }
        }
    }

    // --- 2. XỬ LÝ ẢNH CHI TIẾT (anh[]) ---
    $mapIndexToDbColumn = [0 => 'anh_1', 1 => 'anh_2'];

    if (isset($_FILES["anh"])) {
        foreach ($_FILES["anh"]["name"] as $index => $name) {
            // Kiểm tra: Có file, không lỗi, và index hợp lệ
            if (!empty($name) && $_FILES["anh"]["error"][$index] === UPLOAD_ERR_OK && isset($mapIndexToDbColumn[$index])) {
                
                $originalName = basename($name);
                $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
                
                if (in_array($ext, $allow_type)) {
                    $target_file = $target_dir . $originalName;
                    $colName = $mapIndexToDbColumn[$index];

                    // KIỂM TRA TỒN TẠI
                    if (file_exists($target_file)) {
                        // File đã tồn tại -> Dùng lại tên cũ
                        $result[$colName] = $originalName;
                    } else {
                        // File chưa có -> Upload mới
                        if (move_uploaded_file($_FILES["anh"]["tmp_name"][$index], $target_file)) {
                            $result[$colName] = $originalName;
                        }
                    }
                }
            }
        }
    }

    return $result;
}


    /*** UPDATE PRODUCT ***/
    public function showUpdatePage($productId)
    {
        $product = Product::where('sach.ma_sach', '=', $productId)->first();

        $product_type = ProductType::all();
        $tac_gia = TacGia::all();
        $nxb = NhaXuatBan::all();

        $this->sendPage('manage/update', [
            'errors' => session_get_once('errors_update'),
            'product' => $product,
            'old_value' => $this->getSavedUpdateFormValues(),
            'loai_sp' => $product_type,
            'tg' => $tac_gia,
            'nxb' => $nxb
        ]);
    }

    public function update($productId)
    {
        $product = Product::where('ma_sach', '=', $productId)->first();

        // 1. Lấy dữ liệu text từ form
        $data = $this->filterProductData($_POST);

        // 2. GỌI HÀM UPLOAD ẢNH CỦA BẠN
        // Hàm này trả về mảng ví dụ: ['hinh_anh' => 'new.jpg', 'anh_1' => 'new1.jpg']
        $uploadedImages = $this->uploadImage();

        // 3. Xử lý logic cập nhật ảnh
        // - Nếu có ảnh mới (tồn tại trong $uploadedImages) -> Cập nhật vào $data
        // - Nếu không có -> Xóa key khỏi $data (để Eloquent giữ nguyên ảnh cũ)

        // Xử lý ảnh chính
        if (isset($uploadedImages['hinh_anh'])) {
            $data['hinh_anh'] = $uploadedImages['hinh_anh'];
        } else {
            unset($data['hinh_anh']); // Giữ ảnh cũ
        }

        // Xử lý ảnh chi tiết 1
        if (isset($uploadedImages['anh_1'])) {
            $data['anh_1'] = $uploadedImages['anh_1'];
        } else {
            unset($data['anh_1']); // Giữ ảnh cũ
        }

        // Xử lý ảnh chi tiết 2
        if (isset($uploadedImages['anh_2'])) {
            $data['anh_2'] = $uploadedImages['anh_2'];
        } else {
            unset($data['anh_2']); // Giữ ảnh cũ
        }

        // 4. Validate dữ liệu
        $model_errors = Product::validate($data);

        // Bỏ qua lỗi validate "bắt buộc nhập ảnh" khi update (vì nếu unset thì mảng data không có ảnh, validator sẽ báo lỗi)
        if (!isset($data['hinh_anh'])) unset($model_errors['hinh_anh']);
        if (!isset($data['anh_1'])) unset($model_errors['anh_1']);
        if (!isset($data['anh_2'])) unset($model_errors['anh_2']);

        // 5. Loại bỏ các trường cấm sửa
        unset($data['ma_sach']);
        unset($data['created_at']);
        unset($data['sold']);

        // 6. Tính giá khuyến mãi
        if (isset($data['khuyen_mai']) && $data['khuyen_mai'] >= 0) {
            $data['gia_khuyen_mai'] = $data['gia_sach'] - $data['gia_sach'] * ($data['khuyen_mai'] / 100);
        } else {
            $data['gia_khuyen_mai'] = $data['gia_sach'];
        }

        // 7. Lưu vào DB
        if (empty($model_errors)) {
            $product->update($data);
            redirect('/bookstore/public/manageProduct');
            exit;
        }

        // Nếu lỗi -> Quay lại form
        $this->saveUpdateFormValues($_POST);
        redirect('/bookstore/public/manage/' . $productId, ['errors_update' => $model_errors]);
    }

    /*** DELETE PRODUCT ***/
    public function delete($productId)
    {
        // Xóa tất cả sản phẩm này khỏi giỏ hàng trước
        Cart::where('ma_sach', '=', $productId)->delete();
        $product = Product::where('ma_sach', '=', $productId)->first();
        if ($product) {
            $product->delete();
        }
        redirect('../../manageProduct');
    }


    /*** SORT PRODUCT ***/
    public function sortAllUsers()
    {
        if (isset($_POST['sort-user'])) {
            $sort_user = $_POST['sort-user'];
            if ($sort_user == 1) {
                $old_selected = array("val" => "1");
                $this->sendPage('manage/users', [
                    'users_manage' => User::all(),
                    'old_user_selected' => $old_selected
                ]);
            } else if ($sort_user == 2) {
                $old_selected = array("val" => "2");
                $this->sendPage('manage/users', [
                    'users_manage' => User::orderBy('id', 'ASC')->get(),
                    'old_user_selected' => $old_selected
                ]);
            } else if ($sort_user == 3) {
                $old_selected = array("val" => "3");
                $this->sendPage('manage/users', [
                    'users_manage' => User::orderBy('id', 'DESC')->get(),
                    'old_user_selected' => $old_selected
                ]);
            } else if ($sort_user == 4) {
                $old_selected = array("val" => "4");
                $this->sendPage('manage/users', [
                    'users_manage' => User::orderBy('created_at', 'DESC')->get(),
                    'old_user_selected' => $old_selected
                ]);
            }
        }
    }


    /*** MANAGE ALL USERS ***/
    public function getAllUsers()
    {
        $users_manage = User::all();
        $this->sendPage('manage/users', [
            'users_manage' => $users_manage
        ]);
    }

    /*** UPDATE USER'S ACCOUNT ***/
    protected function filterUserData(array $data)
    {
        return [
            'name' => $data['name'] ?? null,
            'email' => filter_var($data['email'], FILTER_VALIDATE_EMAIL),
            'phone' => $data['phone'] ?? null,
            'address' => $data['address'] ?? null,
        ];
    }

    public function userInfo()
    {
        $user = User::where('id', $_GET['userId'])->first();

        $this->sendPage('manage/userInfo', [
            'errors' => session_get_once('errors_update'),
            'user' => $user,
            'old_value' => $this->getSavedUpdateFormValues(),
        ]);
    }

    public function updateUser()
    {
        $user = User::where('id', $_POST['id'])->first();
        $data = $this->filterUserData($_POST);
        $model_errors = User::validateUpdate($data);
        if (!$data['email'] || $data['email'] == $user->email) {
            unset($data['email']);
            unset($model_errors['email']);
        }
        if (empty($model_errors)) {
            $user->update([
                'name' => $_POST['name'],
                'email' => $_POST['email'],
                'phone' => $_POST['phone'],
                'address' => $_POST['address'],
            ]);
            redirect('home');
        }

        $this->saveUpdateFormValues($_POST);
        redirect('userInfo?userId=' . $user->id, ['errors_update' => $model_errors]);
    }

    /*** UPDATE USER'S ACCOUNT ***/
    protected function filterPassData(array $data)
    {
        return [
            'password' => $data['password'] ?? null,
            'new_password' => $data['new_password'] ?? null,
            'password_confirmation' => $data['password_confirmation'] ?? null
        ];
    }

    public function passChange()
    {
        $user = User::where('id', $_GET['id'])->first();

        $this->sendPage('manage/passChange', [
            'errors' => session_get_once('errors_update'),
            'user' => $user,
            'old_value' => $this->getSavedUpdateFormValues(),
        ]);
    }

    public function updatePass()
    {
        $user = User::where('id', $_POST['id'])->first();
        $data = $this->filterPassData($_POST);
        $model_errors = User::validatePass($data);
        $verify = password_verify($data['password'], $user->password);
        if (!$verify) {
            $model_errors['password'] = 'Mật khẩu không đúng';
        }
        if (empty($model_errors) && $verify) {
            $user->update([
                'password' => password_hash($_POST['new_password'], PASSWORD_DEFAULT),
            ]);
            redirect('home');
        }

        $this->saveUpdateFormValues($_POST);
        redirect('passChange?id=' . $user->id, ['errors_update' => $model_errors]);
    }

    /*** MANAGE ALL BILLS ***/
    public function manageBill()
    {
        $khach = User::where('email', Guard::user()->email)->first();
        $this->sendPage('manage/manageBill', [
            'bills' => Bill::join('users', 'users.id', '=', 'hoadon.id')->orderBy('ma_hoa_don', 'DESC')->get()
        ]);
    }

    public function manageDetailBill()
    {
        $this->sendPage('manage/manageDetailBill', [
            'bill' => BillDetail::join('sach', 'sach.ma_sach', '=', 'chitiethoadon.ma_sach')->where('ma_hoa_don', $_GET['mhd'])->get(),
            'billdetail' => Bill::where('ma_hoa_don', $_GET['mhd'])->get()
        ]);
    }

    public function cancelBill($billId)
    {
        $data['trang_thai'] = "Canceled";
        $bill = Bill::where('ma_hoa_don', '=', $billId)->first();
        $bill->update($data);
        redirect('../../manageBill');
    }

    public function send($billId)
    {
        $data['trang_thai'] = "sending";
        $bill = Bill::where('ma_hoa_don', '=', $billId)->first();
        $bill->update($data);
        redirect('../../manageBill');
    }

    /*** BILL FILTER ***/
    public function sortBill()
    {
        if (isset($_POST['bill-filter'])) {
            $filter_bill = $_POST['bill-filter'];
            if ($filter_bill == 1) {
                $old_selected = array("val" => "1");
                $this->sendPage('manage/manageBill', [
                    'bills' => Bill::orderBy('ma_hoa_don', 'DESC')->get(),
                    'old_selected' => $old_selected
                ]);
            } else if ($filter_bill == 2) {
                $old_selected = array("val" => "2");
                $this->sendPage('manage/manageBill', [
                    'bills' => Bill::where('hoadon.trang_thai', '=', 'processing')->get(),
                    'old_selected' => $old_selected
                ]);
            } else if ($filter_bill == 3) {
                $old_selected = array("val" => "3");
                $this->sendPage('manage/manageBill', [
                    'bills' => Bill::where('hoadon.trang_thai', '=', 'sending')->get(),
                    'old_selected' => $old_selected
                ]);
            } else if ($filter_bill == 4) {
                $old_selected = array("val" => "4");
                $this->sendPage('manage/manageBill', [
                    'bills' => Bill::where('hoadon.trang_thai', '=', 'recieved')->get(),
                    'old_selected' => $old_selected
                ]);
            } else if ($filter_bill == 5) {
                $old_selected = array("val" => "5");
                $this->sendPage('manage/manageBill', [
                    'bills' => Bill::where('hoadon.trang_thai', '=', 'Canceled')->get(),
                    'old_selected' => $old_selected
                ]);
            }
        }
    }

   public function indexArticles()
    {
        // 1. Xác định trang hiện tại từ URL (ví dụ: ?page=2). Mặc định là trang 1.
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $page = $page > 0 ? $page : 1; 
        
        $perPage = 10; // Số bài viết mỗi trang
        $offset = ($page - 1) * $perPage;

        // 2. Khởi tạo câu truy vấn cơ bản (Chưa lấy dữ liệu vội)
        $query = Article::with('admin');

        // 3. XỬ LÝ LỌC: Nếu có tham số 'status' gửi lên từ form thì thêm điều kiện
        if (!empty($_GET['status'])) {
            $query->where('status', $_GET['status']);
        }

        // 4. Đếm tổng số bài viết (dựa trên query đã lọc) để tính tổng trang
        $totalArticles = $query->count();
        $totalPages = ceil($totalArticles / $perPage);

        // 5. Lấy dữ liệu chính thức với skip, take và sắp xếp
        $articles = $query->latest('created_at')
            ->skip($offset)
            ->take($perPage)
            ->get(); 

        // 6. Trả về view cùng với các biến cần thiết
        $this->sendPage('manage/manageArticles', [
            'articles'      => $articles,
            'currentPage'   => $page,
            'totalPages'    => $totalPages,
            'currentStatus' => $_GET['status'] ?? '' // Truyền biến này ra để giữ trạng thái đã chọn ở file HTML
        ]);
    }

    /**
     * Xử lý cập nhật bài viết vào CSDL
     * @param int $id Mã bài viết cần sửa (lấy từ URL)
     */
    public function updateArticle($id)
    {
        // 1. Lấy dữ liệu từ form gửi lên
        $title = $_POST['title'] ?? '';
        $summary = $_POST['summary'] ?? '';
        $content = $_POST['content'] ?? '';
        $status = $_POST['status'] ?? 'draft';
        $thumbnail_cu = $_POST['thumbnail_cu'] ?? '';

        // Validate cơ bản (bạn có thể tuỳ chỉnh lại logic báo lỗi giống project của bạn)
        if (empty($title) || empty($content)) {
            // Lưu lỗi vào session và quay lại trang sửa
            // $_SESSION['errors'] = ['title' => 'Tiêu đề không được để trống!'];
            header("Location: /bookstore/public/article/edit/" . $id);
            exit;
        }

        // 2. Xử lý Upload Ảnh Bìa (Thumbnail)
        $thumbnail = $thumbnail_cu; // Mặc định là giữ lại tên ảnh cũ

        // Kiểm tra xem người dùng có chọn file ảnh mới không (mã lỗi 0 = UPLOAD_ERR_OK)
        if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === 0) {
            
            // Định nghĩa đường dẫn lưu file (Bạn nhớ điều chỉnh lại số lượng '../' cho đúng với vị trí thư mục public của bạn)
            $uploadDir = __DIR__ . '/../../../public/img/blog/'; 
            
            // Tạo thư mục nếu chưa tồn tại
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            // Đổi tên file để tránh bị trùng lặp khi nhiều người up trùng tên file
            $fileName = time() . '_' . basename($_FILES['thumbnail']['name']);
            $targetFilePath = $uploadDir . $fileName;

            // Di chuyển file từ thư mục tạm vào thư mục chính thức
            if (move_uploaded_file($_FILES['thumbnail']['tmp_name'], $targetFilePath)) {
                $thumbnail = $fileName; // Đổi biến $thumbnail thành tên file mới để lưu vào CSDL
                
                // (Tùy chọn) Xóa ảnh cũ đi cho nhẹ dung lượng Server
                if (!empty($thumbnail_cu) && file_exists($uploadDir . $thumbnail_cu)) {
                    unlink($uploadDir . $thumbnail_cu);
                }
            }
        }

        // 3. Thực hiện Cập nhật vào Cơ sở dữ liệu bằng Model Article
        Article::where('article_id', $id)->update([
            'title'     => $title,
            'summary'   => $summary,
            'content'   => $content,
            'thumbnail' => $thumbnail,
            'status'    => $status,
        ]);

        // 4. Chuyển hướng người dùng về lại trang Danh sách bài viết sau khi thành công
        header('Location: /bookstore/public/manageArticles');
        exit;
    }

    /**
     * Hiển thị giao diện sửa bài viết
     * @param int $id Mã bài viết truyền từ URL
     */
    public function editArticle($id)
    {
        // 1. Truy vấn lấy thông tin bài viết theo Khóa chính (article_id)
        $article = Article::where('article_id', $id)->first();

        // 2. Bảo mật cơ bản: Nếu user cố tình nhập ID tào lao trên URL và không tìm thấy bài viết
        if (!$article) {
            // Chuyển hướng ngay về trang danh sách bài viết
            header('Location: /bookstore/public/manageArticles');
            exit;
        }

        // 3. Trả về view 'edit_article' kèm theo dữ liệu
        // Lưu ý: Đổi chữ 'manage/edit_article' thành đúng tên file giao diện bạn đã lưu nhé
        $this->sendPage('manage/edit_article', [
            'article'   => $article,                               // Dữ liệu bài viết hiện tại
            'errors'    => session_get_once('errors_article'),     // Lỗi xác thực (nếu có lúc update)
            'old_value' => $this->getSavedUpdateFormValues(),      // Giá trị cũ đang nhập dở (nếu có lỗi)
        ]);
    }

    /**
     * Xử lý xóa bài viết và file ảnh kèm theo
     * @param int $id Mã bài viết cần xóa (lấy từ URL)
     */
    public function deleteArticle($id)
    {
        // 1. Tìm bài viết dựa vào ID
        $article = Article::where('article_id', $id)->first();

        // Nếu bài viết tồn tại thì mới tiến hành xóa
        if ($article) {
            
            // 2. Xóa file ảnh bìa vật lý trên server (nếu có ảnh)
            if (!empty($article->thumbnail)) {
                $imagePath = '/var/www/html/public/img/blog/' . $article->thumbnail;
                
                // Kiểm tra xem file có thực sự tồn tại trên ổ cứng không rồi mới xóa
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }

            // 3. Xóa dữ liệu bài viết trong Cơ sở dữ liệu
            $article->delete();
        }

        // 4. Chuyển hướng người dùng về lại trang Danh sách bài viết
        header('Location: /bookstore/public/manageArticles');
        exit;
    }

    /**
     * Hiển thị giao diện Thêm bài viết mới
     */
    public function createArticle()
    {
        // Trả về view 'add_article' (Bạn nhớ tạo file giao diện này nhé)
        $this->sendPage('manage/add_article', [
            'errors'    => session_get_once('errors_article'),
            'old_value' => $this->getSavedUpdateFormValues(),
        ]);
    }

    /**
     * Xử lý lưu bài viết mới vào CSDL
     */
    public function storeArticle()
    {
        // 1. Lấy dữ liệu từ form gửi lên
        $title = $_POST['title'] ?? '';
        $summary = $_POST['summary'] ?? '';
        $content = $_POST['content'] ?? '';
        $status = $_POST['status'] ?? 'published';
        
        // Lấy ID của Admin đang đăng nhập. 
        // Nếu bạn có dùng Session thì thay bằng $_SESSION['user_id'], tạm thời tôi để mặc định là 2 (tài khoản Admin của bạn).
        $admin_id = 2; 

        // Validate cơ bản
        if (empty($title) || empty($content)) {
            // Quay lại trang thêm nếu có lỗi
            header("Location: /bookstore/public/manageArticles/createArticle");
            exit;
        }

        // 2. Xử lý Upload Ảnh Bìa (Thumbnail)
        $thumbnail = ''; 

        if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === 0) {
            
            $uploadDir = '/var/www/html/public/img/blog/'; 
            
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            // Giữ nguyên tên file ảnh gốc
            $fileName = basename($_FILES['thumbnail']['name']);
            $targetFilePath = $uploadDir . $fileName;

            // Kiểm tra: Nếu file đã tồn tại thì không upload nữa, chỉ lấy tên lưu vào DB
            if (file_exists($targetFilePath)) {
                $thumbnail = $fileName;
            } else {
                // Nếu chưa có thì upload file mới vào
                if (move_uploaded_file($_FILES['thumbnail']['tmp_name'], $targetFilePath)) {
                    $thumbnail = $fileName;
                }
            }
        }

        // 3. Thực hiện Thêm Mới vào Cơ sở dữ liệu
        // Lưu ý: Dùng create() thay vì update() cho việc thêm mới
        Article::create([
            'admin_id'  => $admin_id,
            'title'     => $title,
            'summary'   => $summary,
            'content'   => $content,
            'thumbnail' => $thumbnail,
            'status'    => $status,
        ]);

        // 4. Chuyển hướng về trang Danh sách
        header('Location: /bookstore/public/manageArticles');
        exit;
    }
}
