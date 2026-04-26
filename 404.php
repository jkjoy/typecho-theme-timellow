<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php $this->need('header.php'); ?>
<main class="site-main">
    <section class="page-hero">
        <p class="page-eyebrow">404</p>
        <h1 class="page-title"><?php _e('你访问的页面不存在'); ?></h1>
        <p class="page-description"><?php _e('可能是链接失效、文章已移动，或者输入地址时多了一个字符。'); ?></p>
    </section>

    <section class="empty-state">
        <p><?php _e('返回首页继续浏览，也许你会找到想看的内容。'); ?></p>
        <p style="margin-top: 18px;">
            <a class="button-link" href="<?php $this->options->siteUrl(); ?>"><?php _e('回到首页'); ?></a>
        </p>
    </section>
</main>
<?php $this->need('footer.php'); ?>
