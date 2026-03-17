<?php $this->layout("/layouts/default", ["title" => $article->title . " - " . APPNAME]) ?>

<?php $this->start("page") ?>

<main class="page-header bg-body-login">
    
    <section class="pb-5 pt-5">
        <div class="container mt-5">
            <div class="row">
                <div class="col-11 col-lg-10 mx-auto">
                    <div class="card card-span mb-3 shadow-lg border-0 rounded-4 overflow-hidden">
                        
                        <?php 
                            // Xử lý ảnh bìa (fallback về ảnh mặc định nếu không có)
                            $thumbnail = !empty($article->thumbnail) ? '/img/blog/' . $this->e($article->thumbnail) : '/img/about1.jpg';
                        ?>
                        
                        <img src="<?= $thumbnail ?>" class="card-img-top img-fluid" style="max-height: 500px; object-fit: cover; width: 100%;" alt="<?= $this->e($article->title) ?>">
                        
                        <div class="card-body p-4 p-lg-5">
                            
                            <h1 class="card-title fw-bold mb-3" style="font-size: 2.5rem; line-height: 1.3;">
                                <?= $this->e($article->title) ?>
                            </h1>
                            
                            <div class="text-muted mb-4 pb-4 border-bottom d-flex align-items-center">
                                <span class="me-4">
                                    <i class="fas fa-user-edit text-primary me-2"></i> 
                                    <span class="fw-bold">Admin</span> </span>
                                <span>
                                    <i class="far fa-calendar-alt text-primary me-2"></i> 
                                    <?= date('d/m/Y', strtotime($article->created_at)) ?>
                                </span>
                            </div>

                            <div class="article-content text-dark" style="font-size: 1.15rem; line-height: 1.8; text-align: justify;">
                                <?= $article->content ?>
                            </div>

                            <div class="mt-5 pt-4 border-top text-center text-md-end">
                                <a href="/bookstore/public/blog" class="btn btn-outline-primary px-4 py-2 fw-bold">
                                    <i class="fas fa-arrow-left me-2"></i> QUAY LẠI BLOG
                                </a>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php if (isset($relatedArticles) && count($relatedArticles) > 0): ?>
    <section class="py-5" style="background-color: rgba(0, 0, 0, 0.02);">
        <div class="container">
            <div class="row mb-4">
                <div class="col-11 mx-auto text-center">
                    <h2 class="fw-bold text-uppercase">Có thể bạn <span class="text-primary">quan tâm</span></h2>
                    <div class="b-example-divider mx-auto mt-2" style="width: 80px; height: 3px; background-color: var(--bs-primary); border: none;"></div>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-11 mx-auto">
                    <div class="row g-4">
                        <?php foreach ($relatedArticles as $related): ?>
                            <?php $relThumb = !empty($related->thumbnail) ? '/img/blog/' . $this->e($related->thumbnail) : '/img/about2.jpg'; ?>
                            
                            <div class="col-md-4">
                                <div class="card h-100 shadow-sm border-0 rounded-3 overflow-hidden">
                                    <img src="<?= $relThumb ?>" class="card-img-top" style="height: 220px; object-fit: cover;" alt="...">
                                    <div class="card-body d-flex flex-column p-4">
                                        <h5 class="card-title fw-bold" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                            <?= $this->e($related->title) ?>
                                        </h5>
                                        <p class="text-muted small mb-4">
                                            <i class="far fa-calendar-alt text-primary me-1"></i> <?= date('d/m/Y', strtotime($related->created_at)) ?>
                                        </p>
                                        <a href="/bookstore/public/blog/detail/<?= $related->article_id ?>" class="btn btn-sm btn-primary mt-auto align-self-start">
                                            Đọc tiếp <i class="fas fa-chevron-right ms-1" style="font-size: 0.8em;"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>

</main>

<?php $this->stop() ?>