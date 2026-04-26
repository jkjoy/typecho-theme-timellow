<?php
/**
 * 文章归档
 *
 * @package custom
 */
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
$this->need('header.php');
$pageIntro = trim((string) $this->text) !== '';
$stat = \Typecho\Widget::widget('Widget_Stat');
\Typecho\Widget::widget('Widget_Contents_Post_Recent', 'pageSize=' . max(1, (int) $stat->publishedPostsNum))->to($posts);
$groups = [];
while ($posts->next()) {
    $year = date('Y', $posts->created);
    if (!isset($groups[$year])) {
        $groups[$year] = [];
    }
    $groups[$year][] = [
        'title' => (string) $posts->title,
        'permalink' => (string) $posts->permalink,
        'date' => date('m-d', $posts->created)
    ];
}
?>
<main class="site-main">
    <section class="page-hero">
        <h1 class="page-title"><?php $this->title(); ?></h1>
    </section>

    <?php if ($pageIntro): ?>
        <article class="content-card">
            <div class="article-body">
                <?php $this->content(); ?>
            </div>
        </article>
    <?php endif; ?>

    <?php if (!empty($groups)): ?>
        <div class="archive-list">
            <?php foreach ($groups as $year => $items): ?>
                <section class="archive-year-card">
                    <div class="archive-year-header">
                        <h2 class="archive-year-title"><?php echo htmlspecialchars((string) $year, ENT_QUOTES, 'UTF-8'); ?></h2>
                        <span class="archive-year-count"><?php echo count($items); ?> <?php _e('篇文章'); ?></span>
                    </div>
                    <div class="archive-items">
                        <?php foreach ($items as $item): ?>
                            <article class="archive-item">
                                <time><?php echo htmlspecialchars((string) $item['date'], ENT_QUOTES, 'UTF-8'); ?></time>
                                <a href="<?php echo htmlspecialchars((string) $item['permalink'], ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars((string) $item['title'], ENT_QUOTES, 'UTF-8'); ?></a>
                            </article>
                        <?php endforeach; ?>
                    </div>
                </section>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <section class="empty-state">
            <p><?php _e('还没有可归档的文章。'); ?></p>
        </section>
    <?php endif; ?>

    
</main>
<?php $this->need('footer.php'); ?>
