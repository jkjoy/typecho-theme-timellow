<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php
$this->need('header.php');
$heading = timellow_archive_heading($this);
?>
<main class="site-main">
    <section class="page-hero">
        <h1 class="page-title"><?php echo htmlspecialchars((string) $heading['title'], ENT_QUOTES, 'UTF-8'); ?></h1>
        <p class="page-description"><?php echo htmlspecialchars((string) $heading['description'], ENT_QUOTES, 'UTF-8'); ?></p>
    </section>

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
                        <h2 class="post-title">
                            <a href="<?php $this->permalink(); ?>"><?php $this->title(); ?></a>
                        </h2>
                        <div class="post-meta">
                            <time datetime="<?php $this->date('c'); ?>"><?php $this->date('Y-m-d'); ?></time>
                            <?php if (!empty($category)): ?>
                                <span class="meta-separator"></span>
                                <a href="<?php echo htmlspecialchars((string) $category['permalink'], ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars((string) $category['name'], ENT_QUOTES, 'UTF-8'); ?></a>
                            <?php endif; ?>
                        </div>
                        <p class="post-excerpt"><?php echo htmlspecialchars(timellow_summary($this, 96), ENT_QUOTES, 'UTF-8'); ?></p>
                    </div>
                </article>
            <?php endwhile; ?>
        </div>

        <?php timellow_render_pagination($this); ?>
    <?php else: ?>
        <section class="empty-state">
            <p><?php _e('这个归档下暂时没有内容。'); ?></p>
        </section>
    <?php endif; ?>
</main>
<?php $this->need('footer.php'); ?>
