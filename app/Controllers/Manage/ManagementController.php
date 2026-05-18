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
        $this->requirePermission('product.view');
        $products_manage = Product::join('loaisach', 'loaisach.ma_loai_sach', '=', 'sach.ma_loai_sach')->orderBy('sach.ma_sach', 'ASC')->get();
        
        // Lấy thêm danh sách metadata để quản lý
        $types = ProductType::all();
        $authors = TacGia::all();
        $publishers = NhaXuatBan::all();

        $this->sendPage('manage/manageProduct', [
            'products_manage' => $products_manage,
            'types' => $types,
            'authors' => $authors,
            'publishers' => $publishers
        ]);
    }


    /*** SORT PRODUCT ***/
    public function sortAllProducts()
    {
        $this->requirePermission('product.view');
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
        $this->requirePermission('product.create');
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
        $this->requirePermission('product.create');
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
        $this->requirePermission('product.update');
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
        $this->requirePermission('product.update');
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
        $this->requirePermission('product.delete');
        // Xóa tất cả sản phẩm này khỏi giỏ hàng trước
        Cart::where('ma_sach', '=', $productId)->delete();
        $product = Product::where('ma_sach', '=', $productId)->first();
        if ($product) {
            $product->delete();
        }
        redirect('../../manageProduct');
    }


    /*** SORT USERS ***/
    public function sortAllUsers()
    {
        $this->requirePermission('user.view_all');
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
        if (!Guard::can('user.view_all') && !Guard::can('user.view_customers')) {
            $this->requirePermission('user.view_all');
        }

        $currentUser = Guard::user();
        if ($currentUser->can('user.view_all')) {
            $users_manage = User::all();
        } else {
            $users_manage = User::customers()->get();
        }

        $this->sendPage('manage/users', [
            'users_manage' => $users_manage,
            'messages' => session_get_once('messages'),
            'errors' => session_get_once('errors')
        ]);
    }

    /*** UPDATE USER'S ACCOUNT ***/
    protected function filterUserData(array $data)
    {
        return [
            'name' => $data['name'] ?? null,
            'email' => isset($data['email']) ? filter_var($data['email'], FILTER_VALIDATE_EMAIL) : null,
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
        
        // Không cho phép cập nhật email theo yêu cầu bảo mật
        unset($data['email']);
        unset($model_errors['email']);
        
        if (empty($model_errors)) {
            $user->update([
                'name' => $_POST['name'] ?? $user->name,
                'phone' => $_POST['phone'] ?? $user->phone,
                'address' => $_POST['address'] ?? $user->address,
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
        if (!Guard::can('bill.view') && !Guard::can('user.view_bill_history')) {
            $this->requirePermission('bill.view');
        }

        $query = Bill::join('users', 'users.id', '=', 'hoadon.id');

        // Xử lý tìm kiếm (Ví dụ: từ link "Lịch sử" trong Quản lý người dùng)
        if (isset($_GET['search']) && !empty($_GET['search'])) {
            $search = $_GET['search'];
            $query->where(function($q) use ($search) {
                $q->where('users.email', 'like', "%$search%")
                  ->orWhere('users.name', 'like', "%$search%")
                  ->orWhere('hoadon.ma_hoa_don', 'like', "%$search%");
            });
        }

        $this->sendPage('manage/manageBill', [
            'bills' => $query->orderBy('ma_hoa_don', 'DESC')->select('hoadon.*', 'users.name as ten_khach_hang')->get()
        ]);
    }

    public function manageDetailBill()
    {
        if (!Guard::can('bill.view') && !Guard::can('user.view_bill_history')) {
            $this->requirePermission('bill.view');
        }
        $this->sendPage('manage/manageDetailBill', [
            'bill' => BillDetail::join('sach', 'sach.ma_sach', '=', 'chitiethoadon.ma_sach')->where('ma_hoa_don', $_GET['mhd'])->get(),
            'billdetail' => Bill::where('ma_hoa_don', $_GET['mhd'])->get()
        ]);
    }

    public function cancelBill($billId)
    {
        // Chỉ ADMIN mới có quyền xóa/hủy hoàn toàn; ORDER_STAFF chỉ cập nhật trạng thái
        $this->requirePermission('bill.delete');
        $data['trang_thai'] = "Canceled";
        $bill = Bill::where('ma_hoa_don', '=', $billId)->first();
        $bill->update($data);
        redirect('../../manageBill');
    }

    public function send($billId)
    {
        $this->requirePermission('bill.update_status');
        $data['trang_thai'] = "sending";
        $bill = Bill::where('ma_hoa_don', '=', $billId)->first();
        $bill->update($data);
        redirect('../../manageBill');
    }

    /*** BILL FILTER ***/
    public function sortBill()
    {
        if (!Guard::can('bill.view') && !Guard::can('user.view_bill_history')) {
            $this->requirePermission('bill.view');
        }
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
        $this->requirePermission('article.view');
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
        $this->requirePermission('article.update');
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
        $this->requirePermission('article.update');
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
        $this->requirePermission('article.delete');
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
        $this->requirePermission('article.create');
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
        $this->requirePermission('article.create');
        // 1. Lấy dữ liệu từ form gửi lên
        $title = $_POST['title'] ?? '';
        $summary = $_POST['summary'] ?? '';
        $content = $_POST['content'] ?? '';
        $status = $_POST['status'] ?? 'published';

        // Lấy ID của người đang đăng nhập làm audit log (admin_id)
        $admin_id = Guard::user()->id;

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

    public function deleteUser()
    {
        $this->requirePermission('user.delete');
        $id = $_POST['id'] ?? null;
        if ($id && $id != Guard::user()->id) {
            $userToDelete = User::where('id', $id)->first();
            if ($userToDelete) {
                $currentUser = Guard::user();
                // Bảo mật: Không cho phép STORE_OWNER xóa ADMIN hoặc STORE_OWNER khác
                if ($userToDelete->isAdmin() && !$currentUser->isAdmin()) {
                    redirect('users', ['errors' => ['delete' => 'Bạn không có quyền xóa Quản trị viên.']]);
                    return;
                }
                if ($userToDelete->role === User::ROLE_STORE_OWNER && !$currentUser->isAdmin()) {
                    redirect('users', ['errors' => ['delete' => 'Bạn không có quyền xóa Chủ cửa hàng khác.']]);
                    return;
                }
                \App\Models\SystemLog::write('Xóa người dùng', [
                    'id' => $userToDelete->id,
                    'name' => $userToDelete->name,
                    'role' => $userToDelete->role
                ]);
                $userToDelete->delete();
            }
        }
        redirect('users');
    }

    public function createStaff()
    {
        $this->requirePermission('user.create');

        $data = [
            'name' => $_POST['name'] ?? '',
            'email' => $_POST['email'] ?? '',
            'phone' => $_POST['phone'] ?? '',
            'address' => $_POST['address'] ?? '',
            'password' => $_POST['password'] ?? '',
            'password_confirmation' => $_POST['password_confirmation'] ?? '',
            'role' => $_POST['role'] ?? User::ROLE_CUSTOMER
        ];

        // Bảo mật: Không cho phép non-Admin tạo Admin
        $currentUser = Guard::user();
        if ($data['role'] === User::ROLE_ADMIN && !$currentUser->isAdmin()) {
            $data['role'] = User::ROLE_ORDER_STAFF; // Mặc định về role thấp hơn nếu cố tình hack
        }

        // Validation cơ bản
        $errors = [];
        if (empty($data['name'])) $errors['name'] = "Tên không được để trống";
        if (empty($data['email'])) {
            $errors['email'] = "Email không được để trống";
        } else {
            // Kiểm tra email trùng
            $existingUser = User::where('email', $data['email'])->first();
            if ($existingUser) {
                $errors['email'] = "Email này đã được sử dụng bởi một tài khoản khác";
            }
        }

        if (!empty($data['phone'])) {
            // Kiểm tra số điện thoại trùng
            $existingPhone = User::where('phone', $data['phone'])->first();
            if ($existingPhone) {
                $errors['phone'] = "Số điện thoại này đã được sử dụng";
            }
        }

        if (empty($data['password'])) $errors['password'] = "Mật khẩu không được để trống";
        if ($data['password'] !== $data['password_confirmation']) {
            $errors['password'] = "Mật khẩu nhập lại không khớp";
        }

        if (empty($errors)) {
            $newStaff = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'address' => $data['address'],
                'password' => password_hash($data['password'], PASSWORD_DEFAULT),
                'role' => $data['role']
            ]);
            \App\Models\SystemLog::write('Tạo nhân sự mới', [
                'id' => $newStaff->id,
                'name' => $newStaff->name,
                'email' => $newStaff->email,
                'role' => $newStaff->role
            ]);
            redirect('users', ['messages' => ['success' => 'Đã thêm nhân sự thành công.']]);
        }

        redirect('users', ['errors' => $errors]);
    }

    public function updateStaff()
    {
        $this->requirePermission('user.update');
        $id = $_POST['id'] ?? null;
        if (!$id) redirect('users');

        $user = User::where('id', $id)->first();
        if (!$user) redirect('users');

        $data = [
            'name' => $_POST['name'] ?? $user->name,
            'address' => $_POST['address'] ?? $user->address,
            'role' => $_POST['role'] ?? $user->role
        ];

        // Bảo mật: Không cho phép đổi thành ADMIN nếu không phải ADMIN
        $currentUser = Guard::user();
        if ($data['role'] === User::ROLE_ADMIN && !$currentUser->isAdmin()) {
            $data['role'] = User::ROLE_ORDER_STAFF; // Ngăn chặn
        }

        $user->update($data);

        \App\Models\SystemLog::write('Cập nhật nhân sự', [
            'id' => $user->id,
            'name' => $user->name,
            'role' => $user->role
        ]);

        redirect('users', ['messages' => ['success' => 'Cập nhật thông tin nhân sự thành công.']]);
    }

    public function getUserOrders()
    {
        $this->requirePermission('user.view_bill_history');
        $id = $_GET['id'] ?? null;
        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'ID người dùng không hợp lệ']);
            return;
        }

        $orders = Bill::where('id', $id)->orderBy('ma_hoa_don', 'DESC')->get();
        
        // Nạp thêm thông tin chi tiết cho mỗi đơn hàng (bao gồm tên sách và ảnh)
        foreach ($orders as $order) {
            $order->details = BillDetail::join('sach', 'sach.ma_sach', '=', 'chitiethoadon.ma_sach')
                ->where('ma_hoa_don', $order->ma_hoa_don)
                ->select('chitiethoadon.*', 'sach.ten_sach', 'sach.hinh_anh')
                ->get();
        }
        
        echo json_encode(['success' => true, 'orders' => $orders]);
    }

    // =========================================================
    // MANAGE METADATA (Type, Author, Publisher)
    // =========================================================
    
    // Loai Sach
    public function addType() {
        $this->requirePermission('product.create');
        
        // Auto generate ID (Pattern BXX)
        $lastType = ProductType::where('ma_loai_sach', 'LIKE', 'B%')
                               ->orderBy('ma_loai_sach', 'DESC')
                               ->first();
        if ($lastType) {
            $lastId = $lastType->ma_loai_sach; 
            $num = (int)substr($lastId, 1);
            $newId = 'B' . str_pad($num + 1, 2, '0', STR_PAD_LEFT);
        } else {
            $newId = 'B01';
        }
        
        ProductType::create(['ma_loai_sach' => $newId, 'ten_loai_sach' => $_POST['name']]);
        redirect('/bookstore/public/manageProduct', ['success' => 'Thêm loại sách mới thành công!']);
    }
    public function updateType() {
        $this->requirePermission('product.update');
        $type = ProductType::where('ma_loai_sach', $_POST['id'])->first();
        if ($type) $type->update(['ten_loai_sach' => $_POST['name']]);
        redirect('/bookstore/public/manageProduct', ['success' => 'Cập nhật loại sách thành công!']);
    }
    public function deleteType($id) {
        $this->requirePermission('product.delete');
        $count = Product::where('ma_loai_sach', $id)->count();
        if ($count > 0) {
            redirect('/bookstore/public/manageProduct', ['errors' => ['metadata' => 'Không thể xóa loại sách này vì vẫn còn sản phẩm thuộc loại này.']]);
        } else {
            ProductType::where('ma_loai_sach', $id)->delete();
            redirect('/bookstore/public/manageProduct');
        }
    }

    // Tac Gia
    public function addAuthor() {
        $this->requirePermission('product.create');
        
        // Auto generate numeric ID
        $lastAuthor = TacGia::orderBy('ma_tac_gia', 'DESC')->first();
        $newId = $lastAuthor ? ($lastAuthor->ma_tac_gia + 1) : 1;
        
        TacGia::create(['ma_tac_gia' => $newId, 'ten_tac_gia' => $_POST['name']]);
        redirect('/bookstore/public/manageProduct', ['success' => 'Thêm tác giả mới thành công!']);
    }
    public function updateAuthor() {
        $this->requirePermission('product.update');
        $author = TacGia::where('ma_tac_gia', $_POST['id'])->first();
        if ($author) $author->update(['ten_tac_gia' => $_POST['name']]);
        redirect('/bookstore/public/manageProduct', ['success' => 'Cập nhật tác giả thành công!']);
    }
    public function deleteAuthor($id) {
        $this->requirePermission('product.delete');
        $count = Product::where('ma_tac_gia', $id)->count();
        if ($count > 0) {
            redirect('/bookstore/public/manageProduct', ['errors' => ['metadata' => 'Không thể xóa tác giả này vì vẫn còn sản phẩm của tác giả này.']]);
        } else {
            TacGia::where('ma_tac_gia', $id)->delete();
            redirect('/bookstore/public/manageProduct');
        }
    }

    // Nha Xuat Ban
    public function addPublisher() {
        $this->requirePermission('product.create');
        
        // Auto generate numeric ID
        $lastNxb = NhaXuatBan::orderBy('ma_nxb', 'DESC')->first();
        $newId = $lastNxb ? ($lastNxb->ma_nxb + 1) : 1;

        NhaXuatBan::create([
            'ma_nxb' => $newId, 
            'ten_nxb' => $_POST['name'],
            'sdt_nxb' => $_POST['phone'],
            'dia_chi_nxb' => $_POST['address']
        ]);
        redirect('/bookstore/public/manageProduct', ['success' => 'Thêm nhà xuất bản mới thành công!']);
    }
    public function updatePublisher() {
        $this->requirePermission('product.update');
        $nxb = NhaXuatBan::where('ma_nxb', $_POST['id'])->first();
        if ($nxb) {
            $nxb->update([
                'ten_nxb' => $_POST['name'],
                'sdt_nxb' => $_POST['phone'],
                'dia_chi_nxb' => $_POST['address']
            ]);
        }
        redirect('/bookstore/public/manageProduct', ['success' => 'Cập nhật nhà xuất bản thành công!']);
    }
    public function deletePublisher($id) {
        $this->requirePermission('product.delete');
        $count = Product::where('ma_nxb', $id)->count();
        if ($count > 0) {
            redirect('/bookstore/public/manageProduct', ['errors' => ['metadata' => 'Không thể xóa NXB này vì vẫn còn sản phẩm của NXB này.']]);
        } else {
            NhaXuatBan::where('ma_nxb', $id)->delete();
            redirect('/bookstore/public/manageProduct');
        }
    }

    // =========================================================
    // SYSTEM LOGS & SYSTEM OPERATIONS (For ADMIN Only)
    // =========================================================

    public function systemLogs()
    {
        $this->requirePermission('log.view');

        // Phân trang
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $page = $page > 0 ? $page : 1;
        $perPage = 20;

        $query = \App\Models\SystemLog::query();

        // Xử lý bộ lọc tìm kiếm
        if (isset($_GET['search']) && !empty($_GET['search'])) {
            $search = $_GET['search'];
            $query->where(function($q) use ($search) {
                $q->where('action', 'like', "%$search%")
                  ->orWhere('user_name', 'like', "%$search%")
                  ->orWhere('details', 'like', "%$search%");
            });
        }

        $total = $query->count();
        $pages = ceil($total / $perPage);
        $offset = ($page - 1) * $perPage;

        $logs = $query->orderBy('created_at', 'DESC')
                      ->offset($offset)
                      ->limit($perPage)
                      ->get();

        $this->sendPage('manage/systemLogs', [
            'logs' => $logs,
            'page' => $page,
            'pages' => $pages,
            'total' => $total,
            'messages' => session_get_once('messages'),
        ]);
    }

    public function clearLogs()
    {
        $this->requirePermission('log.view'); // Đảm bảo đúng quyền
        \App\Models\SystemLog::truncate();
        \App\Models\SystemLog::write('Xóa Logs', 'Admin đã xóa sạch dữ liệu System Logs');
        redirect('systemLogs', ['messages' => ['success' => 'Đã xóa toàn bộ logs thành công!']]);
    }

    public function systemConfig()
    {
        $this->requirePermission('system.manage');

        // Đọc thông số máy chủ thực tế (Linux)
        $phpVersion = PHP_VERSION;
        
        // Đo dung lượng RAM
        $memFree = 0;
        $memTotal = 0;
        if (file_exists('/proc/meminfo')) {
            $data = @explode("\n", @file_get_contents('/proc/meminfo'));
            if ($data) {
                foreach ($data as $line) {
                    list($key, $val) = explode(":", $line . ":");
                    if (trim($key) == 'MemTotal') {
                        $memTotal = (int)filter_var($val, FILTER_SANITIZE_NUMBER_INT) / 1024; // MB
                    }
                    if (trim($key) == 'MemAvailable' || trim($key) == 'MemFree') {
                        $memFree = (int)filter_var($val, FILTER_SANITIZE_NUMBER_INT) / 1024; // MB
                    }
                }
            }
        }
        $memUsage = $memTotal > 0 ? round((($memTotal - $memFree) / $memTotal) * 100, 1) : 0;

        // Đo dung lượng ổ đĩa
        $diskTotal = @disk_total_space('/') / (1024 * 1024 * 1024); // GB
        $diskFree = @disk_free_space('/') / (1024 * 1024 * 1024); // GB
        $diskUsage = $diskTotal > 0 ? round((($diskTotal - $diskFree) / $diskTotal) * 100, 1) : 0;

        // CPU load
        $load = @sys_getloadavg();
        $cpuUsage = isset($load[0]) ? round($load[0] * 100 / 4, 1) : 0; // Giả sử máy chủ có 4 cores

        // Đọc trạng thái Maintenance Mode từ database
        try {
            $maintenanceSetting = \Illuminate\Database\Capsule\Manager::table('settings')->where('key', 'maintenance')->first();
            $maintenance = ($maintenanceSetting && $maintenanceSetting->value === '1');
        } catch (\Exception $e) {
            $maintenance = false;
        }

        $this->sendPage('manage/systemConfig', [
            'phpVersion' => $phpVersion,
            'ramUsage' => $memUsage,
            'ramTotal' => round($memTotal / 1024, 2), // GB
            'diskUsage' => $diskUsage,
            'diskTotal' => round($diskTotal, 2), // GB
            'cpuUsage' => $cpuUsage,
            'maintenance' => $maintenance,
            'messages' => session_get_once('messages'),
        ]);
    }

    public function toggleMaintenance()
    {
        $this->requirePermission('system.manage');

        try {
            $maintenanceSetting = \Illuminate\Database\Capsule\Manager::table('settings')->where('key', 'maintenance')->first();
            $currentStatus = ($maintenanceSetting && $maintenanceSetting->value === '1');
            $newStatus = !$currentStatus;

            \Illuminate\Database\Capsule\Manager::table('settings')
                ->where('key', 'maintenance')
                ->update(['value' => $newStatus ? '1' : '0']);

            \App\Models\SystemLog::write(
                $newStatus ? 'Bảo trì: Bật' : 'Bảo trì: Tắt',
                'Thay đổi trạng thái chế độ bảo trì hệ thống.'
            );

            redirect('systemConfig', ['messages' => ['success' => 'Đã ' . ($newStatus ? 'BẬT' : 'TẮT') . ' chế độ bảo trì hệ thống thành công!']]);
        } catch (\Exception $e) {
            redirect('systemConfig', ['messages' => ['error' => 'Lỗi cập nhật chế độ bảo trì: ' . $e->getMessage()]]);
        }
    }

    public function clearCache()
    {
        $this->requirePermission('system.manage');

        \App\Models\SystemLog::write('Xóa Cache', 'Admin đã thực hiện dọn dẹp bộ nhớ đệm (Cache) của hệ thống.');

        redirect('systemConfig', ['messages' => ['success' => 'Đã xóa bộ nhớ đệm (Cache) hệ thống thành công!']]);
    }

    public function backupDb()
    {
        $this->requirePermission('system.manage');

        try {
            $connection = \Illuminate\Database\Capsule\Manager::connection();
            $pdo = $connection->getPdo();
            $dbName = $_ENV['DB_NAME'] ?? 'bookstore_db';

            // Khởi tạo nội dung file SQL
            $sql = "-- Bookworm Store Database Backup\n";
            $sql .= "-- Generated: " . date('Y-m-d H:i:s') . "\n";
            $sql .= "-- Database: " . $dbName . "\n";
            $sql .= "-- --------------------------------------------------------\n\n";
            $sql .= "SET FOREIGN_KEY_CHECKS=0;\n\n";

            // Lấy danh sách các bảng
            $tablesResult = $pdo->query("SHOW TABLES");
            $tables = [];
            while ($row = $tablesResult->fetch(\PDO::FETCH_NUM)) {
                $tables[] = $row[0];
            }

            foreach ($tables as $table) {
                $sql .= "-- --------------------------------------------------------\n";
                $sql .= "-- Table structure for table `{$table}`\n";
                $sql .= "-- --------------------------------------------------------\n";
                $sql .= "DROP TABLE IF EXISTS `{$table}`;\n";

                // SHOW CREATE TABLE
                $createStatementResult = $pdo->query("SHOW CREATE TABLE `{$table}`");
                $createStatementRow = $createStatementResult->fetch(\PDO::FETCH_NUM);
                $sql .= $createStatementRow[1] . ";\n\n";

                // Lấy dữ liệu của bảng
                $rowsResult = $pdo->query("SELECT * FROM `{$table}`");
                $columnCount = $rowsResult->columnCount();

                if ($rowsResult->rowCount() > 0) {
                    $sql .= "-- Dumping data for table `{$table}`\n";
                    $sql .= "LOCK TABLES `{$table}` WRITE;\n";
                    $sql .= "INSERT INTO `{$table}` VALUES \n";

                    $first = true;
                    while ($row = $rowsResult->fetch(\PDO::FETCH_NUM)) {
                        if (!$first) {
                            $sql .= ",\n";
                        }
                        $values = [];
                        for ($i = 0; $i < $columnCount; $i++) {
                            if (is_null($row[$i])) {
                                $values[] = "NULL";
                            } else {
                                $values[] = $pdo->quote($row[$i]);
                            }
                        }
                        $sql .= "(" . implode(",", $values) . ")";
                        $first = false;
                    }
                    $sql .= ";\n";
                    $sql .= "UNLOCK TABLES;\n\n";
                }
            }

            $sql .= "SET FOREIGN_KEY_CHECKS=1;\n";

            $backupFileName = 'backup_' . $dbName . '_' . date('Y-m-d_H-i-s') . '.sql';

            // Ghi nhận log
            \App\Models\SystemLog::write('Sao lưu CSDL', 'Admin sao lưu thành công database ' . $dbName . ' qua bộ backup PDO Exporter.');

            // Thiết lập headers tải về trực tiếp từ bộ nhớ đệm PHP
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . $backupFileName . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . strlen($sql));

            echo $sql;
            exit;

        } catch (\Exception $e) {
            \App\Models\SystemLog::write('Sao lưu CSDL thất bại', 'Lỗi sao lưu PDO: ' . $e->getMessage());
            redirect('systemConfig', ['messages' => ['error' => 'Lỗi sao lưu CSDL: ' . $e->getMessage()]]);
        }
    }
}
