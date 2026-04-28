<?php
/**
 * Timellow Theme
 * 简单、清爽、极简的 Typecho 主题
 * @package Timellow
 * @author 时光沉淀
 * @version 1.0.5
 * @link https://www.timellow.com/
 */
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
$this->need('header.php');
$timellowPosts = timellow_index_posts_source($this);
?>
<main class="site-main">
    <?php if ($timellowPosts->have()): ?>
        <div class="post-list" data-post-list>
            <?php $timellowRenderedPostIds = []; ?>
            <?php while ($timellowPosts->next()): ?>
                <?php
                $postCid = isset($timellowPosts->cid) ? (int) $timellowPosts->cid : 0;
                if ($postCid > 0) {
                    if (isset($timellowRenderedPostIds[$postCid])) {
                        continue;
                    }
                    $timellowRenderedPostIds[$postCid] = true;
                }
                $cover = timellow_post_cover($timellowPosts);
                $category = !empty($timellowPosts->categories) ? $timellowPosts->categories[0] : null;
                $isSticky = timellow_is_sticky_post($timellowPosts);
                ?>
                <article class="post-card<?php if ($isSticky): ?> is-sticky<?php endif; ?>" data-post-cid="<?php echo $postCid; ?>" itemscope itemtype="https://schema.org/BlogPosting">
                    <a class="post-thumb-link" href="<?php $timellowPosts->permalink(); ?>" aria-label="<?php $timellowPosts->title(); ?>">
                        <?php if ($cover !== ''): ?>
                            <img class="post-thumb" src="<?php echo htmlspecialchars($cover, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php $timellowPosts->title(); ?>" loading="lazy" decoding="async">
                        <?php else: ?>
                            <span class="post-thumb-placeholder"><?php echo htmlspecialchars(timellow_first_character($timellowPosts->title), ENT_QUOTES, 'UTF-8'); ?></span>
                        <?php endif; ?>
                    </a>
                    <div class="post-body">
                        <h2 class="post-title" itemprop="headline">
                            <?php if ($isSticky): ?><span class="post-sticky-badge"><?php _e('置顶'); ?></span><?php endif; ?>
                            <a href="<?php $timellowPosts->permalink(); ?>" itemprop="url"><?php $timellowPosts->title(); ?></a>
                        </h2>
                        <div class="post-meta">
                            <time datetime="<?php $timellowPosts->date('c'); ?>" itemprop="datePublished"><?php $timellowPosts->date('Y-m-d'); ?></time>
                            <?php if (!empty($category)): ?>
                                <span class="meta-separator"></span>
                                <a href="<?php echo htmlspecialchars((string) $category['permalink'], ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars((string) $category['name'], ENT_QUOTES, 'UTF-8'); ?></a>
                            <?php endif; ?>
                        </div>
                        <p class="post-excerpt" itemprop="description"><?php echo htmlspecialchars(timellow_summary($timellowPosts, 92), ENT_QUOTES, 'UTF-8'); ?></p>
                    </div>
                </article>
            <?php endwhile; ?>
        </div>

        <?php timellow_render_pagination($this); ?>
    <?php else: ?>
        <section class="empty-state">
            <p><?php _e('这里还没有发布任何文章。'); ?></p>
        </section>
    <?php endif; ?>
</main>
<?php $this->need('footer.php'); ?>
