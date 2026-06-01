<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<aside class="site-sidebar" aria-label="<?php echo timellow_escape(_t('侧边栏')); ?>">
    <?php if (timellow_sidebar_widget_enabled('profile')): ?>
        <?php
        $profileTitle = timellow_admin_screen_name(1);
        $profileTitle = $profileTitle !== '' ? $profileTitle : timellow_site_title();
        $profileTitle = $profileTitle !== '' ? $profileTitle : _t('博主');
        $profileBio = trim((string) timellow_option('sidebarProfileBio', ''));
        $profileBio = $profileBio !== '' ? $profileBio : timellow_site_subtitle();
        $profileAvatar = trim((string) timellow_option('sidebarProfileAvatar', ''));
        $profileLinks = timellow_sns_links();
        ?>
        <section class="sidebar-widget sidebar-profile-widget">
            <div class="sidebar-profile">
                <div class="sidebar-profile-avatar" aria-hidden="true">
                    <?php if ($profileAvatar !== ''): ?>
                        <img src="<?php echo timellow_escape($profileAvatar); ?>" alt="<?php echo timellow_escape($profileTitle); ?>" loading="lazy" decoding="async">
                    <?php else: ?>
                        <span><?php echo timellow_escape(timellow_first_character($profileTitle)); ?></span>
                    <?php endif; ?>
                </div>
                <h2 class="sidebar-profile-name"><?php echo timellow_escape($profileTitle); ?></h2>
                <?php if ($profileBio !== ''): ?>
                    <p class="sidebar-profile-bio"><?php echo timellow_escape($profileBio); ?></p>
                <?php endif; ?>
            </div>

            <?php if (!empty($profileLinks)): ?>
                <nav class="sidebar-profile-links" aria-label="<?php echo timellow_escape(_t('博主链接')); ?>">
                    <?php foreach ($profileLinks as $link): ?>
                        <?php
                        $target = !empty($link['target']) ? ' target="' . timellow_escape($link['target']) . '"' : '';
                        $rel = !empty($link['rel']) ? ' rel="' . timellow_escape($link['rel']) . '"' : '';
                        ?>
                        <a class="sidebar-profile-link sidebar-profile-link-<?php echo timellow_escape($link['type']); ?>" href="<?php echo timellow_escape($link['url']); ?>"<?php echo $target . $rel; ?> aria-label="<?php echo timellow_escape($link['label']); ?>" title="<?php echo timellow_escape($link['label']); ?>">
                            <?php echo timellow_sns_icon($link['type']); ?>
                        </a>
                    <?php endforeach; ?>
                </nav>
            <?php endif; ?>
        </section>
    <?php endif; ?>

    <?php if (timellow_sidebar_widget_enabled('latest_posts')): ?>
        <?php $recentPosts = timellow_sidebar_recent_posts(); ?>
        <?php if (!empty($recentPosts)): ?>
            <section class="sidebar-widget">
                <h2 class="sidebar-widget-title"><?php _e('最新文章'); ?></h2>
                <ol class="sidebar-list">
                    <?php foreach ($recentPosts as $post): ?>
                        <li class="sidebar-list-item">
                            <a class="sidebar-item-link" href="<?php echo timellow_escape($post['url']); ?>">
                                <span class="sidebar-item-title"><?php echo timellow_escape($post['title']); ?></span>
                                <?php if ($post['date'] !== ''): ?>
                                    <span class="sidebar-item-meta"><?php echo timellow_escape($post['date']); ?></span>
                                <?php endif; ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ol>
            </section>
        <?php endif; ?>
    <?php endif; ?>

    <?php if (timellow_sidebar_widget_enabled('popular_posts')): ?>
        <?php $popularPosts = timellow_sidebar_popular_posts(); ?>
        <?php if (!empty($popularPosts)): ?>
            <section class="sidebar-widget">
                <h2 class="sidebar-widget-title"><?php _e('热门文章'); ?></h2>
                <ol class="sidebar-list">
                    <?php foreach ($popularPosts as $post): ?>
                        <li class="sidebar-list-item">
                            <a class="sidebar-item-link" href="<?php echo timellow_escape($post['url']); ?>">
                                <span class="sidebar-item-title"><?php echo timellow_escape($post['title']); ?></span>
                                <span class="sidebar-item-meta"><?php echo timellow_escape(sprintf(_t('%d 条评论'), (int) $post['comments'])); ?></span>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ol>
            </section>
        <?php endif; ?>
    <?php endif; ?>

    <?php if (timellow_sidebar_widget_enabled('latest_comments')): ?>
        <?php $recentComments = timellow_sidebar_recent_comments(); ?>
        <?php if (!empty($recentComments)): ?>
            <section class="sidebar-widget">
                <h2 class="sidebar-widget-title"><?php _e('最新评论'); ?></h2>
                <ol class="sidebar-list sidebar-comment-list">
                    <?php foreach ($recentComments as $comment): ?>
                        <li class="sidebar-list-item">
                            <a class="sidebar-item-link" href="<?php echo timellow_escape($comment['url']); ?>">
                                <span class="sidebar-comment-author"><?php echo timellow_escape($comment['author']); ?></span>
                                <span class="sidebar-comment-text"><?php echo timellow_escape($comment['text']); ?></span>
                                <?php if ($comment['date'] !== ''): ?>
                                    <span class="sidebar-item-meta"><?php echo timellow_escape($comment['date']); ?></span>
                                <?php endif; ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ol>
            </section>
        <?php endif; ?>
    <?php endif; ?>

    <?php if (timellow_sidebar_widget_enabled('latest_moments')): ?>
        <?php $recentMoments = timellow_sidebar_recent_moments(); ?>
        <?php if (!empty($recentMoments)): ?>
            <section class="sidebar-widget">
                <h2 class="sidebar-widget-title"><?php _e('最新说说'); ?></h2>
                <ol class="sidebar-list sidebar-moment-list">
                    <?php foreach ($recentMoments as $moment): ?>
                        <li class="sidebar-list-item">
                            <a class="sidebar-item-link" href="<?php echo timellow_escape($moment['url']); ?>">
                                <span class="sidebar-moment-text"><?php echo timellow_escape($moment['text']); ?></span>
                                <?php if ($moment['date'] !== ''): ?>
                                    <span class="sidebar-item-meta"><?php echo timellow_escape($moment['date']); ?></span>
                                <?php endif; ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ol>
            </section>
        <?php endif; ?>
    <?php endif; ?>
</aside>
