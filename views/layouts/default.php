<!DOCTYPE html>
<html lang="vn">

<head>
    <meta charset="utf-8">
    <title><?=$this->e($title)?></title>
    <script>
        if (window.location.search.includes('zarsrc=') || window.location.search.includes('utm_')) {
            var cleanUrl = window.location.protocol + "//" + window.location.host + window.location.pathname;
            window.history.replaceState({}, document.title, cleanUrl);
        }
    </script>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <base href="/bookstore/public/"> 
    <!-- Favicon -->
    <link href="/img/favicon.jpg" rel="shortcut icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Literata:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">

    <!-- JQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.js" integrity="sha256-a9jBBRygX1Bh5lt8GZjXDzyOB+bWve9EiO7tROUtj/E=" 
    crossorigin="anonymous"></script>

    <!-- Customized Bootstrap Stylesheet -->
    <link href="/css/style.css" rel="stylesheet">

    <?=$this->section("page_specific_css")?>
</head>

<body>
    <?=$this->insert('/layouts/header')?>
    <?=$this->section("page")?>
    <?=$this->insert('/layouts/footer')?>
    
    <script src="/libs/jquery-3.6.0.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
