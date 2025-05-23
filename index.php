<?php
include "./include/config.php";
include "./include/db.php";

$query = "SELECT * FROM categories";
$categories = $db->query($query);

// echo "<pre>";
// print_r($categories->fetchAll());


?>

<!DOCTYPE html>
<html dir="rtl" lang="fa">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>cafe.orderify</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous" />

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />


    <style>
        @font-face {
            font-family: "Vazir";
            src: url("../fonts/Vazir.eot");
            /* IE9 Compat Modes */
            src: url("../fonts/Vazir.eot?#iefix") format("embedded-opentype"),
                url("../fonts/Vazir.woff2") format("woff2"),
                url("../fonts/Vazir.woff") format("woff"),
                url("../fonts/Vazir.ttf") format("truetype");
            /* Safari, Android, iOS */
        }

        body {
            font-family: "Vazir", sans-serif !important;

        }

        .glass-bg {
            background: rgba(255, 255, 255, 0.3) !important;
            backdrop-filter: blur(10px) !important;
            -webkit-backdrop-filter: blur(10px) !important;
            border: 2px solid #B69F7C !important;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1) !important;
        }

        .single-glass-bg {
            background: rgba(255, 255, 255, 0.1) !important;
            backdrop-filter: blur(10px) !important;
            -webkit-backdrop-filter: blur(10px) !important;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1) !important;
        }

        .bg-main {
            background-color: #3F545A;
            background: url('./uploads/sky-centennial-normal.jpg') fixed;

        }
    </style>
</head>

<body>
    <main class="container py-3 pb-0 bg-main">
        <header class=" row align-items-center justify-content-center gap-2" style="top: 0.5rem;">
            <div class="col-8 text-center p-3" style="background:#A89170; border-radius: 4rem;">
                <a href="" class="fs-4 text-black text-decoration-none">کافه ...</a>
            </div>
			<div class="col-2 rounded-circle align-items-center text-center p-3 d-none" style="background:#A89170;">
                <i class="bi bi-search fs-3 "></i>
            </div>
            
        </header>




        <div class="mt-5 row text-center text-black gap-3 justify-content-evenly glass-bg rounded-5 p-3 m-2 ">
            <?php foreach ($categories as $category): ?>
                <a class="col-4 align-items-center text-decoration-none text-dark rounded-4 single-glass-bg"
                    onclick="window.location.href='main.php#category-<?= $category['id'] ?>'"
                    style="background-color: #C8B59B; cursor:pointer;"
                    id="category-<?= htmlspecialchars($category['id']) ?>" data-category-id="<?= $category['id'] ?>">
                    <img src="./uploads/categories/<?= htmlspecialchars($category['image']) ?>" loading="lazy"
                        alt="<?= htmlspecialchars($category['alt']) ?>" class="mt-2 object-fit-contain"
                        style="height: 3rem;">
                    <h6 class="mt-2  fw-bold" style="font-size:.9rem;"><?= htmlspecialchars($category["title"]) ?></h6>
                    <h6 class="mt-2  fw-bold" style="font-size:.8rem;"><?= htmlspecialchars($category["alt"]) ?></h6>
                </a>
            <?php endforeach ?>
        </div>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <!-- Footer Section -->
        <footer class="text-center pt-4 my-md-5 pt-md-5 border-top" style="min-height: 100%; margin-top:auto;">
            <br>
            <div class="row flex-column" style="color:#C8B59B;">
                <div>
                    <p class="">
                        کلیه حقوق محتوا این سایت متعلق به کافه ...
                        میباشد
                    </p>
                </div>
                <div>
                    <a href="#"><i class="bi bi-telegram fs-3 text-secondary ms-2"></i></a>
                    <a href="#"><i class="bi bi-whatsapp fs-3 text-secondary ms-2"></i></a>
                    <a href="#"><i class="bi bi-instagram fs-3 text-secondary"></i></a>
                </div>
                <div class=" d-flex rounded-top-5 justify-content-between fw-medium p-3 pb-0 fs-7 text-dark"
                    style="background:#9C8966;">
                    <p class="font-monospace fw-bold">09301306552</p>
                    <p>Develope By A.H MohseniFar</p>
                </div>
            </div>
        </footer>
        <?php include "./include/layout/footer.php"; ?>
    </main>