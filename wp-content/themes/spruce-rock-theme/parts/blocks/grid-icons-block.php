<?php
$grid_with_icons = get_field('grid_with_icons');
?>
<section class="grid-icons">
    <div class="container">
        <div class="grid-icons__content">
            <?php if(empty($grid_with_icons)){return;} ?>
            <?php
            foreach($grid_with_icons as $grid_with_icon) :
                $image_id = $grid_with_icon['grid_icon_image'];
                $image_url = '';
                if( $image_id ) {
                    list( $image_url ) = wp_get_attachment_image_src( $image_id, 'huge' );
                }
            ?>
            <div class="grid-icons__grid flow flow--24 | intersect fadeIn">
                <div class="grid-icons__grid-icons | intersect fadeIn">
                    <?php if($image_url): ?>
                    <img class="grid-icons__grid-icon" src="<?= esc_url($image_url); ?>">
                    <?php endif; ?>
                </div>
                <div class="grid-icons__grid-title t-24 t-trim | intersect fadeIn">
                    <?=$grid_with_icon['grid_icon_title']?>
                </div>
                <div class="grid-icons__grid-text t-16 t-trim | intersect fadeIn">
                    <?=$grid_with_icon['grid_icon_text']?>
                </div>
            </div>
            <?php
            endforeach
            ?>
        </div>
    </div>
</section>