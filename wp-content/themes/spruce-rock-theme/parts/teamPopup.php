<?php
$positions = get_field('team_position');
?>
<div class="carousel__wrapper"></div>
<div class="carousel hide  container--1106">
<div class="carousel__content">
<section class="slides">
<?php
if ( $positions && is_array($positions) ) {
    foreach ( $positions as $index => $position ) {
        $imageId = get_post_thumbnail_id($position->ID);
        $teamTitle = get_the_title($position->ID);
        $partes = explode(" ", trim($teamTitle));
        $firstName = $partes[0];
        $teamRol = get_field('team_rol',$position->ID);
        $team_bio = get_field('team_bio',$position->ID);
        $team_linkedin = get_field('team_linkedin',$position->ID);
        $imageUrl = wp_get_attachment_url($imageId);
        $idAnterior=0;
        $idPosterior=0;
        $nameAnterior="";
        $namePosterior="";

        // Anterior
        $idAnterior = $positions[$index - 1] ?? $positions[(count($positions)-1)];
        // Siguiente
        $idPosterior = $positions[$index + 1] ?? $positions[0];
        if ( $idAnterior ) {
            $nameAnterior = get_the_title( $idAnterior->ID );
        } else {
            $nameAnterior="";
        }

        if ( $idPosterior ) {
            $namePosterior = get_the_title( $idPosterior->ID );
        } else {
            $namePosterior="";
        }
?>
<div class="slide">
<section class="teamPopup__center">
    <div class="teamPopup__subContent">        
        <div class="teamPopup__subContentRight">
            <div class="flow flow--24">
                <div class="flow flow--24">                                        
                    <h2 class="teamPopup__subContentTitleSlogan flow flow--16">
                        <div class="teamPopup__subContentTitle t-40_2 t-trim c-900">
                            <?= $teamTitle; ?>
                        </div>
                    </h2>
                    <h3 class="teamPopup__subContentRol t-18 t-trim">
                        <?= $teamRol; ?>
                    </h3>
                    <div class="teamPopup__subContentDescription c-500">
                        <div class="wysiwyg">
                            <?= $team_bio; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="teamPopup__subContentLeft">
            <div class="teamPopup__subButton">
                <button class="x-btn" aria-label="Cerrar">
                    <svg width="10" height="10" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M1.0635 9.648L0 8.5845L3.773 4.8115L0 1.0635L1.0635 0L4.8365 3.773L8.5845 0L9.648 1.0635L5.875 4.8115L9.648 8.5845L8.5845 9.648L4.8365 5.875L1.0635 9.648Z" fill="#141A33"/>
                    </svg>
                </button>
            </div>
            <div class="teamPopup__subContentImage">
                <img class="teamPopup__image" src="<?= esc_url($imageUrl); ?>">
            </div>
            <?php if($team_linkedin) :?>
            <a href="<?= esc_url($team_linkedin); ?>" class="btn btn--primary t-16 t-trim" style="font-weight: 500;">  
                <svg width="16" height="15" viewBox="0 0 16 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M3.33337 1.66742C3.33307 2.34601 2.92137 2.95666 2.29242 3.21142C1.66347 3.46619 0.942867 3.31419 0.470392 2.82711C-0.00208338 2.34001 -0.132067 1.61511 0.141725 0.994205C0.415525 0.373305 1.03842 -0.0196034 1.71671 0.00075496C2.6176 0.0277966 3.33378 0.766122 3.33337 1.66742ZM3.38337 4.56742H0.0500416V15.0007H3.38337V4.56742ZM8.65006 4.56742H5.33337V15.0007H8.61672V9.52573C8.61672 6.47573 12.5917 6.1924 12.5917 9.52573V15.0007H15.8834V8.3924C15.8834 3.25075 10.0001 3.44242 8.61672 5.9674L8.65006 4.56742Z" fill="#0A1029"/>
                </svg>
          
                Connect with <?=$firstName?>
            </a>
            <?php endif;?>
        </div>
    </div>
</section>
</div>
<?php }}; ?>
</section>
</div>
</div>
