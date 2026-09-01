<?php
/** FootballerRank single post template. */
if (!defined('ABSPATH')) { exit; }
get_header();

while (have_posts()) : the_post();
    $author_id   = (int) get_the_author_meta('ID');
    $author_name = 'Adnan Ahmed';
    $categories  = get_the_category();
    $primary_cat = $categories ? $categories[0] : null;
    $word_count  = str_word_count(wp_strip_all_tags((string) get_the_content()));
    $read_time   = max(1, (int) ceil($word_count / 220));
?>
<main id="primary" class="fr-single">
    <header class="fr-post-hero">
        <div class="fr-post-container">
            <?php if ($primary_cat) : ?><a class="fr-post-category" href="<?php echo esc_url(get_category_link($primary_cat)); ?>"><?php echo esc_html($primary_cat->name); ?></a><?php endif; ?>
            <h1><?php the_title(); ?></h1>
            <div class="fr-post-meta">
                <span class="fr-post-author-mini"><?php echo get_avatar($author_id, 42); ?><span>By <a href="<?php echo esc_url(get_author_posts_url($author_id)); ?>"><?php echo esc_html($author_name); ?></a></span></span>
                <span>Updated <?php echo esc_html(get_the_modified_date()); ?></span>
                <span><?php echo esc_html($read_time); ?> min read</span>
            </div>
        </div>
    </header>

    <div class="fr-post-container fr-post-shell">
        <aside class="fr-post-sidebar" aria-label="Article navigation">
            <div class="fr-toc-card">
                <strong>Table of Contents</strong>
                <nav id="fr-post-toc"><span class="fr-toc-loading">Article sections</span></nav>
            </div>
            <div class="fr-sidebar-cta">
                <span class="fr-sidebar-cta__icon">★</span>
                <strong>Follow our rankings</strong>
                <p>Get FootballerRank updates, analysis and research.</p>
                <a href="#fr-newsletter">Stay Updated</a>
            </div>
        </aside>

        <article <?php post_class('fr-post-article'); ?>>
            <?php if (has_post_thumbnail()) : ?>
                <figure class="fr-post-featured"><?php the_post_thumbnail('full', ['loading' => 'eager']); ?></figure>
            <?php endif; ?>
            <div class="fr-post-content entry-content"><?php the_content(); ?></div>

            <section class="fr-author-box" aria-labelledby="fr-author-title">
                <?php echo get_avatar($author_id, 112); ?>
                <div><span>About the author</span><h2 id="fr-author-title"><a href="<?php echo esc_url(get_author_posts_url($author_id)); ?>"><?php echo esc_html($author_name); ?></a></h2><p><?php echo esc_html(get_the_author_meta('description', $author_id)); ?></p><a class="fr-author-link" href="<?php echo esc_url(get_author_posts_url($author_id)); ?>">View author profile &rarr;</a></div>
            </section>

            <?php
            $related_args = [
                'post_type' => 'post', 'posts_per_page' => 3,
                'post__not_in' => [get_the_ID()], 'ignore_sticky_posts' => true,
            ];
            if ($primary_cat) { $related_args['cat'] = $primary_cat->term_id; }
            $related = new WP_Query($related_args);
            if ($related->have_posts()) :
            ?>
            <section class="fr-related" aria-labelledby="fr-related-title">
                <h2 id="fr-related-title">Related Posts</h2>
                <div class="fr-related-grid">
                    <?php while ($related->have_posts()) : $related->the_post(); ?>
                    <a class="fr-related-card" href="<?php the_permalink(); ?>">
                        <span class="fr-related-card__media"><?php if (has_post_thumbnail()) { the_post_thumbnail('medium_large'); } else { echo '<b>FR</b>'; } ?></span>
                        <span class="fr-related-card__body"><small><?php echo esc_html(get_the_date()); ?></small><strong><?php the_title(); ?></strong></span>
                    </a>
                    <?php endwhile; ?>
                </div>
            </section>
            <?php wp_reset_postdata(); endif; ?>
        </article>
    </div>
</main>
<?php endwhile; get_footer();
