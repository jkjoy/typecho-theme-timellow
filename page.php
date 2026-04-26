<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php
$this->need('header.php');
$summary = timellow_summary($this, 96, timellow_site_subtitle());
if ($summary === trim((string) $this->title)) {
    $summary = timellow_site_subtitle();
}
?>
<main class="site-main">
    <section class="page-hero">
        <h1 class="page-title"><?php $this->title(); ?></h1>
    </section>

    <article class="content-card">
        <div class="article-body">
            <?php $this->content(); ?>
        </div>
    </article>

    <?php $this->need('comments.php'); ?>
</main>
<?php $this->need('footer.php'); ?>
