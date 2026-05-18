<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hệ Thống Đang Bảo Trì | Bookworm Store</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            color: #f8fafc;
            font-family: 'Inter', sans-serif;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }
        .maintenance-container {
            max-width: 600px;
            text-align: center;
            padding: 40px;
            background: rgba(30, 41, 59, 0.7);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 24px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3);
            animation: fadeIn 1s ease-out;
        }
        .icon-box {
            font-size: 5rem;
            color: #38bdf8;
            margin-bottom: 25px;
            display: inline-block;
            position: relative;
            animation: pulse 2s infinite;
        }
        .gear-1 {
            animation: spin 6s infinite linear;
        }
        .gear-2 {
            font-size: 2.5rem;
            position: absolute;
            bottom: -5px;
            right: -15px;
            color: #0ea5e9;
            animation: spin-reverse 4s infinite linear;
        }
        h1 {
            font-size: 2.2rem;
            font-weight: 800;
            letter-spacing: -0.5px;
            margin-bottom: 15px;
            background: linear-gradient(to right, #38bdf8, #818cf8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        p {
            color: #94a3b8;
            font-size: 1.1rem;
            line-height: 1.6;
            margin-bottom: 30px;
        }
        .btn-staff {
            background: linear-gradient(to right, #4f46e5, #6366f1);
            color: #fff;
            border: none;
            padding: 12px 30px;
            font-weight: 600;
            border-radius: 12px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(99, 102, 241, 0.4);
        }
        .btn-staff:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(99, 102, 241, 0.6);
            color: #fff;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        @keyframes spin-reverse {
            from { transform: rotate(360deg); }
            to { transform: rotate(0deg); }
        }
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
    </style>
</head>
<body>
    <div class="maintenance-container">
        <div class="icon-box">
            <i class="fas fa-cog gear-1"></i>
            <i class="fas fa-cog gear-2"></i>
        </div>
        <h1>Hệ Thống Đang Bảo Trì</h1>
        <p>
            Bookworm Store hiện đang được nâng cấp để mang lại trải nghiệm tốt nhất cho bạn. 
            Chúng tôi sẽ quay lại trong giây lát. Xin lỗi quý khách vì sự bất tiện này!
        </p>
        <div class="mt-4">
            <a href="/bookstore/public/login" class="btn btn-staff px-4 py-2">
                <i class="fas fa-user-shield me-2"></i> Đăng nhập quản trị viên
            </a>
        </div>
    </div>
</body>
</html>
