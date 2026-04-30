<?php
/**
 * 说说
 *
 * @package custom
 */
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
$this->need('header.php');
$pageIntro = trim((string) $this->text) !== '';
$momentsResult = timellow_fetch_moments();
$moments = $momentsResult['items'];
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

    <?php if (!empty($moments)): ?>
        <section class="moments-list" aria-label="<?php echo htmlspecialchars(_t('说说列表'), ENT_QUOTES, 'UTF-8'); ?>">
            <?php foreach ($moments as $moment): ?>
                <?php
                    $created = (int) $moment['created'];
                    $datetime = $created > 0 ? date('c', $created) : '';
                    $publishedText = $created > 0 ? date('Y-m-d H:i', $created) : '';
                    $mediaCount = count($moment['media']);
                    $mediaClass = $mediaCount === 1 ? ' is-single' : ($mediaCount === 2 ? ' is-two' : '');
                    $source = trim((string) $moment['source']);
                    $sourceHtml = timellow_moment_source_html($source);
                ?>
                <article class="moment-item" id="moment-<?php echo (int) $moment['mid']; ?>">
                    <div class="moment-main">
                        <?php if (!empty($moment['tags'])): ?>
                            <div class="moment-tags">
                                <?php foreach ($moment['tags'] as $tag): ?>
                                    <span class="moment-tag">#<?php echo htmlspecialchars((string) $tag, ENT_QUOTES, 'UTF-8'); ?></span>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($moment['content'] !== ''): ?>
                            <div class="moment-content">
                                <?php echo timellow_moment_content_html($moment['content']); ?>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($moment['media'])): ?>
                            <div class="moment-media-grid<?php echo htmlspecialchars($mediaClass, ENT_QUOTES, 'UTF-8'); ?>">
                                <?php foreach ($moment['media'] as $media): ?>
                                    <?php if ($media['type'] === 'VIDEO'): ?>
                                        <div class="moment-media-item">
                                            <video src="<?php echo htmlspecialchars((string) $media['url'], ENT_QUOTES, 'UTF-8'); ?>" controls preload="metadata"></video>
                                        </div>
                                    <?php elseif ($media['type'] === 'PHOTO'): ?>
                                        <?php $mediaUrl = htmlspecialchars((string) $media['url'], ENT_QUOTES, 'UTF-8'); ?>
                                        <a class="moment-media-item" href="<?php echo $mediaUrl; ?>" target="_blank" rel="noopener">
                                            <img src="<?php echo $mediaUrl; ?>" alt="" loading="lazy" decoding="async">
                                        </a>
                                    <?php else: ?>
                                        <a class="moment-file-link" href="<?php echo htmlspecialchars((string) $media['url'], ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener nofollow"><?php _e('查看附件'); ?></a>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($publishedText !== '' || $moment['location'] !== '' || $sourceHtml !== ''): ?>
                            <footer class="moment-meta">
                                <?php if ($publishedText !== '' || $moment['location'] !== ''): ?>
                                    <span class="moment-meta-left">
                                        <?php if ($publishedText !== ''): ?>
                                            <time class="moment-published" datetime="<?php echo htmlspecialchars($datetime, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($publishedText, ENT_QUOTES, 'UTF-8'); ?></time>
                                        <?php endif; ?>
                                        <?php if ($moment['location'] !== ''): ?>
                                            <span class="moment-location">
                                                <svg class="moment-location-icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                                                    <path d="M20 10c0 5-8 11-8 11S4 15 4 10a8 8 0 1 1 16 0Z"></path>
                                                    <circle cx="12" cy="10" r="3"></circle>
                                                </svg>
                                                <span class="moment-location-text"><?php echo htmlspecialchars((string) $moment['location'], ENT_QUOTES, 'UTF-8'); ?></span>
                                            </span>
                                        <?php endif; ?>
                                    </span>
                                <?php endif; ?>
                                <?php if ($sourceHtml !== ''): ?>
                                    <span class="moment-source"><?php echo $sourceHtml; ?></span>
                                <?php endif; ?>
                            </footer>
                        <?php endif; ?>
                    </div>
                </article>
            <?php endforeach; ?>
        </section>
    <?php else: ?>
        <section class="empty-state">
            <p><?php echo htmlspecialchars(!empty($momentsResult['error']) ? _t('暂时无法读取说说数据。') : _t('暂时还没有说说。'), ENT_QUOTES, 'UTF-8'); ?></p>
        </section>
    <?php endif; ?>
</main>
<?php $this->need('footer.php'); ?>
