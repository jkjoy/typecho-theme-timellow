<?php
/**
 * 全部分类
 *
 * @package custom
 */
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
$this->need('header.php');
$pageIntro = trim((string) $this->text) !== '';
$this->widget('Widget_Metas_Category_List')->to($categories);
$categoryItems = [];
while ($categories->next()) {
    $categoryItems[] = [
        'name' => (string) $categories->name,
        'permalink' => (string) $categories->permalink,
        'description' => trim((string) $categories->description),
        'count' => (int) $categories->count
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

    <?php if (!empty($categoryItems)): ?>
        <div class="taxonomy-grid">
            <?php foreach ($categoryItems as $item): ?>
                <article class="taxonomy-card">
                    <h2 class="taxonomy-card-title">
                        <a href="<?php echo htmlspecialchars((string) $item['permalink'], ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars((string) $item['name'], ENT_QUOTES, 'UTF-8'); ?></a>
                    </h2>
                    <p><?php echo htmlspecialchars($item['description'] !== '' ? $item['description'] : '这个分类下收纳了相关主题文章。', ENT_QUOTES, 'UTF-8'); ?></p>
                    <div class="taxonomy-card-meta"><?php echo (int) $item['count']; ?> <?php _e('篇文章'); ?></div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <section class="empty-state">
            <p><?php _e('暂时还没有分类。'); ?></p>
        </section>
    <?php endif; ?>
   
</main>
<?php $this->need('footer.php'); ?>
