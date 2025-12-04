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
}
?>
<section class="grid-image">
    <div class="container">
        <div class="grid-image__content">
            <?php if($image_url): ?>
            <div class="grid-image__left <?= ($grid_image_position == 'right')?'grid-image__left--change':'' ?>">
                <img class="grid-image__left-image" src="<?= esc_url($image_url); ?>">
            </div>
            <?php endif; ?>
            <div class="grid-image__right flow">
                <?php if($grid_image_eyebrow_text): ?>
                <div class="grid-image__right-eyebrow t-caption t-trim">
                    <?=$grid_image_eyebrow_text?>
                </div>
                <?php endif; ?>
                <h2 class="grid-image__right-title <?= ($grid_image_eyebrow_text)?'t-96':'t-96-56' ?> t-trim t-uppercase">
                    <?=$grid_image_title?>
                </h2>
                <div class="grid-image__right-text flow t-18 t-trim">
                    <?=$grid_image_text?>
                </div>
                <?php if($template_buttons){
                    the_buttons($template_buttons); 
                }?> 
            </div>
        </div>
    </div>
</section>