<?php
// views/errors/403.php
// Được render bởi requirePermission() trong ManagementController/ManageStaffController
$message = $message ?? 'Bạn không có quyền truy cập trang này.';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Không có quyền truy cập | BookStore</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            background: #0f172a;
            color: #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }
        .container {
            text-align: center;
            padding: 2rem;
            max-width: 480px;
        }
        .code {
            font-size: 8rem;
            font-weight: 800;
            background: linear-gradient(135deg, #f97316, #ef4444);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            line-height: 1;
            margin-bottom: 1rem;
        }
        h1 {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 0.75rem;
            color: #f1f5f9;
        }
        p {
            color: #94a3b8;
            margin-bottom: 2rem;
            line-height: 1.6;
        }
        .badge {
            display: inline-block;
            background: #1e293b;
            border: 1px solid #334155;
            border-radius: 6px;
            padding: 0.25rem 0.75rem;
            font-family: monospace;
            font-size: 0.85rem;
            color: #f97316;
            margin-bottom: 2rem;
        }
        .btn {
            display: inline-block;
            background: linear-gradient(135deg, #f97316, #ef4444);
            color: #fff;
            text-decoration: none;
            padding: 0.75rem 2rem;
            border-radius: 8px;
            font-weight: 600;
            transition: opacity 0.2s;
        }
        .btn:hover { opacity: 0.85; }
    </style>
</head>
<body>
    <div class="container">
        <div class="code">403</div>
        <h1>Không có quyền truy cập</h1>
        <p><?= htmlspecialchars($message) ?></p>
        <?php if (!empty($required_permission)): ?>
            <div class="badge">Yêu cầu: <?= htmlspecialchars($required_permission) ?></div><br>
        <?php endif; ?>
        <a href="/bookstore/public/home" class="btn">← Về trang chủ</a>
    </div>
</body>
</html>
