<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php timellow_render_sns_links(); ?>
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
    </div>
</div>
<script src="<?php $this->options->themeUrl('assets/theme.js'); ?>"></script>
<?php $this->footer(); ?>
</body>
</html>
