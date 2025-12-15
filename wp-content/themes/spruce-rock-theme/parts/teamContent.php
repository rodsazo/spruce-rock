<?php
$positions = get_field('team_position');
?>
 <section class="teamContent">
    <div class="container">
        <div class="teamContent__container">
            <?php if ( $positions && is_array($positions) ) : ?>
                <?php foreach ( $positions as $index => $position ) :
                    $imageId = get_post_thumbnail_id($position->ID);
                    $teamTitle = get_the_title($position->ID);
                    $teamRol = get_field('team_rol',$position->ID);
                    $imageUrl = wp_get_attachment_url($imageId);
            ?>
            <div class="teamContent__containerBody">
                <div class="teamContent__imageContent | btnOpen" data-id="<?= $index; ?>" aria-label="Open">
                    <img class="teamContent__image" src="<?= esc_url($imageUrl); ?>">
                </div>
                <div class="teamContent__textContent flow flow--24">
                    <div class="teamContent__textContentNameSlogan">
                        <h3 class="teamContent__textContentName | t-24 t-trim c-900">
                            <span class="t-hoverlink btnOpen" data-id="<?= $index; ?>" aria-label="Open">
                                <?= $teamTitle; ?>
                            </span>
                        </h3>
                    </div>
                    <div class="teamContent__textContentRol | t-18 t-trim c-700">
                        <?= $teamRol; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>