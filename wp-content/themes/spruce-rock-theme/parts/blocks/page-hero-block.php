<?php
$page_hero_main_title = get_field('page_hero_main_title');
$page_hero_text = get_field('page_hero_text');
?>
<section class="page-hero | intersect fadeIn">
    <div class="container">
        <div class="page-hero__header flow">
            <h1 class="page-hero__title t-124 t-trim t-uppercase | intersect fadeIn">
                <?=$page_hero_main_title?>
            </h1>
            <?php if( $page_hero_text ): ?>
                <div class="page-hero__text | t-18 t-trim | intersect fadeIn">
                    <?= $page_hero_text; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>