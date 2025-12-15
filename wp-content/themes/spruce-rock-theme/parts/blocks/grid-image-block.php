<?php
$grid_image_eyebrow_text = get_field('grid_image_eyebrow_text');
$grid_image_title = get_field('grid_image_title');
$grid_image_text = get_field('grid_image_text');
$grid_image_position = get_field('grid_image_position');
$template_buttons = get_field('template_buttons');
$image_id = get_field('grid_image_main_image');
$image_url = '';
if( $image_id ) {
    list( $image_url ) = wp_get_attachment_image_src( $image_id, 'huge' );
}else{
    $grid_image_title = preg_replace(
        '/(<br\s*\/?>)([^<]+)$/i',
        '$1<span class="last-text">$2</span>',
        $grid_image_title
    );
}
?>
<section class="grid-image">
    <div class="container">
        <?php if($grid_image_eyebrow_text && !$image_url): ?>
        <div class="grid-image__right-eyebrow bottom-40 t-caption t-trim  | intersect fadeIn">
            <?=$grid_image_eyebrow_text?>
        </div>
        <?php endif; ?>
        <div class="grid-image__content <?= (!$image_url)?'grid-image__content--top':'' ?>  | intersect fadeIn">
            <?php if($image_url): ?>
            <div class="grid-image__left <?= ($grid_image_position == 'right')?'':'grid-image__left--change' ?>  | intersect fadeIn">
                <img class="grid-image__left-image" src="<?= esc_url($image_url); ?>">
            </div>
            <?php else: ?>
            <div class="grid-image__right-text <?= ($grid_image_position == 'right')?'':'grid-image__left--change' ?> flow t-18 t-trim | intersect fadeIn">
                <?=$grid_image_text?>
            </div>
            <?php endif; ?>
            <div class="grid-image__right flow">
                <?php if($grid_image_eyebrow_text && $image_url): ?>
                <div class="grid-image__right-eyebrow t-caption t-trim | intersect fadeIn">
                    <?=$grid_image_eyebrow_text?>
                </div>
                <?php endif; ?>
                <h2 class="grid-image__right-title <?= ($grid_image_eyebrow_text && $image_url)?'t-96':'t-96-56' ?> t-trim t-uppercase | intersect fadeIn">
                    <?=$grid_image_title?>
                </h2>
                <?php if($image_url): ?>
                <div class="grid-image__right-text flow t-18 t-trim | intersect fadeIn">
                    <?=$grid_image_text?>
                </div>
                <?php endif; ?>
                <?php if($template_buttons){
                    the_buttons($template_buttons);
                }?>
            </div>
        </div>
    </div>
</section>