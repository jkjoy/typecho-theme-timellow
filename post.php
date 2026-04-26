<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php $this->need('header.php'); ?>
<main class="site-main">
    <article class="content-card" itemscope itemtype="https://schema.org/BlogPosting">
        <header class="article-header">
            <h1 class="article-title" itemprop="headline"><?php $this->title(); ?></h1>
            <div class="article-meta">
                <time datetime="<?php $this->date('c'); ?>" itemprop="datePublished"><?php $this->date('Y-m-d'); ?></time>
                <?php if (!empty($this->categories)): ?>
                    <span class="meta-separator"></span>
                    <?php foreach ($this->categories as $index => $category): ?>
                        <?php if ($index > 0): ?><span>, </span><?php endif; ?>
                        <a href="<?php echo htmlspecialchars((string) $category['permalink'], ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars((string) $category['name'], ENT_QUOTES, 'UTF-8'); ?></a>
                    <?php endforeach; ?>
                <?php endif; ?>
                <span class="meta-separator"></span>
                <a href="#comments"><?php $this->commentsNum(_t('暂无评论'), _t('1 条评论'), _t('%d 条评论')); ?></a>
            </div>
        </header>

        <div class="article-body" itemprop="articleBody">
            <?php $this->content(); ?>
        </div>

        <?php if (!empty($this->tags)): ?>
            <footer class="article-footer">
                <div class="tag-list">
                    <?php foreach ($this->tags as $tag): ?>
                        <a class="tag-chip" href="<?php echo htmlspecialchars((string) $tag['permalink'], ENT_QUOTES, 'UTF-8'); ?>">
                            <span>#</span>
                            <span><?php echo htmlspecialchars((string) $tag['name'], ENT_QUOTES, 'UTF-8'); ?></span>
                        </a>
                    <?php endforeach; ?>
                </div>
            </footer>
        <?php endif; ?>
    </article>

    <nav class="article-nav" aria-label="<?php _e('文章切换'); ?>">
        <div class="article-nav-card">
            <span class="article-nav-label"><?php _e('上一篇'); ?></span>
            <p class="article-nav-title"><?php $this->thePrev('%s', _t('已经是第一篇了')); ?></p>
        </div>
        <div class="article-nav-card">
            <span class="article-nav-label"><?php _e('下一篇'); ?></span>
            <p class="article-nav-title"><?php $this->theNext('%s', _t('已经是最后一篇了')); ?></p>
        </div>
    </nav>

    <?php $this->need('comments.php'); ?>
</main>
<?php $this->need('footer.php'); ?>
