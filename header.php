<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="<?php $this->options->charset(); ?>">
    <meta name="renderer" content="webkit">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5">
    <meta name="color-scheme" content="light dark">
    <title><?php echo htmlspecialchars(timellow_document_title($this), ENT_QUOTES, 'UTF-8'); ?></title>
    <?php if (timellow_option('faviconUrl', '') !== ''): ?>
        <link rel="icon" href="<?php echo htmlspecialchars((string) timellow_option('faviconUrl', ''), ENT_QUOTES, 'UTF-8'); ?>">
        <link rel="shortcut icon" href="<?php echo htmlspecialchars((string) timellow_option('faviconUrl', ''), ENT_QUOTES, 'UTF-8'); ?>">
        <link rel="apple-touch-icon" href="<?php echo htmlspecialchars((string) timellow_option('faviconUrl', ''), ENT_QUOTES, 'UTF-8'); ?>">
    <?php endif; ?>
    <script>
        (function () {
            var key = 'timellow-theme';
            var theme = 'light';

            try {
                var stored = window.localStorage.getItem(key);

                if (stored === 'light' || stored === 'dark') {
                    theme = stored;
                } else if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                    theme = 'dark';
                }
            } catch (e) {}

            document.documentElement.setAttribute('data-theme', theme);
        })();
    </script>
    <link rel="stylesheet" href="<?php $this->options->themeUrl('style.css'); ?>">
    <style>
<?php echo timellow_article_font_style_block(); ?>
    </style>
    <?php $this->header($this->is('post') || $this->is('page') ? 'commentReply=1' : ''); ?>
    <?php if (timellow_option('analyticsCode', '') !== ''): ?>
        <?php echo trim((string) timellow_option('analyticsCode', '')); ?>
    <?php endif; ?>
</head>
<body class="timellow-<?php echo htmlspecialchars((string) $this->archiveType, ENT_QUOTES, 'UTF-8'); ?>">
<div class="site-shell">
    <div class="container">
        <header class="site-header">
            <div class="header-inner">
                <div class="brand">
                    <a class="brand-link" href="<?php $this->options->siteUrl(); ?>" aria-label="<?php echo htmlspecialchars(timellow_site_title(), ENT_QUOTES, 'UTF-8'); ?>">
                        <span class="brand-copy">
                            <span class="brand-title"><?php echo htmlspecialchars(timellow_site_title(), ENT_QUOTES, 'UTF-8'); ?></span>
                            <span class="brand-description"><?php echo htmlspecialchars(timellow_site_subtitle(), ENT_QUOTES, 'UTF-8'); ?></span>
                        </span>
                    </a>
                </div>
                <div class="header-actions">
                    <nav class="site-nav" aria-label="主导航">
                        <a<?php if ($this->is('index')): ?> class="is-current"<?php endif; ?> href="<?php $this->options->siteUrl(); ?>"><?php _e('首页'); ?></a>
                        <?php \Widget\Contents\Page\Rows::alloc()->to($pages); ?>
                        <?php while ($pages->next()): ?>
                            <a<?php if ($this->is('page', $pages->slug)): ?> class="is-current"<?php endif; ?>
                                href="<?php $pages->permalink(); ?>"
                                title="<?php $pages->title(); ?>"><?php $pages->title(); ?></a>
                        <?php endwhile; ?>
                    </nav>
                    <div class="header-tools">
                        <button type="button" class="search-toggle" data-search-toggle aria-expanded="false" aria-controls="site-search-panel">
                            <span class="screen-reader-text"><?php _e('打开搜索'); ?></span>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <circle cx="11" cy="11" r="7"></circle>
                                <path d="m20 20-3.8-3.8"></path>
                            </svg>
                        </button>
                        <button type="button" class="theme-toggle" data-theme-toggle aria-pressed="false">
                            <span class="screen-reader-text" data-theme-label><?php _e('切换深色模式'); ?></span>
                            <svg viewBox="0 0 24 24" aria-hidden="true">
                                <circle class="theme-icon-light" cx="12" cy="12" r="8.5"></circle>
                                <path class="theme-icon-dark" d="M12 3.5a8.5 8.5 0 0 0 0 17z"></path>
                                <circle class="theme-icon-ring" cx="12" cy="12" r="8.5"></circle>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
            <div class="search-panel" id="site-search-panel" data-search-panel hidden>
                <form class="search-form" method="post" action="<?php $this->options->siteUrl(); ?>" role="search">
                    <label for="timellow-search" class="screen-reader-text"><?php _e('搜索关键字'); ?></label>
                    <input id="timellow-search" type="search" name="s" placeholder="<?php _e('搜索文章、页面或关键词'); ?>">
                    <button type="submit"><?php _e('搜索'); ?></button>
                </form>
            </div>
        </header>
