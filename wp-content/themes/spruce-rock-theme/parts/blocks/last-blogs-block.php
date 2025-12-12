<?php
$blog_title = get_field('blog_title');
$blog_text = get_field('blog_text');
$blog_list = get_field('blog_list');
global $post;
?>
<section class="last-blogs last-blogs--header">
    <div class="container">
        <div class="last-blogs__header flow">
            <h1 class="last-blogs__title t-124 t-trim t-uppercase">
                <?=$blog_title?>
            </h1>
            <div class="last-blogs__text | t-18 t-trim">
                <?= $blog_text; ?>
            </div>
        </div>
    </div>
</section>
 <section class="last-blogs">
    <div class="container">
        <div class="last-blogs__container">       
            <?php if ( $blog_list && is_array($blog_list) ) : ?>
                <?php foreach ( $blog_list as $index => $blog ) :
                    $imageId = get_post_thumbnail_id($blog->ID);
                    $blogTitle = get_the_title($blog->ID);
                    $content = get_the_content(null, false, $blog->ID);
                    $imageUrl = wp_get_attachment_url($imageId);
                    $Date = get_the_date('M j, Y', $blog->ID);
                   
            ?>
            <div class="last-blogs__containerBody">                
                <div class="last-blogs__textContent flow flow--24">  
                    <div class="last-blogs__textContent-date | t-16 t-trim c-900">
                        <?= $Date; ?>
                    </div>                  
                    <div class="last-blogs__textContent-content | t-32 t-trim c-900">
                        <?= $content; ?>
                    </div>                    
                </div>
                <div class="last-blogs__imageContent">
                    <img class="last-blogs__image" src="<?= esc_url($imageUrl); ?>">
                </div>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>