<?php

namespace App\Controllers;

use App\Models\Article;

class ArticleController extends Controller
{
    /**
     * Hiển thị danh sách bài viết trên trang Blog cho khách hàng
     */
    public function index()
    {
        // 1. Cấu hình phân trang (giống với Admin)
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $page = $page > 0 ? $page : 1; 
        $perPage = 10; 
        $offset = ($page - 1) * $perPage;

        // 2. Khởi tạo câu truy vấn: LẤY KÈM THÔNG TIN ADMIN VÀ CHỈ LẤY BÀI 'published'
        $query = Article::with('admin')->where('status', 'published');

        if (!empty($_GET['search'])) {
            $keyword = $_GET['search'];
            $query->where('title', 'LIKE', '%' . $keyword . '%');
        }

        $totalArticles = $query->count();
        $totalPages = ceil($totalArticles / $perPage);

        $articles = $query->latest('created_at')
            ->skip($offset)
            ->take($perPage)
            ->get(); 

        $this->sendPage('blog/blog', [
            'articles'    => $articles,
            'currentPage' => $page,
            'totalPages'  => $totalPages,
            'search'      => $_GET['search'] ?? ''
        ]);
    }

    /**
     * Hiển thị chi tiết một bài viết
     * @param int $id Mã bài viết (truyền từ URL)
     */
    public function show($id)
    {
        // 1. Truy vấn bài viết theo ID. 
        // BẮT BUỘC: Phải kèm điều kiện 'status' = 'published' để tránh khách mò ID đọc lén bài nháp
        $article = Article::with('admin')
            ->where('article_id', $id)
            ->where('status', 'published')
            ->first();

        // 2. Xử lý trường hợp không tìm thấy bài viết (hoặc bài đã bị ẩn/xóa)
        if (!$article) {
            // Chuyển hướng người dùng về lại trang danh sách Blog
            header('Location: /bookstore/public/blog');
            exit;
        }

        // 3. Lấy 3 bài viết mới nhất (trừ bài hiện tại ra) để làm mục "Bài viết liên quan"
        $relatedArticles = Article::where('status', 'published')
            ->where('article_id', '!=', $id) // Không lấy lại bài đang đọc
            ->latest('created_at')
            ->take(3)
            ->get();

        // 4. Trả về View chi tiết (Ví dụ: views/blog/detail.php)
        $this->sendPage('blog/detail', [
            'article'         => $article,
            'relatedArticles' => $relatedArticles
        ]);
    }
}