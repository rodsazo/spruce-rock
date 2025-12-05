<?php
$grid_icons_font_eyebrow = get_field('grid_icons_font_eyebrow');
$grid_icons_font_title = get_field('grid_icons_font_title');
$grid_icons_font = get_field('grid_icons_font');
?>
<section class="grid-icons-font">
    <div class="container">
        <div class="grid-icons-font__content flow">
            <div class="grid-icons-font__eyebrow grid-icons-font__bottom-40 t-caption t-trim">
                <?=$grid_icons_font_eyebrow?>
            </div>
            <div class="grid-icons-font__title t-80 t-trim t-uppercase">
                <?=$grid_icons_font_title?>
            </div>
            <div class="grid-icons-font__subcontent flow">          
                <?php if(empty($grid_icons_font)){return;} ?> 
                <?php 
                $number = 1;
                foreach($grid_icons_font as $grid_icon_font) : 
                    $image_id = $grid_icon_font['grid_icon_font_icon'];
                    $image_url = '';
                    if( $image_id ) {
                        list( $image_url ) = wp_get_attachment_image_src( $image_id, 'huge' );
                    }  
                    $shape_index = ($number % 3 == 1)?1:(($number % 3 == 2) ? 2 : 3);
                ?>
                <div class="grid-icons-font__grid flow flow--24 grid-icons-font__grid--shape-<?=$shape_index?>">
                    <div class="grid-icons-font__grid-icons">               
                        <?php if($image_url): ?>
                        <img class="grid-icons-font__grid-icon" src="<?= esc_url($image_url); ?>">
                        <?php endif; ?>
                    </div>

                    <div class="grid-icons-font__grid-title t-24 t-trim">               
                        <?=$grid_icon_font['grid_icon_font_title']?>
                    </div>

                    <div class="grid-icons-font__grid-text t-16 t-trim">               
                        <?=$grid_icon_font['grid_icon_font_text']?>
                    </div>
                </div>
                <?php 
                $number++;
                endforeach
                ?> 
            </div>
        </div>
    </div>
</section>