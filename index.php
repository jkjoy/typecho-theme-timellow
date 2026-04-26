<?php
/**
 * Timellow Theme
 * 简单、清爽、极简的 Typecho 主题
 * @package Timellow
 * @author 时光沉淀
 * @version 1.0.0
 * @link https://www.timellow.com/
 */
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
$this->need('header.php');
?>
<main class="site-main">
    <?php if ($this->have()): ?>
        <div class="post-list" data-post-list>
            <?php while ($this->next()): ?>
                <?php
                $cover = timellow_post_cover($this);
                $category = !empty($this->categories) ? $this->categories[0] : null;
                ?>
                <article class="post-card" itemscope itemtype="https://schema.org/BlogPosting">
                    <a class="post-thumb-link" href="<?php $this->permalink(); ?>" aria-label="<?php $this->title(); ?>">
                        <?php if ($cover !== ''): ?>
                            <img class="post-thumb" src="<?php echo htmlspecialchars($cover, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php $this->title(); ?>" loading="lazy" decoding="async">
                        <?php else: ?>
                            <span class="post-thumb-placeholder"><?php echo htmlspecialchars(timellow_first_character($this->title), ENT_QUOTES, 'UTF-8'); ?></span>
                        <?php endif; ?>
                    </a>
                    <div class="post-body">
                        <h2 class="post-title" itemprop="headline">
                            <a href="<?php $this->permalink(); ?>" itemprop="url"><?php $this->title(); ?></a>
                        </h2>
                        <div class="post-meta">
                            <time datetime="<?php $this->date('c'); ?>" itemprop="datePublished"><?php $this->date('Y-m-d'); ?></time>
                            <?php if (!empty($category)): ?>
                                <span class="meta-separator"></span>
                                <a href="<?php echo htmlspecialchars((string) $category['permalink'], ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars((string) $category['name'], ENT_QUOTES, 'UTF-8'); ?></a>
                            <?php endif; ?>
                        </div>
                        <p class="post-excerpt" itemprop="description"><?php echo htmlspecialchars(timellow_summary($this, 92), ENT_QUOTES, 'UTF-8'); ?></p>
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
