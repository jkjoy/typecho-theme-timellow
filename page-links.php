<?php
/**
 * 友情链接
 *
 * @package custom
 */
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
$this->need('header.php');
$rawPageText = (string) $this->text;
$pageLinksSource = timellow_parse_links_page_text($rawPageText);
$linksResult = timellow_fetch_friend_links($rawPageText);
$linkGroups = $linksResult['groups'];
$pageIntro = false;

if ($pageLinksSource['source'] === 'page-raw') {
    $pageIntro = false;
} elseif ($pageLinksSource['source'] === 'page-comment') {
    $pageIntro = timellow_links_page_has_intro($rawPageText);
} else {
    $pageIntro = trim($rawPageText) !== '';
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

    <?php if (!empty($linkGroups)): ?>
        <?php foreach ($linkGroups as $groupName => $items): ?>
            <section class="links-group">
                <h2 class="links-group-title">
                    <span><?php echo htmlspecialchars((string) $groupName, ENT_QUOTES, 'UTF-8'); ?></span>
                    <span class="links-group-count"><?php echo count($items); ?> <?php _e('个站点'); ?></span>
                </h2>
                <div class="links-grid">
                    <?php foreach ($items as $item): ?>
                        <article class="link-card">
                            <div class="link-avatar">
                                <?php if (!empty($item['image'])): ?>
                                    <img src="<?php echo htmlspecialchars((string) $item['image'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars((string) $item['name'], ENT_QUOTES, 'UTF-8'); ?>" loading="lazy" decoding="async">
                                <?php else: ?>
                                    <span class="link-avatar-fallback"><?php echo htmlspecialchars(timellow_first_character($item['name']), ENT_QUOTES, 'UTF-8'); ?></span>
                                <?php endif; ?>
                            </div>
                            <div class="link-main">
                                <div class="link-heading">
                                    <a class="link-name" href="<?php echo htmlspecialchars((string) $item['url'], ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener nofollow">
                                        <?php echo htmlspecialchars((string) $item['name'], ENT_QUOTES, 'UTF-8'); ?>
                                    </a>
                                    <?php if (!empty($item['host'])): ?>
                                        <span class="link-host"><?php echo htmlspecialchars((string) $item['host'], ENT_QUOTES, 'UTF-8'); ?></span>
                                    <?php endif; ?>
                                </div>
                                <?php if (!empty($item['description'])): ?>
                                    <p class="link-desc"><?php echo htmlspecialchars((string) $item['description'], ENT_QUOTES, 'UTF-8'); ?></p>
                                <?php endif; ?>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endforeach; ?>
    <?php else: ?>
        <section class="empty-state">
            <p><?php _e('暂时还没有友情链接。请优先配置友链插件；如果没有使用插件，请在友情链接页面内容中填写友链数据。'); ?></p>
        </section>
    <?php endif; ?>

    <?php $this->need('comments.php'); ?>
</main>
<?php $this->need('footer.php'); ?>
