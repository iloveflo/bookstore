<?php $this->layout("/layouts/default", ["title" => "Blog - " . APPNAME]) ?>

<?php $this->start("page") ?>

<main class="page-header bg-body-login">

        <div class="row">
            <div class="col-11 mx-auto text-center">
                <h2 class="fw-bold text-uppercase mb-4">Tin tức & <span class="text-primary">Bài viết</span></h2>
            </div>
        </div>
    <style>
    /* Hiệu ứng nổi bật khi di chuột vào thẻ bài viết */
    .blog-card-hover:hover {
        transform: translateY(-5px);
        box-shadow: 0 1rem 3rem rgba(0,0,0,.15) !important;
    }
    </style>

    <?php if (isset($articles) && count($articles) > 0) : ?>
        <?php foreach ($articles as $index => $article) : ?>
            
            <?php 
                // Xử lý xen kẽ trái/phải dựa vào số thứ tự bài viết (Chẵn/Lẻ)
                $isEven = ($index % 2 == 0); 
                
                // Nếu là bài viết đầu tiên (index = 0) thì thêm class mt-5
                $containerMargin = ($index == 0) ? 'mt-3' : '';
                $sectionPadding = ($index == 0) ? 'pb-5 pt-3' : 'py-0 pb-5';

                // Cấu hình class cho cột Ảnh và cột Chữ để đảo chiều
                if ($isEven) {
                    $imgOrderClass  = 'order-0 order-md-1 text-end'; // Ảnh bên phải
                    $textOrderClass = '';
                } else {
                    $imgOrderClass  = 'order-md-0 text-start';       // Ảnh bên trái
                    $textOrderClass = '';
                }

                // Xử lý ảnh (nếu không có ảnh thì dùng ảnh mặc định)
                $thumbnail = !empty($article->thumbnail) ? '/img/blog/' . $this->e($article->thumbnail) : '/img/about1.jpg';
            ?>

           <section class="<?= $sectionPadding ?>">
                <div class="container <?= $containerMargin ?>">
                    <div class="row">
                        <div class="col-11 mx-auto">
                            <div class="card card-span mb-4 shadow border-0 rounded-4 overflow-hidden blog-card-hover" style="transition: transform 0.3s ease, box-shadow 0.3s ease;">
                                <div class="card-body py-0">
                                    <div class="row justify-content-center align-items-center">
                                        
                                        <div class="col-md-5 col-xl-6 col-xxl-7 g-0 <?= $imgOrderClass ?>">
                                            <img class="img-fluid fit-cover" src="<?= $thumbnail ?>" alt="<?= $this->e($article->title) ?>" style="object-fit: cover; height: 350px; width: 100%; max-width: 500px;" />
                                        </div>
                                        
                                        <div class="col-md-7 col-xl-6 col-xxl-5 p-4 p-lg-5 <?= $textOrderClass ?>">
                                            <h2 class="card-title mt-xl-2 mb-3 fw-bold text-dark" style="font-size: 1.8rem; line-height: 1.4;">
                                                <?= $this->e($article->title) ?>
                                            </h2>
                                            
                                            <p class="text-muted" style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; line-height: 1.6;">
                                                <?= $this->e($article->summary) ?>
                                            </p>
                                            
                                            <div class="d-grid bottom-0 mt-4 pt-2">
                                                <a class="btn btn-lg btn-primary w-50 mt-xl-3 rounded-pill shadow-sm" href="/bookstore/public/blog/detail/<?= $article->article_id ?>">
                                                    XEM CHI TIẾT <i class="fas fa-arrow-right ms-2" style="font-size: 0.9em;"></i>
                                                </a>
                                            </div>
                                            
                                            <div class="mt-4 pt-2 text-secondary fw-semibold" style="font-size: 0.85rem;">
                                                <i class="far fa-calendar-alt me-2 text-primary"></i> <?= date('d/m/Y', strtotime($article->created_at)) ?>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        <?php endforeach; ?>
    <?php else : ?>
        <div class="container text-center py-5">
            <h4 class="text-muted">Hiện tại chưa có bài viết nào được xuất bản.</h4>
        </div>
    <?php endif; ?>

    <?php if (isset($totalPages) && $totalPages > 1): ?>
        <?php $searchParam = !empty($_GET['search']) ? '&search=' . urlencode($_GET['search']) : ''; ?>
        <section class="py-0 pb-5">
            <div class="container">
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center">
                        
                        <li class="page-item <?= ($currentPage <= 1) ? 'disabled' : '' ?>">
                            <a class="page-link" href="/bookstore/public/blog?page=<?= $currentPage - 1 ?><?= $searchParam ?>">Trang trước</a>
                        </li>
                        
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <li class="page-item <?= ($currentPage == $i) ? 'active' : '' ?>">
                                <a class="page-link" href="/bookstore/public/blog?page=<?= $i ?><?= $searchParam ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>
                        
                        <li class="page-item <?= ($currentPage >= $totalPages) ? 'disabled' : '' ?>">
                            <a class="page-link" href="/bookstore/public/blog?page=<?= $currentPage + 1 ?><?= $searchParam ?>">Trang tiếp</a>
                        </li>
                        
                    </ul>
                </nav>
            </div>
        </section>
    <?php endif; ?>

</main>

<?php $this->stop() ?>