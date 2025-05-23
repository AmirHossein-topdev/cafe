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
        .custom-image{
            border-radius: 20px;
        }
        .custom-popup{
            width: 70%;
            color: white;
            background-color: #3F545A !important;
        }
    </style>
</head>

<body>

    <div class="container py-3 pb-0 h-100" style="background-color:#3F545A">
        <header class="d-flex justify-content-between align-items-center pb-3 mb-4 border-bottom "
            style="direction:ltr;">
            <a href="index.php" class="fs-4 fw-medium link-body-emphasis text-decoration-none " style="color:#E2CFC4">
                Cafe Name
            </a>
        </header>