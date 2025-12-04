<section class="container">
    <?php if(have_rows('stats_content')): ?>
    <div class="stats__wrap">
        <div class="stats">
            <?php while(have_rows('stats_content')):the_row();
            $title = get_sub_field('stats_title');
            $text = get_sub_field('stats_text');
            ?>
            <div class="stats__content flow c-900">
                <div class="stats__title t-112 t-trim">
                    <?php echo $title; ?>
                </div>
                <div class="stats__text t-18 t-trim">
                    <?php echo $text; ?>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
    <?php endif; ?>
</section>