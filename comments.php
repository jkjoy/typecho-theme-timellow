<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<section id="comments" class="comments-section">
    <?php $this->comments()->to($comments); ?>

    <h2 class="comments-header">
        <p><?php _e('评论'); ?></p>
    </h2>

    <?php if ($comments->have()): ?>
        <ol class="comment-list">
            <?php $comments->listComments(['callback' => 'threadedComments']); ?>
        </ol>

        <div class="comments-nav">
            <?php
            $comments->pageNav(
                _t('上一页'),
                _t('下一页'),
                1,
                '...',
                [
                    'wrapTag' => 'div',
                    'wrapClass' => 'page-navigator',
                    'itemTag' => '',
                    'textTag' => 'span',
                    'itemClass' => '',
                    'currentClass' => 'current',
                    'prevClass' => '',
                    'nextClass' => ''
                ]
            );
            ?>
        </div>
    <?php endif; ?>

    <?php if ($this->allow('comment')): ?>
        <div id="<?php $this->respondId(); ?>" class="comment-form-card">
            <form method="post" action="<?php $this->commentUrl(); ?>" id="comment-form">
                <input type="hidden" name="parent" id="comment-parent" value="0">

                <?php if ($this->user->hasLogin()): ?>
                    <p class="comment-form-tip">
                        <?php _e('登录身份：'); ?>
                        <a href="<?php $this->options->profileUrl(); ?>"><?php $this->user->screenName(); ?></a>
                        <span class="footer-divider">·</span>
                        <a href="<?php $this->options->logoutUrl(); ?>"><?php _e('退出'); ?></a>
                    </p>
                <?php else: ?>
                    <div class="comment-form-grid">
                        <input class="comment-form-field" type="text" name="author" id="author" placeholder="<?php _e('昵称 *'); ?>" value="<?php $this->remember('author'); ?>" required>
                        <input class="comment-form-field" type="email" name="mail" id="mail" placeholder="<?php _e('邮箱'); ?><?php if ($this->options->commentsRequireMail): ?> *<?php endif; ?>" value="<?php $this->remember('mail'); ?>"<?php if ($this->options->commentsRequireMail): ?> required<?php endif; ?>>
                        <input class="comment-form-field" type="url" name="url" id="url" placeholder="<?php _e('网站'); ?>" value="<?php $this->remember('url'); ?>"<?php if ($this->options->commentsRequireUrl): ?> required<?php endif; ?>>
                    </div>
                <?php endif; ?>

                <textarea name="text" id="textarea" placeholder="<?php _e('写下你的看法...'); ?>" rows="7" required><?php $this->remember('text'); ?></textarea>

                <div class="comment-form-meta">
                    <div class="comment-form-actions">
                        <button class="button-link comment-submit-button" type="submit"><?php _e('提交评论'); ?></button>
                        <a class="cancel-comment-reply" rel="nofollow" id="cancel-comment-reply-link" href="#comments" style="display:none;" onclick="return TypechoComment.cancelReply();"><?php _e('取消回复'); ?></a>
                    </div>
                </div>
            </form>
        </div>
    <?php else: ?>
        <div class="notice-card">
            <p><?php _e('评论已关闭。'); ?></p>
        </div>
    <?php endif; ?>
</section>
