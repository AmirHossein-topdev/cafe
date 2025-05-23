<?php
// گرفتن تمام اسلایدها از جدول slides
$slides = $db->query("SELECT * FROM slides");
?>

<!-- Slider Section -->
<!-- Swiper -->

<div class="swiper mySwiper border-4 border-top border-bottom border-primary rounded-5">
    <div class="swiper-wrapper">
        <?php foreach ($slides as $slide) : ?>
            <div class="swiper-slide " style="height:10rem;">
                <img src="./uploads/slides/<?= htmlspecialchars($slide['image']) ?>
                " class="d-block w-100 rounded-4" alt="slide-img">
            </div>
        <?php endforeach ?>
    </div>
</div>