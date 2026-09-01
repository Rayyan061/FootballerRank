<?php
/** FootballerRank premium author archive. */
if (!defined('ABSPATH')) { exit; }
get_header();

$author      = get_queried_object();
$author_id   = isset($author->ID) ? (int) $author->ID : 0;
$display     = $author_id ? get_the_author_meta('display_name', $author_id) : '';
$author_name = ($display && !is_email($display)) ? $display : 'Adnan Ahmed';
$bio         = $author_id ? trim((string) get_the_author_meta('description', $author_id)) : '';
$website     = $author_id ? get_the_author_meta('user_url', $author_id) : '';
$post_count  = $author_id ? count_user_posts($author_id, 'post', true) : 0;

$latest_query = new WP_Query([
    'post_type' => 'post', 'author' => $author_id, 'posts_per_page' => 1,
    'post_status' => 'publish', 'orderby' => 'modified', 'order' => 'DESC',
]);
$latest_update = $latest_query->have_posts() ? get_the_modified_date('', $latest_query->posts[0]) : '';
wp_reset_postdata();

$category_ids = [];
$all_posts = get_posts([
    'post_type' => 'post', 'author' => $author_id, 'posts_per_page' => -1,
    'post_status' => 'publish', 'fields' => 'ids',
]);
foreach ($all_posts as $post_id) {
    $category_ids = array_merge($category_ids, wp_get_post_categories($post_id));
}
$category_ids = array_values(array_unique(array_map('intval', $category_ids)));
?>
<main class="fr-author-page">
    <section class="fr-author-hero">
        <div class="fr-author-container fr-author-hero__grid">
            <div class="fr-author-portrait"><?php echo get_avatar($author_id, 240, '', $author_name); ?><span class="fr-author-verified" aria-label="Verified FootballerRank author">FR</span></div>
            <div class="fr-author-hero__copy">
                <p class="fr-author-eyebrow">FootballerRank Editorial Team</p>
                <h1><?php echo esc_html($author_name); ?></h1>
                <p class="fr-author-role">Football Researcher &amp; Rankings Analyst</p>
                <?php if ($bio) : ?><p class="fr-author-summary"><?php echo esc_html($bio); ?></p><?php endif; ?>
                <div class="fr-author-actions">
                    <a href="#author-articles">View articles &darr;</a>
                    <?php if ($website) : ?><a class="fr-author-action--outline" href="<?php echo esc_url($website); ?>" rel="me">Website &rarr;</a><?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <section class="fr-author-stats" aria-label="Author information">
        <div class="fr-author-container fr-author-stats__grid">
            <div><strong><?php echo esc_html(number_format_i18n($post_count)); ?></strong><span>Published Articles</span></div>
            <div><strong><?php echo esc_html(number_format_i18n(count($category_ids))); ?></strong><span>Research Topics</span></div>
            <div><strong><?php echo $latest_update ? esc_html($latest_update) : '&mdash;'; ?></strong><span>Latest Update</span></div>
            <div><strong>Independent</strong><span>Editorial Analysis</span></div>
        </div>
    </section>

    <div class="fr-author-container fr-author-body">
        <aside class="fr-author-sidebar">
            <section><h2>About</h2><?php if ($bio) : ?><p><?php echo esc_html($bio); ?></p><?php else : ?><p>Add the approved author biography in Users &rarr; Profile &rarr; Biographical Info.</p><?php endif; ?></section>
            <?php if ($category_ids) : ?><section><h2>Editorial Focus</h2><div class="fr-author-topics"><?php foreach ($category_ids as $category_id) { $term = get_category($category_id); if ($term && !is_wp_error($term)) { echo '<a href="' . esc_url(get_category_link($term)) . '">' . esc_html($term->name) . '</a>'; } } ?></div></section><?php endif; ?>
            <section class="fr-author-standards"><h2>Editorial Standards</h2><p>Review FootballerRank's methodology, sourcing and corrections standards.</p><a href="<?php echo esc_url(home_url('/methodology/')); ?>">Read methodology &rarr;</a></section>
        </aside>

        <section id="author-articles" class="fr-author-articles">
            <header><p class="fr-author-eyebrow">Latest Work</p><h2>Articles by <?php echo esc_html($author_name); ?></h2></header>
            <?php if (have_posts()) : ?>
                <div class="fr-author-post-grid">
                    <?php while (have_posts()) : the_post(); ?>
                    <article class="fr-author-post-card">
                        <a class="fr-author-post-card__media" href="<?php the_permalink(); ?>"><?php if (has_post_thumbnail()) { the_post_thumbnail('medium_large'); } else { echo '<span>FR</span>'; } ?></a>
                        <div class="fr-author-post-card__body">
                            <?php $cats = get_the_category(); if ($cats) : ?><a class="fr-author-post-card__category" href="<?php echo esc_url(get_category_link($cats[0])); ?>"><?php echo esc_html($cats[0]->name); ?></a><?php endif; ?>
                            <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                            <p><?php echo esc_html(wp_trim_words(get_the_excerpt(), 24)); ?></p>
                            <div><span>Updated <?php echo esc_html(get_the_modified_date()); ?></span><a href="<?php the_permalink(); ?>">Read article &rarr;</a></div>
                        </div>
                    </article>
                    <?php endwhile; ?>
                </div>
                <div class="fr-author-pagination"><?php the_posts_pagination(['mid_size' => 1, 'prev_text' => '&larr; Previous', 'next_text' => 'Next &rarr;']); ?></div>
            <?php else : ?><div class="fr-author-empty"><h3>No published articles yet</h3><p>Published research by this author will appear here.</p></div><?php endif; ?>
        </section>
    </div>
</main>
<?php get_footer();
