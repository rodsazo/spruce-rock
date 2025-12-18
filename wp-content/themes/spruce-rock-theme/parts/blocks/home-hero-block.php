<?php
$home_hero_eyebrow_text = get_field('home_hero_eyebrow_text');
$home_hero_title = get_field('home_hero_title');
$home_hero_text = get_field('home_hero_text');
$home_hero_link = get_field('home_hero_link');
$image_id = get_field('home_hero_main_image');
$image_url = '';
if( $image_id ) {
    list( $image_url ) = wp_get_attachment_image_src( $image_id, 'huge' );
}
?>
<section class="home-hero">
    <div class="container">
        <div class="home-hero__content">
            <div class="home-hero__content-left flow flow--64">
                <div class="home-hero__content-left-header flow flow--24">
                    <div class="home-hero__content-left-eyebrow t-40 t-trim t-uppercase | intersect fadeIn">
                        <?=$home_hero_eyebrow_text?>
                    </div>
                    <h1 class="home-hero__content-left-title t-124 t-trim t-uppercase | intersect fadeIn">
                        <?=$home_hero_title?>
                    </h1>
                </div>
                <div class="home-hero__content-left-body flow">
                    <div class="home-hero__content-left-text c-800 t-18 t-trim | intersect fadeIn">
                        <?=$home_hero_text?>
                    </div>
                    <div class="home-hero__content-left-link | intersect fadeIn">
                        <?php if(isset($home_hero_link['url'])) : ?>
                        <a class="btn-first-button" href="<?= $home_hero_link['url'] ?: '#'; ?>" target="<?= $home_hero_link['target'] ?: '_self'; ?>">
                            <?= $home_hero_link['title']; ?>
                        </a>
                        <?php endif ?>
                    </div>
                </div>
            </div>
            <div class="home-hero__content-right | intersect fadeIn">
                <?php if($image_url): ?>
                <img class="home-hero__content-right-main-image" src="<?= esc_url($image_url); ?>">
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>