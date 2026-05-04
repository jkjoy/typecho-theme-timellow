<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php timellow_render_sns_links(); ?>
<?php
$timellowCanEditCurrent = $this->user->hasLogin() && ($this->is('post') || $this->is('page')) && isset($this->cid);
$timellowEditPath = '';

if ($timellowCanEditCurrent) {
    $timellowEditPath = ($this->is('page') ? 'write-page.php' : 'write-post.php') . '?cid=' . (int) $this->cid;
}
?>
        <footer class="site-footer">
            <p>
                &copy; <?php echo date('Y'); ?>
                <a href="<?php $this->options->siteUrl(); ?>"><?php echo htmlspecialchars(timellow_site_title(), ENT_QUOTES, 'UTF-8'); ?></a>
                <?php if (trim((string) timellow_option('icpRecord', '')) !== ''): ?>
                    <span class="footer-divider">·</span>
                    <span><a href="https://beian.miit.gov.cn/" target="_blank" rel="noopener"><?php echo htmlspecialchars((string) timellow_option('icpRecord', ''), ENT_QUOTES, 'UTF-8'); ?></a></span>
                <?php endif; ?>
                <span class="footer-divider">·</span>
                <span><?php _e('由'); ?> <a href="https://typecho.org/" target="_blank" rel="noopener">Typecho</a> <?php _e('驱动'); ?></span>
                <span class="footer-divider">·</span>
                <span>Theme <a href="https://www.imsun.org/" target="_blank" rel="noopener">Timellow</a></span>
                <?php if (trim((string) timellow_option('footerNote', '')) !== ''): ?>
                    <span class="footer-divider">·</span>
                    <span><?php echo htmlspecialchars((string) timellow_option('footerNote', ''), ENT_QUOTES, 'UTF-8'); ?></span>
                <?php endif; ?>
            </p>
        </footer>
        <nav class="floating-actions" aria-label="<?php _e('快捷操作'); ?>">
            <button class="floating-action floating-action-top" type="button" data-back-to-top hidden aria-label="<?php _e('返回顶部'); ?>" title="<?php _e('返回顶部'); ?>">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="m18 15-6-6-6 6"></path>
                </svg>
            </button>
            <?php if ($timellowCanEditCurrent): ?>
                <a class="floating-action" href="<?php echo htmlspecialchars(timellow_admin_url($timellowEditPath), ENT_QUOTES, 'UTF-8'); ?>" aria-label="<?php _e('编辑当前内容'); ?>" title="<?php _e('编辑当前内容'); ?>">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M12 20h9"></path>
                        <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"></path>
                    </svg>
                </a>
            <?php endif; ?>
            <a class="floating-action" href="<?php echo htmlspecialchars(timellow_admin_url(''), ENT_QUOTES, 'UTF-8'); ?>" aria-label="<?php _e('登录后台'); ?>" title="<?php _e('登录后台'); ?>">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path>
                    <path d="m10 17 5-5-5-5"></path>
                    <path d="M15 12H3"></path>
                </svg>
            </a>
        </nav>
    </div>
</div>
<script src="<?php $this->options->themeUrl('assets/theme.js?v=' . rawurlencode(TIMELLOW_VERSION)); ?>"></script>
<?php $this->footer(); ?>
</body>
</html>
