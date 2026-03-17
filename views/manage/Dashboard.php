<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard - Admin Bookworms Store</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="/img/favicon.jpg" rel="shortcut icon">
    
    <script src="https://code.jquery.com/jquery-3.6.4.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css">
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

    <link href="/css/dashboard.css" rel="stylesheet">
    <link href="/css/style.css" rel="stylesheet">
</head>

<body style="background-color: #f4f6f9;">

    <header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
        <a href="home" class="navbar-brand col-md-3 col-lg-2 me-0 px-3 fs-6 ">
            <h5 class="m-0 display-4 fs-5 text-secondary fw-bold"><span class="text-primary fs-5 fw-bold">BOOK</span>worm</h5>
        </a>
        <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="w-100 boder-bottom p-1">
            <h3 class="fs-4 px-2 text-white text-center text-uppercase pt-2">Báo Cáo Tổng Quan</h3>
        </div>
        <div class="navbar-nav">
            <div class="nav-item text-nowrap">
                <a class="nav-link px-3" href="logout">Đăng xuất</a>
            </div>
        </div>
    </header>

    <div class="container-fluid">
        <div class="row">
            <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-white sidebar collapse shadow-sm">
                <div class="position-sticky pt-3 sidebar-sticky">
                    <ul class="nav flex-column">
                        <li class="nav-item"><a class="nav-link active" href="home"><i class="fas fa-home"></i> Trang Chủ</a></li>
                        <li class="nav-item"><a class="nav-link" href="manageProduct"><i class="fa fa-book"></i> Sản Phẩm</a></li>
                        <li class="nav-item"><a class="nav-link" href="manageBill"><i class="fa fa-shopping-cart"></i> Đơn Hàng</a></li>
                        <li class="nav-item"><a class="nav-link" href="users"><i class="fas fa-user-friends"></i> Người Dùng</a></li>
                        <li class="nav-item"><a class="nav-link" href="manageArticles"><i class="fas fa-newspaper"></i> Bài Viết</a></li>
                        <li class="nav-item">
                            <a class="nav-link" href="dashboard">
                                <i class="fas fa-chart-line"></i>
                                Dashboard
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-4">
                
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-4 border-bottom">
                    <form class="d-flex align-items-center gap-2" method="GET" action="dashboard">
                        <select name="filter_type" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="today" <?= ($filterType ?? '') == 'today' ? 'selected' : '' ?>>Hôm nay</option>
                            <option value="month" <?= ($filterType ?? '') == 'month' ? 'selected' : '' ?>>Tháng này</option>
                            <option value="quarter" <?= ($filterType ?? '') == 'quarter' ? 'selected' : '' ?>>Quý này</option>
                            <option value="year" <?= ($filterType ?? '') == 'year' ? 'selected' : '' ?>>Năm nay</option>
                        </select>
                        <noscript><button type="submit" class="btn btn-sm btn-primary px-3"><i class="fas fa-filter"></i> Lọc</button></noscript>
                    </form>

                    <button id="btnExportPDF" class="btn btn-sm btn-danger fw-bold shadow-sm">
                        <i class="fas fa-file-pdf"></i> Xuất Báo Cáo PDF
                    </button>
                </div>

                <div id="reportContainer" class="p-2 bg-white rounded-3">
                    
                    <h3 class="text-center mb-4 text-uppercase fw-bold text-dark d-none d-print-block print-title">BÁO CÁO KẾT QUẢ KINH DOANH</h3>

                    <div class="row g-3 mb-4">
                        <div class="col-md-3">
                            <div class="card text-white bg-primary shadow-sm border-0 h-100">
                                <div class="card-body">
                                    <h6 class="card-title"><i class="fas fa-coins"></i> TỔNG DOANH THU</h6>
                                    <h3 class="fw-bold mb-0"><?= number_format($totalRevenue ?? 0, 0, ',', '.') ?>đ</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-white bg-success shadow-sm border-0 h-100">
                                <div class="card-body">
                                    <h6 class="card-title"><i class="fas fa-shopping-cart"></i> ĐƠN HÀNG</h6>
                                    <h3 class="fw-bold mb-0"><?= number_format($totalOrders ?? 0, 0, ',', '.') ?></h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-white bg-warning shadow-sm border-0 h-100">
                                <div class="card-body">
                                    <h6 class="card-title"><i class="fas fa-book"></i> SÁCH TRONG KHO</h6>
                                    <h3 class="fw-bold mb-0"><?= number_format($totalProducts ?? 0, 0, ',', '.') ?></h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-white bg-danger shadow-sm border-0 h-100">
                                <div class="card-body">
                                    <h6 class="card-title"><i class="fas fa-users"></i> KHÁCH HÀNG</h6>
                                    <h3 class="fw-bold mb-0"><?= number_format($totalUsers ?? 0, 0, ',', '.') ?></h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4 html2pdf__page-break-avoid">
                        <div class="col-12">
                            <div class="card shadow-sm border-0">
                                <div class="card-header bg-white fw-bold"><i class="fas fa-chart-line text-primary"></i> 1. Biểu đồ Doanh thu (Theo thời gian)</div>
                                <div class="card-body">
                                    <canvas id="revenueLineChart" height="80"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4 g-3 html2pdf__page-break-avoid">
                        <div class="col-md-6">
                            <div class="card shadow-sm border-0 h-100">
                                <div class="card-header bg-white fw-bold"><i class="fas fa-trophy text-warning"></i> 2. Top 5 Sản phẩm bán chạy nhất</div>
                                <div class="card-body">
                                    <canvas id="topBooksBarChart" height="200"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card shadow-sm border-0 h-100">
                                <div class="card-header bg-white fw-bold"><i class="fas fa-chart-bar text-success"></i> 3. Doanh số theo Loại sách</div>
                                <div class="card-body">
                                    <canvas id="categoryBarChart" height="200"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4 g-3 html2pdf__page-break-avoid">
                        <div class="col-md-6">
                            <div class="card shadow-sm border-0 h-100">
                                <div class="card-header bg-white fw-bold"><i class="fas fa-chart-pie text-info"></i> 4. Trạng thái Đơn hàng</div>
                                <div class="card-body">
                                    <canvas id="statusPieChart"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card shadow-sm border-0 h-100">
                                <div class="card-header bg-white fw-bold"><i class="fas fa-wallet text-secondary"></i> 5. Phương thức thanh toán (Tham khảo)</div>
                                <div class="card-body">
                                    <canvas id="paymentDoughnutChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                </div> </main>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            
            // --------------------------------------------------------
            // CHỨC NĂNG XUẤT PDF
            // --------------------------------------------------------
            $('#btnExportPDF').click(function () {
                // Thêm tiêu đề tạm thời cho PDF
                $('.print-title').removeClass('d-none');
                
                var element = document.getElementById('reportContainer');
                var opt = {
                    margin:       [0.5, 0.5, 0.5, 0.5], // Tránh cắt trên dưới
                    filename:     'Bao_Cao_Bookworm_' + new Date().getTime() + '.pdf',
                    image:        { type: 'jpeg', quality: 0.98 },
                    html2canvas:  { scale: 2, useCORS: true, backgroundColor: '#ffffff' },
                    jsPDF:        { unit: 'px', format: [element.offsetWidth, element.offsetHeight + 100], orientation: 'portrait', hotfixes: ["px_scaling"] }, 
                    pagebreak:    { mode: 'avoid-all' }
                };

                // Bắt đầu xuất PDF với chiều cao tùy chỉnh của phần tử container để nó nằm hoàn toàn trên 1 trang 
                html2pdf().set(opt).from(element).save().then(function(){
                    // Xóa tiêu đề sau khi xuất xong
                    $('.print-title').addClass('d-none');
                });
            });

            // --------------------------------------------------------
            // CẤU HÌNH BIỂU ĐỒ BẰNG CHART.JS (Phiên bản 2.9.4)
            // --------------------------------------------------------

            // Dữ liệu PHP truyền sang
            var chartLabels = <?= $chartLabels ?? '[]' ?>;
            var chartDataArray = <?= $chartData ?? '[]' ?>;

            var topBookLabels = <?= $topBookLabels ?? '[]' ?>;
            var topBookData = <?= $topBookData ?? '[]' ?>;

            var categoryLabels = <?= $categoryLabels ?? '[]' ?>;
            var categoryData = <?= $categoryData ?? '[]' ?>;

            var statusData = <?= $statusData ?? '[0,0,0,0,0]' ?>; // fallback tránh rỗng
            var paymentData = <?= $paymentData ?? '[0,0]' ?>; // fallback tránh rỗng

            // 1. Biểu đồ Đường (Doanh thu)
            if(chartLabels.length > 0) {
                var ctxLine = document.getElementById('revenueLineChart').getContext('2d');
                new Chart(ctxLine, {
                    type: 'line',
                    data: {
                        labels: chartLabels,
                        datasets: [{
                            label: 'Doanh thu (VNĐ)',
                            data: chartDataArray,
                            borderColor: '#0d6efd',
                            backgroundColor: 'rgba(13, 110, 253, 0.1)',
                            borderWidth: 2,
                            fill: true
                        }]
                    },
                    options: { 
                        responsive: true,
                        tooltips: {
                            callbacks: {
                                label: function(tooltipItem, data) {
                                    return tooltipItem.yLabel.toLocaleString('vi-VN') + ' VNĐ';
                                }
                            }
                        },
                        scales: {
                            yAxes: [{
                                ticks: {
                                    callback: function(value, index, values) {
                                        if(parseInt(value) >= 1000){
                                            return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                                        } else {
                                            return value;
                                        }
                                    }
                                }
                            }]
                        }
                    }
                });
            }

            // 2. Biểu đồ Cột ngang (Sách bán chạy)
            if(topBookLabels.length > 0) {
                var ctxHBar = document.getElementById('topBooksBarChart').getContext('2d');
                new Chart(ctxHBar, {
                    type: 'horizontalBar',
                    data: {
                        labels: topBookLabels,
                        datasets: [{
                            label: 'Số lượng đã bán',
                            data: topBookData,
                            backgroundColor: '#ffc107'
                        }]
                    },
                    options: { 
                        responsive: true,
                        scales: { xAxes: [{ ticks: { beginAtZero: true } }] }
                    }
                });
            }

            // 3. Biểu đồ Cột dọc (Danh mục)
            if(categoryLabels.length > 0) {
                var ctxBar = document.getElementById('categoryBarChart').getContext('2d');
                new Chart(ctxBar, {
                    type: 'bar',
                    data: {
                        labels: categoryLabels,
                        datasets: [{
                            label: 'Số lượng đã bán',
                            data: categoryData,
                            backgroundColor: '#198754'
                        }]
                    },
                    options: { 
                        responsive: true,
                        scales: { yAxes: [{ ticks: { beginAtZero: true } }] }
                    }
                });
            }

            // 4. Biểu đồ Tròn (Trạng thái đơn)
            // Thay vì kiểm tra statusData.length, biểu đồ vẫn vẽ kể cả mảng là mảng các số 0 để không bị trống
            var isStatusEmpty = statusData.reduce((a, b) => a + b, 0) === 0;
            var ctxPie = document.getElementById('statusPieChart').getContext('2d');
            new Chart(ctxPie, {
                type: 'pie',
                data: {
                    labels: isStatusEmpty ? ['Chưa có đơn hàng trong khoảng thời gian này'] : ['Hoàn Thành', 'Đang Giao', 'Chờ Duyệt', 'Đã Hủy', 'Khác'],
                    datasets: [{
                        data: isStatusEmpty ? [1] : statusData,
                        backgroundColor: isStatusEmpty ? ['#e9ecef'] : ['#198754', '#0dcaf0', '#ffc107', '#dc3545', '#6c757d']
                    }]
                },
                options: { 
                    responsive: true, 
                    legend: { position: 'right' },
                    tooltips: {
                        enabled: !isStatusEmpty
                    }
                }
            });

            // 5. Biểu đồ Donut (Thanh toán)
            var isPaymentEmpty = paymentData.reduce((a, b) => a + b, 0) === 0;
            var ctxDoughnut = document.getElementById('paymentDoughnutChart').getContext('2d');
            new Chart(ctxDoughnut, {
                type: 'doughnut',
                data: {
                    labels: isPaymentEmpty ? ['Chưa có đơn hàng trong khoảng thời gian này'] : ['Chưa Thanh Toán (COD)', 'Đã Thanh Toán (Bank)'],
                    datasets: [{
                        data: isPaymentEmpty ? [1] : paymentData,
                        backgroundColor: isPaymentEmpty ? ['#e9ecef'] : ['#6c757d', '#0d6efd']
                    }]
                },
                options: { 
                    responsive: true, 
                    legend: { position: 'right' }, 
                    cutoutPercentage: 60,
                    tooltips: {
                        enabled: !isPaymentEmpty
                    }
                }
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>