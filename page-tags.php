<?php
/**
 * 全部标签
 *
 * @package custom
 */
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
$this->need('header.php');
$pageIntro = trim((string) $this->text) !== '';
$this->widget('Widget_Metas_Tag_Cloud', 'ignoreZeroCount=1&limit=0')->to($tags);
$tagItems = [];
$maxCount = 1;
while ($tags->next()) {
    $count = (int) $tags->count;
    $maxCount = max($maxCount, $count);
    $tagItems[] = [
        'name' => (string) $tags->name,
        'permalink' => (string) $tags->permalink,
        'count' => $count
    ];
}
?>
<main class="site-main">
    <section class="page-hero">
        <p class="page-eyebrow"><?php $this->title(); ?></p>
        <h1 class="page-title"></h1>
        <p class="page-description"><?php _e('以更轻的方式浏览全部话题，快速进入标签聚合页。'); ?></p>
    </section>

    <?php if ($pageIntro): ?>
        <article class="content-card">
            <div class="article-body">
                <?php $this->content(); ?>
            </div>
        </article>
    <?php endif; ?>

    <?php if (!empty($tagItems)): ?>
        <article class="content-card">
            <div class="tag-cloud">
                <?php foreach ($tagItems as $item): ?>
                    <?php $scale = $maxCount > 0 ? round($item['count'] / $maxCount, 2) : 0.2; ?>
                    <a class="tag-chip" href="<?php echo htmlspecialchars((string) $item['permalink'], ENT_QUOTES, 'UTF-8'); ?>" style="--tag-scale: <?php echo $scale; ?>;">
                        <span>#</span>
                        <span><?php echo htmlspecialchars((string) $item['name'], ENT_QUOTES, 'UTF-8'); ?></span>
                        <span><?php echo (int) $item['count']; ?></span>
                    </a>
                <?php endforeach; ?>
            </div>
        </article>
    <?php else: ?>
        <section class="empty-state">
            <p><?php _e('暂时还没有标签。'); ?></p>
        </section>
    <?php endif; ?>

 
</main>
<?php $this->need('footer.php'); ?>
