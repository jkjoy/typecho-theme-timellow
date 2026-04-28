<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;

if (!defined('TIMELLOW_VERSION')) {
    define('TIMELLOW_VERSION', '1.0.7');
}

if (!defined('TIMELLOW_UPDATE_REPO')) {
    define('TIMELLOW_UPDATE_REPO', 'jkjoy/typecho-theme-timellow');
}

if (!defined('TIMELLOW_UPDATE_PACKAGE')) {
    define('TIMELLOW_UPDATE_PACKAGE', 'timellow.zip');
}

function themeConfig($form)
{
    timellow_handle_update_request();
    timellow_add_update_panel($form);

    $updaterCaFile = new \Typecho\Widget\Helper\Form\Element\Text(
        'updaterCaFile',
        null,
        '',
        _t('更新器 CA 证书路径'),
        _t('可选。服务器未配置 curl.cainfo / openssl.cafile 时填写 cacert.pem 的绝对路径，用于安全连接 GitHub。留空会自动尝试常见路径。')
    );
    $form->addInput($updaterCaFile);

    $updaterPackageUrl = new \Typecho\Widget\Helper\Form\Element\Text(
        'updaterPackageUrl',
        null,
        '',
        _t('更新包下载地址'),
        _t('可选。填写 timellow.zip 直链后，“在线更新”会优先使用此地址，适合 GitHub 下载慢或被阻断时配置代理/CDN 地址。')
    );
    $form->addInput($updaterPackageUrl->addRule('url', _t('请填写合法的更新包 URL 地址')));

    $updaterAllowInsecureSsl = new \Typecho\Widget\Helper\Form\Element\Checkbox(
        'updaterAllowInsecureSsl',
        ['1' => _t('允许在线更新在 HTTPS 证书校验失败时关闭证书校验')],
        null,
        _t('更新器兼容模式'),
        _t('默认不要开启。只有在服务器无法配置 CA 证书，且你确认网络环境可信时才勾选。')
    );
    $form->addInput($updaterAllowInsecureSsl);

    $faviconUrl = new \Typecho\Widget\Helper\Form\Element\Text(
        'faviconUrl',
        null,
        '',
        _t('Favicon 地址'),
        _t('输出到页面 head 中，填写完整图标 URL。')
    );
    $form->addInput($faviconUrl->addRule('url', _t('请填写合法的 Favicon URL 地址')));

    $subtitle = new \Typecho\Widget\Helper\Form\Element\Text(
        'subtitle',
        null,
        'Stay hungry. Stay foolish.',
        _t('站点副标题'),
        _t('显示在站点标题下方，默认读取这里；留空则回退到站点描述。')
    );
    $form->addInput($subtitle);

    $icpRecord = new \Typecho\Widget\Helper\Form\Element\Text(
        'icpRecord',
        null,
        '',
        _t('备案号'),
        _t('例如：浙ICP备00000000号-1，留空则不显示。')
    );
    $form->addInput($icpRecord);

    $footerNote = new \Typecho\Widget\Helper\Form\Element\Text(
        'footerNote',
        null,
        '',
        _t('页脚补充文案'),
        _t('显示在页脚的附加信息，例如版权说明或一句话介绍。')
    );
    $form->addInput($footerNote);

    $defaultCover = new \Typecho\Widget\Helper\Form\Element\Text(
        'defaultCover',
        null,
        '',
        _t('默认缩略图'),
        _t('当文章没有封面图和正文首图时使用；留空则从主题 assets/cover 目录随机选择图片。')
    );
    $form->addInput($defaultCover);

    $analyticsCode = new \Typecho\Widget\Helper\Form\Element\Textarea(
        'analyticsCode',
        null,
        '',
        _t('统计代码'),
        _t('会直接插入到页面 head 尾部，适合放站点统计或分析脚本。')
    );
    $form->addInput($analyticsCode);

    $articleListPagingMode = new \Typecho\Widget\Helper\Form\Element\Select(
        'articleListPagingMode',
        [
            'pagination' => _t('页码'),
            'loadmore' => _t('加载更多')
        ],
        'pagination',
        _t('文章列表加载方式'),
        _t('应用于首页、分类、标签、搜索等文章列表页。选择“加载更多”后，会按 index.html 的按钮样式逐页追加文章。')
    );
    $form->addInput($articleListPagingMode);

    $articleFontMode = new \Typecho\Widget\Helper\Form\Element\Select(
        'articleFontMode',
        [
            'lxgw' => _t('霞鹜文楷（内置）'),
            'custom' => _t('自定义字体')
        ],
        'lxgw',
        _t('全站字体'),
        _t('默认使用主题内置的霞鹜文楷。切换到自定义后，可继续填写字体名称和字体文件 URL，前台全局生效。')
    );
    $form->addInput($articleFontMode);

    $customArticleFontName = new \Typecho\Widget\Helper\Form\Element\Text(
        'customArticleFontName',
        null,
        '',
        _t('自定义全站字体名称'),
        _t('例如：Smiley Sans、Noto Serif SC。若填写字体文件 URL，留空时会自动使用主题内部别名。')
    );
    $form->addInput($customArticleFontName);

    $customArticleFontUrl = new \Typecho\Widget\Helper\Form\Element\Text(
        'customArticleFontUrl',
        null,
        '',
        _t('自定义全站字体文件 URL'),
        _t('可选，支持 woff2 / woff / ttf / otf。填写后会在前台自动注册该字体并全站生效。')
    );
    $form->addInput($customArticleFontUrl->addRule('url', _t('请填写合法的字体文件 URL 地址')));
}

function themeFields($layout)
{
    $cover = new \Typecho\Widget\Helper\Form\Element\Text(
        'cover',
        null,
        null,
        _t('封面图地址'),
        _t('用于首页和列表页缩略图，留空则自动提取正文首图。')
    );
    $layout->addItem($cover);

    $summary = new \Typecho\Widget\Helper\Form\Element\Textarea(
        'summary',
        null,
        null,
        _t('摘要'),
        _t('列表页和独立页面说明文案，留空则自动截取正文。')
    );
    $layout->addItem($summary);

    $sticky = new \Typecho\Widget\Helper\Form\Element\Checkbox(
        'sticky',
        ['true' => _t('置顶文章')],
        null,
        _t('置顶文章'),
        _t('勾选后，这篇文章会显示在首页文章列表最前面，并从后续普通文章列表中排除，避免重复出现。')
    );
    $layout->addItem($sticky);
}

function themeInit($archive)
{
    if (!$archive->is('page') || !empty($archive->template)) {
        return;
    }

    $slugMap = [
        'links' => 'page-links.php',
        'archives' => 'page-archives.php',
        'categories' => 'page-categories.php',
        'tags' => 'page-tags.php',
        'moments' => 'page-moments.php',
        'shuoshuo' => 'page-moments.php'
    ];

    $slug = trim((string) $archive->slug);
    if ($slug !== '' && isset($slugMap[$slug]) && file_exists(__DIR__ . DIRECTORY_SEPARATOR . $slugMap[$slug])) {
        $archive->setThemeFile($slugMap[$slug]);
    }
}

function timellow_option($name, $default = '')
{
    $options = \Typecho\Widget::widget('Widget_Options');
    return isset($options->$name) && trim((string) $options->$name) !== '' ? $options->$name : $default;
}

function timellow_site_title()
{
    $options = \Typecho\Widget::widget('Widget_Options');
    return isset($options->title) ? trim((string) $options->title) : '';
}

function timellow_capture($callback)
{
    ob_start();
    $callback();
    return trim((string) ob_get_clean());
}

function timellow_sanitize_css_text($value)
{
    $value = trim((string) $value);
    $value = preg_replace('/[<>{};]/u', '', $value);
    $value = preg_replace('/\s+/u', ' ', $value);
    return trim((string) $value);
}

function timellow_escape($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function timellow_version_number($version)
{
    $version = trim((string) $version);
    return ltrim($version, "vV \t\n\r\0\x0B");
}

function timellow_admin_url($path)
{
    $options = \Widget\Options::alloc();
    return \Typecho\Common::url($path, $options->adminUrl);
}

function timellow_update_action_url($action)
{
    $security = \Widget\Security::alloc();
    return $security->getAdminUrl('options-theme.php?timellow_update=' . rawurlencode((string) $action));
}

function timellow_update_notice($message, $type = 'notice')
{
    \Widget\Notice::alloc()->set($message, $type);
}

function timellow_update_ca_file()
{
    $configured = trim((string) timellow_option('updaterCaFile', ''));
    $candidates = [];

    if ($configured !== '') {
        $candidates[] = $configured;
    }

    $curlCaInfo = trim((string) ini_get('curl.cainfo'));
    if ($curlCaInfo !== '') {
        $candidates[] = $curlCaInfo;
    }

    $opensslCaFile = trim((string) ini_get('openssl.cafile'));
    if ($opensslCaFile !== '') {
        $candidates[] = $opensslCaFile;
    }

    $candidates[] = __TYPECHO_ROOT_DIR__ . '/usr/plugins/TeStore/data/cacert.pem';
    $candidates[] = dirname(__TYPECHO_ROOT_DIR__, 2) . '/childApp/tool/phpMyAdmin/vendor/composer/ca-bundle/res/cacert.pem';
    $candidates[] = '/etc/ssl/certs/ca-certificates.crt';
    $candidates[] = '/etc/pki/tls/certs/ca-bundle.crt';
    $candidates[] = '/usr/local/share/certs/ca-root-nss.crt';

    foreach (array_unique($candidates) as $candidate) {
        if ($candidate !== '' && is_file($candidate) && is_readable($candidate)) {
            return $candidate;
        }
    }

    return '';
}

function timellow_update_allow_insecure_ssl()
{
    return timellow_truthy(timellow_option('updaterAllowInsecureSsl', ''));
}

function timellow_http_get($url, array $headers = [], $timeout = 20, $outputFile = null)
{
    $url = trim((string) $url);
    if ($url === '') {
        throw new RuntimeException('请求地址为空。');
    }

    if (function_exists('curl_init')) {
        $ch = curl_init($url);
        if (!$ch) {
            throw new RuntimeException('无法初始化网络请求。');
        }

        $requestHeaders = array_merge([
            'User-Agent: Timellow-Theme-Updater/' . TIMELLOW_VERSION,
            'Accept: application/vnd.github+json'
        ], $headers);

        curl_setopt($ch, CURLOPT_HTTPHEADER, $requestHeaders);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, max(10, (int) $timeout));
        $verifySsl = !timellow_update_allow_insecure_ssl();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $verifySsl);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, $verifySsl ? 2 : 0);
        $caFile = timellow_update_ca_file();
        if ($verifySsl && $caFile !== '') {
            curl_setopt($ch, CURLOPT_CAINFO, $caFile);
        }

        $handle = null;
        if ($outputFile !== null) {
            $handle = fopen($outputFile, 'wb');
            if (!$handle) {
                curl_close($ch);
                throw new RuntimeException('无法写入下载文件。');
            }
            curl_setopt($ch, CURLOPT_FILE, $handle);
        } else {
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        }

        $body = curl_exec($ch);
        $status = (int) curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($handle) {
            fclose($handle);
        }

        if ($body === false || $status >= 400 || $status < 200) {
            throw new RuntimeException($error !== '' ? $error : '网络请求失败，HTTP 状态码：' . $status);
        }

        return $outputFile !== null ? '' : (string) $body;
    }

    $context = stream_context_create([
        'http' => [
            'method' => 'GET',
            'timeout' => max(10, (int) $timeout),
            'header' => implode("\r\n", array_merge([
                'User-Agent: Timellow-Theme-Updater/' . TIMELLOW_VERSION,
                'Accept: application/vnd.github+json'
            ], $headers))
        ]
    ]);

    $body = @file_get_contents($url, false, $context);
    if ($body === false) {
        throw new RuntimeException('网络请求失败。');
    }

    if ($outputFile !== null && file_put_contents($outputFile, $body) === false) {
        throw new RuntimeException('无法写入下载文件。');
    }

    return $outputFile !== null ? '' : (string) $body;
}

function timellow_latest_release()
{
    $apiUrl = 'https://api.github.com/repos/' . TIMELLOW_UPDATE_REPO . '/releases/latest';
    $body = timellow_http_get($apiUrl);
    $release = json_decode($body, true);

    if (!is_array($release) || empty($release['tag_name'])) {
        throw new RuntimeException('无法解析 GitHub 最新版本信息。');
    }

    $downloadUrl = '';
    if (!empty($release['assets']) && is_array($release['assets'])) {
        foreach ($release['assets'] as $asset) {
            if (!empty($asset['name']) && $asset['name'] === TIMELLOW_UPDATE_PACKAGE && !empty($asset['browser_download_url'])) {
                $downloadUrl = (string) $asset['browser_download_url'];
                break;
            }
        }
    }

    if ($downloadUrl === '') {
        $downloadUrl = 'https://github.com/' . TIMELLOW_UPDATE_REPO . '/releases/latest/download/' . TIMELLOW_UPDATE_PACKAGE;
    }

    $customPackageUrl = trim((string) timellow_option('updaterPackageUrl', ''));
    if ($customPackageUrl !== '' && filter_var($customPackageUrl, FILTER_VALIDATE_URL)) {
        $downloadUrl = $customPackageUrl;
    }

    return [
        'tag' => (string) $release['tag_name'],
        'version' => timellow_version_number($release['tag_name']),
        'url' => !empty($release['html_url']) ? (string) $release['html_url'] : 'https://github.com/' . TIMELLOW_UPDATE_REPO . '/releases',
        'downloadUrl' => $downloadUrl
    ];
}

function timellow_runtime_directory()
{
    return realpath(__DIR__) ?: __DIR__;
}

function timellow_create_backup($themeDir)
{
    if (!class_exists('ZipArchive')) {
        return '';
    }

    $backupDir = __TYPECHO_ROOT_DIR__ . '/usr/uploads/timellow-backups';
    if (!is_dir($backupDir) && !mkdir($backupDir, 0755, true)) {
        return '';
    }

    $backupPath = $backupDir . '/timellow-' . TIMELLOW_VERSION . '-' . date('Ymd-His') . '.zip';
    $zip = new ZipArchive();
    if ($zip->open($backupPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
        return '';
    }

    $themeDir = rtrim(str_replace('\\', '/', $themeDir), '/');
    $baseLength = strlen($themeDir) + 1;
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($themeDir, FilesystemIterator::SKIP_DOTS),
        RecursiveIteratorIterator::SELF_FIRST
    );

    foreach ($iterator as $file) {
        $path = str_replace('\\', '/', $file->getPathname());
        $relative = substr($path, $baseLength);

        if ($relative === '' || strpos($relative, '.git/') === 0 || strpos($relative, '.timellow-update-') === 0) {
            continue;
        }

        if ($file->isDir()) {
            $zip->addEmptyDir($relative);
        } elseif ($file->isFile()) {
            $zip->addFile($path, $relative);
        }
    }

    $zip->close();
    return is_file($backupPath) ? $backupPath : '';
}

function timellow_validate_zip($zip)
{
    for ($index = 0; $index < $zip->numFiles; $index++) {
        $name = (string) $zip->getNameIndex($index);
        $normalized = str_replace('\\', '/', $name);

        if ($normalized === '' || $normalized[0] === '/' || preg_match('/^[A-Za-z]:\//', $normalized) || strpos($normalized, '../') !== false || strpos($normalized, '/..') !== false) {
            throw new RuntimeException('更新包路径不安全，已停止解压。');
        }
    }
}

function timellow_find_package_root($extractDir)
{
    $items = array_values(array_filter(scandir($extractDir), static function ($item) {
        return $item !== '.' && $item !== '..' && $item !== '__MACOSX';
    }));

    if (count($items) === 1 && is_dir($extractDir . DIRECTORY_SEPARATOR . $items[0])) {
        return $extractDir . DIRECTORY_SEPARATOR . $items[0];
    }

    return $extractDir;
}

function timellow_copy_directory($source, $target)
{
    $source = rtrim($source, DIRECTORY_SEPARATOR);
    $target = rtrim($target, DIRECTORY_SEPARATOR);

    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($source, FilesystemIterator::SKIP_DOTS),
        RecursiveIteratorIterator::SELF_FIRST
    );

    foreach ($iterator as $file) {
        $sourcePath = $file->getPathname();
        $relative = substr($sourcePath, strlen($source) + 1);
        $targetPath = $target . DIRECTORY_SEPARATOR . $relative;

        if (strpos(str_replace('\\', '/', $relative), '.git/') === 0 || strpos(str_replace('\\', '/', $relative), '.github/') === 0) {
            continue;
        }

        if ($file->isDir()) {
            if (!is_dir($targetPath) && !mkdir($targetPath, 0755, true)) {
                throw new RuntimeException('无法创建目录：' . $relative);
            }
        } elseif ($file->isFile()) {
            $targetDir = dirname($targetPath);
            if (!is_dir($targetDir) && !mkdir($targetDir, 0755, true)) {
                throw new RuntimeException('无法创建目录：' . $relative);
            }

            if (!copy($sourcePath, $targetPath)) {
                throw new RuntimeException('无法写入文件：' . $relative);
            }
        }
    }
}

function timellow_remove_directory($path)
{
    if (!is_dir($path)) {
        return;
    }

    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS),
        RecursiveIteratorIterator::CHILD_FIRST
    );

    foreach ($iterator as $file) {
        if ($file->isDir()) {
            @rmdir($file->getPathname());
        } else {
            @unlink($file->getPathname());
        }
    }

    @rmdir($path);
}

function timellow_install_latest_release()
{
    if (!class_exists('ZipArchive')) {
        throw new RuntimeException('服务器未启用 ZipArchive，无法在线解压更新包。');
    }

    $customPackageUrl = trim((string) timellow_option('updaterPackageUrl', ''));
    $hasCustomPackage = $customPackageUrl !== '' && filter_var($customPackageUrl, FILTER_VALIDATE_URL);

    if ($hasCustomPackage) {
        $latest = [
            'tag' => '自定义更新包',
            'version' => TIMELLOW_VERSION,
            'downloadUrl' => $customPackageUrl
        ];
    } else {
        $latest = timellow_latest_release();
    }

    if (version_compare($latest['version'], TIMELLOW_VERSION, '<=')) {
        if (!$hasCustomPackage) {
            return '当前已是最新版本：' . TIMELLOW_VERSION . '。';
        }
    }

    $themeDir = timellow_runtime_directory();
    if (!is_writable($themeDir)) {
        throw new RuntimeException('当前主题目录不可写，无法在线更新：' . $themeDir);
    }

    $tmpBase = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'timellow-update-' . uniqid('', true);
    if (!mkdir($tmpBase, 0755, true)) {
        throw new RuntimeException('无法创建临时更新目录。');
    }

    $zipPath = $tmpBase . DIRECTORY_SEPARATOR . TIMELLOW_UPDATE_PACKAGE;
    $extractDir = $tmpBase . DIRECTORY_SEPARATOR . 'extract';

    try {
        timellow_download_update_package($latest['downloadUrl'], $zipPath);

        if (!is_file($zipPath) || filesize($zipPath) < 1) {
            throw new RuntimeException('更新包下载失败或文件为空。');
        }

        if (!mkdir($extractDir, 0755, true)) {
            throw new RuntimeException('无法创建解压目录。');
        }

        $zip = new ZipArchive();
        if ($zip->open($zipPath) !== true) {
            throw new RuntimeException('无法打开更新包。');
        }

        timellow_validate_zip($zip);
        if (!$zip->extractTo($extractDir)) {
            $zip->close();
            throw new RuntimeException('更新包解压失败。');
        }
        $zip->close();

        $packageRoot = timellow_find_package_root($extractDir);
        if (!is_file($packageRoot . DIRECTORY_SEPARATOR . 'index.php') || !is_file($packageRoot . DIRECTORY_SEPARATOR . 'functions.php')) {
            throw new RuntimeException('更新包不是有效的 Timellow 主题。');
        }

        $backupPath = timellow_create_backup($themeDir);
        timellow_copy_directory($packageRoot, $themeDir);

        return '已更新到 ' . $latest['tag'] . ($hasCustomPackage ? '（使用自定义更新包）' : '') . ($backupPath !== '' ? '，已备份当前主题到：' . $backupPath : '。');
    } finally {
        timellow_remove_directory($tmpBase);
    }
}

function timellow_download_update_package($url, $zipPath)
{
    $lastException = null;

    for ($attempt = 1; $attempt <= 3; $attempt++) {
        try {
            if (is_file($zipPath)) {
                @unlink($zipPath);
            }

            timellow_http_get($url, ['Accept: application/octet-stream'], 120, $zipPath);
            return;
        } catch (Throwable $exception) {
            $lastException = $exception;
            if ($attempt < 3) {
                sleep(1);
            }
        }
    }

    throw new RuntimeException($lastException ? $lastException->getMessage() : '更新包下载失败。');
}

function timellow_handle_update_request()
{
    $options = \Widget\Options::alloc();
    $request = $options->request;

    if (!$request->is('timellow_update')) {
        return;
    }

    \Widget\User::alloc()->pass('administrator');
    \Widget\Security::alloc()->protect();

    $action = trim((string) $request->get('timellow_update'));

    try {
        if ($action === 'check') {
            $latest = timellow_latest_release();
            if (version_compare($latest['version'], TIMELLOW_VERSION, '>')) {
                timellow_update_notice('发现新版本 ' . $latest['tag'] . '，当前版本 ' . TIMELLOW_VERSION . '。可点击“在线更新”安装。', 'success');
            } else {
                timellow_update_notice('当前已是最新版本：' . TIMELLOW_VERSION . '。', 'notice');
            }
        } elseif ($action === 'install') {
            timellow_update_notice(timellow_install_latest_release(), 'success');
        } else {
            timellow_update_notice('未知的更新操作。', 'error');
        }
    } catch (Throwable $exception) {
        timellow_update_notice('在线更新失败：' . $exception->getMessage(), 'error');
    }

    $options->response->redirect(timellow_admin_url('options-theme.php'));
    exit;
}

function timellow_add_update_panel($form)
{
    $checkUrl = timellow_update_action_url('check');
    $installUrl = timellow_update_action_url('install');
    $repoUrl = 'https://github.com/' . TIMELLOW_UPDATE_REPO . '/releases';
    $themeDir = timellow_runtime_directory();
    $writable = is_writable($themeDir);

    $html = '<li><label class="typecho-label">在线更新</label></li>'
        . '<li><p class="description">当前版本：<strong>' . timellow_escape(TIMELLOW_VERSION) . '</strong>。更新源：<a href="' . timellow_escape($repoUrl) . '" target="_blank" rel="noopener noreferrer">' . timellow_escape(TIMELLOW_UPDATE_REPO) . '</a>。</p>'
        . '<p><a class="btn" href="' . timellow_escape($checkUrl) . '">检查更新</a> '
        . '<a class="btn primary" href="' . timellow_escape($installUrl) . '" onclick="return confirm(\'将从 GitHub 下载 timellow.zip 并覆盖当前主题文件，继续吗？\');">在线更新</a></p>'
        . (!$writable ? '<p class="description" style="color:#c00;">当前主题目录不可写，在线更新前需要给目录写入权限：' . timellow_escape($themeDir) . '</p>' : '')
        . '<p class="description">更新前会尝试备份当前主题到 <code>usr/uploads/timellow-backups</code>。不会删除你额外添加的文件，但同名主题文件会被覆盖。GitHub 下载慢时，可在下方“更新包下载地址”填写代理后的 <code>timellow.zip</code> 直链。</p></li>';

    $panel = new \Typecho\Widget\Helper\Layout('ul', [
        'class' => 'typecho-option',
        'id' => 'typecho-option-item-timellow-update'
    ]);
    $panel->html($html);
    $form->addItem($panel);
}

function timellow_article_font_stack()
{
    $fallback = '"PingFang SC", "Microsoft YaHei", sans-serif';
    $mode = trim((string) timellow_option('articleFontMode', 'lxgw'));

    if ($mode === 'custom') {
        $customName = timellow_sanitize_css_text((string) timellow_option('customArticleFontName', ''));
        $customUrl = trim((string) timellow_option('customArticleFontUrl', ''));

        if ($customName === '' && $customUrl !== '') {
            $customName = 'Timellow Custom Article';
        }

        if ($customName !== '') {
            return '"' . addcslashes($customName, "\"\\") . '", ' . $fallback;
        }
    }

    return '"Timellow LXGW", "LXGW WenKai", ' . $fallback;
}

function timellow_custom_article_font_face()
{
    $mode = trim((string) timellow_option('articleFontMode', 'lxgw'));
    if ($mode !== 'custom') {
        return '';
    }

    $fontUrl = trim((string) timellow_option('customArticleFontUrl', ''));
    if ($fontUrl === '' || !filter_var($fontUrl, FILTER_VALIDATE_URL)) {
        return '';
    }

    $fontName = timellow_sanitize_css_text((string) timellow_option('customArticleFontName', ''));
    if ($fontName === '') {
        $fontName = 'Timellow Custom Article';
    }

    $path = (string) parse_url($fontUrl, PHP_URL_PATH);
    $extension = strtolower((string) pathinfo($path, PATHINFO_EXTENSION));
    $formatMap = [
        'woff2' => 'woff2',
        'woff' => 'woff',
        'ttf' => 'truetype',
        'otf' => 'opentype'
    ];
    $format = isset($formatMap[$extension]) ? $formatMap[$extension] : 'woff2';

    return '@font-face {' . "\n"
        . '  font-family: "' . addcslashes($fontName, "\"\\") . '";' . "\n"
        . '  src: url("' . addcslashes($fontUrl, "\"\\") . '") format("' . $format . '");' . "\n"
        . '  font-style: normal;' . "\n"
        . '  font-weight: 400;' . "\n"
        . '  font-display: swap;' . "\n"
        . '}';
}

function timellow_article_font_style_block()
{
    $rules = [];
    $fontFace = timellow_custom_article_font_face();

    if ($fontFace !== '') {
        $rules[] = $fontFace;
    }

    $rules[] = ':root {' . "\n"
        . '  --site-font-family: ' . timellow_article_font_stack() . ';' . "\n"
        . '  --article-font-family: ' . timellow_article_font_stack() . ';' . "\n"
        . '}';
    return implode("\n\n", $rules);
}

function timellow_site_subtitle()
{
    $subtitle = trim((string) timellow_option('subtitle', ''));
    if ($subtitle !== '') {
        return $subtitle;
    }

    $options = \Typecho\Widget::widget('Widget_Options');
    return trim((string) $options->description);
}

function timellow_document_title($archive)
{
    $siteTitle = timellow_site_title();

    if ($archive->is('index')) {
        return $siteTitle;
    }

    $title = '';
    if ($archive->is('post') || $archive->is('page') || $archive->is('attachment')) {
        $title = trim((string) $archive->title);
    } else {
        $title = timellow_capture(function () use ($archive) {
            $archive->archiveTitle([
                'category' => _t('分类 %s'),
                'search'   => _t('搜索 %s'),
                'tag'      => _t('标签 %s'),
                'author'   => _t('作者 %s')
            ], '', '');
        });
    }

    if ($title === '') {
        $title = trim((string) $archive->title);
    }

    if ($siteTitle === '') {
        return $title;
    }

    return $title !== '' ? $title . ' - ' . $siteTitle : $siteTitle;
}

function timellow_normalize_text($text)
{
    $text = html_entity_decode((string) $text, ENT_QUOTES, 'UTF-8');
    $text = preg_replace('/\s+/u', ' ', strip_tags($text));
    return trim((string) $text);
}

function timellow_summary($archive, $length = 110, $default = '')
{
    $summary = '';

    if (isset($archive->fields) && !empty($archive->fields->summary)) {
        $summary = timellow_normalize_text($archive->fields->summary);
    }

    if ($summary === '' && !empty($archive->plainExcerpt)) {
        $summary = timellow_normalize_text($archive->plainExcerpt);
    }

    if ($summary === '') {
        $summary = timellow_normalize_text($archive->excerpt ?: $archive->content);
    }

    if ($summary === '') {
        $summary = $default !== '' ? $default : trim((string) $archive->title);
    }

    return \Typecho\Common::subStr($summary, 0, (int) $length, '...');
}

function timellow_first_character($text)
{
    $text = trim((string) $text);
    if ($text === '') {
        return 'T';
    }

    return function_exists('mb_substr')
        ? mb_strtoupper(mb_substr($text, 0, 1, 'UTF-8'), 'UTF-8')
        : strtoupper(substr($text, 0, 1));
}

function timellow_cover_pool()
{
    static $pool = null;

    if ($pool !== null) {
        return $pool;
    }

    $pool = [];
    $coverDir = __DIR__ . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'cover';
    if (!is_dir($coverDir)) {
        return $pool;
    }

    $extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'avif'];
    $items = @scandir($coverDir);
    if ($items === false) {
        return $pool;
    }

    $baseUrl = rtrim((string) \Typecho\Widget::widget('Widget_Options')->themeUrl, '/');

    foreach ($items as $item) {
        if ($item === '.' || $item === '..') {
            continue;
        }

        $fullPath = $coverDir . DIRECTORY_SEPARATOR . $item;
        if (!is_file($fullPath)) {
            continue;
        }

        $extension = strtolower((string) pathinfo($item, PATHINFO_EXTENSION));
        if (!in_array($extension, $extensions, true)) {
            continue;
        }

        $pool[] = $baseUrl . '/assets/cover/' . rawurlencode($item);
    }

    sort($pool, SORT_NATURAL | SORT_FLAG_CASE);
    return $pool;
}

function timellow_random_cover($archive)
{
    $pool = timellow_cover_pool();
    if (empty($pool)) {
        return '';
    }

    $seed = '';
    if (isset($archive->cid)) {
        $seed = (string) $archive->cid;
    } elseif (isset($archive->slug)) {
        $seed = (string) $archive->slug;
    } else {
        $seed = (string) $archive->title;
    }

    $index = abs((int) crc32($seed)) % count($pool);
    return $pool[$index];
}

function timellow_post_cid($archive)
{
    return isset($archive->cid) ? (int) $archive->cid : 0;
}

function timellow_field_row_value($row)
{
    if (empty($row) || !is_array($row)) {
        return '';
    }

    $type = isset($row['type']) ? (string) $row['type'] : 'str';
    if ($type === 'json') {
        return isset($row['str_value']) ? (string) $row['str_value'] : '';
    }

    $column = $type . '_value';
    return isset($row[$column]) ? (string) $row[$column] : '';
}

function timellow_post_field_value($cid, $name)
{
    static $cache = [];

    $cid = (int) $cid;
    $name = (string) $name;

    if ($cid <= 0 || $name === '') {
        return '';
    }

    $cacheKey = $cid . ':' . $name;
    if (array_key_exists($cacheKey, $cache)) {
        return $cache[$cacheKey];
    }

    try {
        $db = \Typecho\Db::get();
        $row = $db->fetchRow($db->select()
            ->from('table.fields')
            ->where('cid = ?', $cid)
            ->where('name = ?', $name)
            ->limit(1));

        if (empty($row)) {
            $cache[$cacheKey] = '';
            return '';
        }

        $cache[$cacheKey] = trim(timellow_field_row_value($row));
        return $cache[$cacheKey];
    } catch (Throwable $exception) {
        $cache[$cacheKey] = '';
        return '';
    }
}

function timellow_post_raw_text($cid)
{
    static $cache = [];

    $cid = (int) $cid;
    if ($cid <= 0) {
        return '';
    }

    if (array_key_exists($cid, $cache)) {
        return $cache[$cid];
    }

    try {
        $db = \Typecho\Db::get();
        $row = $db->fetchRow($db->select('table.contents.text')
            ->from('table.contents')
            ->where('table.contents.cid = ?', $cid)
            ->limit(1));

        $cache[$cid] = isset($row['text']) ? (string) $row['text'] : '';
        return $cache[$cid];
    } catch (Throwable $exception) {
        $cache[$cid] = '';
        return '';
    }
}

function timellow_extract_first_image($text)
{
    $text = (string) $text;

    if (preg_match('/<img[^>]+src=[\'"]([^\'"]+)[\'"][^>]*>/i', $text, $matches)) {
        return trim((string) html_entity_decode($matches[1], ENT_QUOTES, 'UTF-8'));
    }

    if (preg_match('/!\[[^\]]*\]\((\S+?)(?:\s+[\'"][^\'"]*[\'"])?\)/u', $text, $matches)) {
        return trim((string) $matches[1], " \t\n\r\0\x0B<>\"'");
    }

    return '';
}

function timellow_post_cover($archive)
{
    $cid = timellow_post_cid($archive);

    if ($cid > 0) {
        $cover = timellow_post_field_value($cid, 'cover');
        if ($cover !== '') {
            return $cover;
        }

        $cover = timellow_extract_first_image(timellow_post_raw_text($cid));
        if ($cover !== '') {
            return $cover;
        }
    }

    if (isset($archive->text)) {
        $cover = timellow_extract_first_image((string) $archive->text);
        if ($cover !== '') {
            return $cover;
        }
    }

    $defaultCover = trim((string) timellow_option('defaultCover', ''));
    if ($defaultCover !== '') {
        return $defaultCover;
    }

    return timellow_random_cover($archive);
}

function timellow_truthy($value)
{
    if (is_array($value)) {
        foreach ($value as $key => $item) {
            if (!is_int($key) && timellow_truthy($key)) {
                return true;
            }

            if (timellow_truthy($item)) {
                return true;
            }
        }

        return false;
    }

    $value = trim((string) $value);
    if ($value === '') {
        return false;
    }

    $decodedJson = json_decode($value, true);
    if (json_last_error() === JSON_ERROR_NONE && is_array($decodedJson)) {
        return timellow_truthy($decodedJson);
    }

    $decoded = @unserialize($value);
    if ($decoded !== false || $value === 'b:0;') {
        return timellow_truthy($decoded);
    }

    $value = strtolower($value);
    if (in_array($value, ['0', 'false', 'off', 'no', 'normal', 'none'], true)) {
        return false;
    }

    return in_array($value, ['1', 'true', 'on', 'yes', 'sticky'], true);
}

function timellow_is_sticky_post($archive)
{
    $cid = timellow_post_cid($archive);
    return $cid > 0 && timellow_truthy(timellow_post_field_value($cid, 'sticky'));
}

function timellow_sticky_post_cids()
{
    static $cache = null;

    if ($cache !== null) {
        return $cache;
    }

    $cache = [];

    try {
        $db = \Typecho\Db::get();
        $options = \Typecho\Widget::widget('Widget_Options');
        $rows = $db->fetchAll($db->select(
            'table.contents.cid',
            'table.fields.type',
            'table.fields.str_value',
            'table.fields.int_value',
            'table.fields.float_value'
        )
            ->from('table.contents')
            ->join('table.fields', 'table.fields.cid = table.contents.cid', \Typecho\Db::INNER_JOIN)
            ->where('table.contents.status = ?', 'publish')
            ->where('table.contents.created < ?', $options->time)
            ->where('table.contents.type = ?', 'post')
            ->where('table.fields.name = ?', 'sticky')
            ->order('table.contents.created', \Typecho\Db::SORT_DESC));

        foreach ($rows as $row) {
            $cid = isset($row['cid']) ? (int) $row['cid'] : 0;

            if ($cid > 0 && timellow_truthy(timellow_field_row_value($row))) {
                $cache[$cid] = $cid;
            }
        }

        $cache = array_values($cache);
    } catch (Throwable $exception) {
        $cache = [];
    }

    return $cache;
}

function timellow_archive_has_duplicate_posts($archive)
{
    if (!$archive || !method_exists($archive, 'next') || !method_exists($archive, 'have') || !$archive->have()) {
        return false;
    }

    $seen = [];
    $hasDuplicate = false;

    while ($archive->next()) {
        $cid = isset($archive->cid) ? (int) $archive->cid : 0;

        if ($cid <= 0) {
            continue;
        }

        if (isset($seen[$cid])) {
            $hasDuplicate = true;
            continue;
        }

        $seen[$cid] = true;
    }

    return $hasDuplicate;
}

function timellow_post_cids_query($limit, $offset, array $excludeCids = [])
{
    $limit = max(0, (int) $limit);
    $offset = max(0, (int) $offset);

    if ($limit < 1) {
        return [];
    }

    try {
        $db = \Typecho\Db::get();
        $options = \Typecho\Widget::widget('Widget_Options');
        $excludeMap = !empty($excludeCids) ? array_fill_keys(array_map('intval', $excludeCids), true) : [];
        $query = $db->select('table.contents.cid')
            ->from('table.contents')
            ->where('table.contents.status = ?', 'publish')
            ->where('table.contents.created < ?', $options->time)
            ->where('table.contents.type = ?', 'post')
            ->order('table.contents.created', \Typecho\Db::SORT_DESC);

        $rows = $db->fetchAll($query);
        $cids = [];

        foreach ($rows as $row) {
            $cid = isset($row['cid']) ? (int) $row['cid'] : 0;

            if ($cid > 0 && !isset($excludeMap[$cid])) {
                $cids[] = $cid;
            }
        }

        return array_slice($cids, $offset, $limit);
    } catch (Throwable $exception) {
        return [];
    }
}

function timellow_posts_by_cids(array $cids, $alias)
{
    $cids = array_values(array_unique(array_filter(array_map('intval', $cids))));

    if (empty($cids)) {
        return \Widget\Contents\From::allocWithAlias($alias . '_empty', ['cid' => -1]);
    }

    $db = \Typecho\Db::get();
    $query = $db->select('table.contents.*')
        ->from('table.contents')
        ->where('table.contents.cid IN ?', $cids);
    $rows = $db->fetchAll($query);
    $rowsByCid = [];

    foreach ($rows as $row) {
        if (isset($row['cid'])) {
            $rowsByCid[(int) $row['cid']] = $row;
        }
    }

    $widget = \Widget\Contents\From::allocWithAlias($alias . '_empty_' . md5(implode(',', $cids)), ['cid' => -1]);

    foreach ($cids as $cid) {
        if (isset($rowsByCid[$cid])) {
            $widget->push($rowsByCid[$cid]);
        }
    }

    return $widget;
}

function timellow_sticky_index_posts($archive)
{
    $pageSize = isset($archive->parameter->pageSize) ? (int) $archive->parameter->pageSize : 10;
    $pageSize = $pageSize > 0 ? $pageSize : 10;
    $currentPage = method_exists($archive, 'getCurrentPage') ? (int) $archive->getCurrentPage() : 1;
    $currentPage = $currentPage > 0 ? $currentPage : 1;
    $start = ($currentPage - 1) * $pageSize;
    $stickyCids = timellow_sticky_post_cids();
    $stickyCount = count($stickyCids);
    $pageStickyCids = array_slice($stickyCids, $start, $pageSize);
    $normalLimit = $pageSize - count($pageStickyCids);
    $normalOffset = max(0, $start - $stickyCount);
    $normalCids = timellow_post_cids_query($normalLimit, $normalOffset, $stickyCids);
    $cids = array_merge($pageStickyCids, $normalCids);

    return timellow_posts_by_cids($cids, 'timellow_sticky_index_' . $currentPage . '_' . $pageSize);
}

function timellow_index_posts_source($archive)
{
    if (!$archive || !method_exists($archive, 'is') || !$archive->is('index')) {
        return $archive;
    }

    if (!empty(timellow_sticky_post_cids()) || timellow_archive_has_duplicate_posts($archive)) {
        return timellow_sticky_index_posts($archive);
    }

    return $archive;
}

function timellow_archive_heading($archive)
{
    $total = method_exists($archive, 'getTotal') ? (int) $archive->getTotal() : 0;
    $title = _t('文章');
    $description = timellow_site_subtitle();

    if ($archive->is('index')) {
        $title = _t('最新文章');
        $description = timellow_site_subtitle();
    } elseif ($archive->is('category')) {
        $title = timellow_capture(function () use ($archive) {
            $archive->archiveTitle(['category' => _t('分类 · %s')], '', '');
        });
        $description = $total > 0 ? sprintf(_t('当前分类下共有 %d 篇文章。'), $total) : _t('这个分类暂时还没有内容。');
    } elseif ($archive->is('tag')) {
        $title = timellow_capture(function () use ($archive) {
            $archive->archiveTitle(['tag' => _t('标签 · %s')], '', '');
        });
        $description = $total > 0 ? sprintf(_t('这个标签下共有 %d 篇文章。'), $total) : _t('这个标签暂时还没有内容。');
    } elseif ($archive->is('search')) {
        $title = timellow_capture(function () use ($archive) {
            $archive->archiveTitle(['search' => _t('搜索 · %s')], '', '');
        });
        $description = $total > 0 ? sprintf(_t('共找到 %d 篇相关内容。'), $total) : _t('没有找到匹配的内容。');
    } elseif ($archive->is('author')) {
        $title = timellow_capture(function () use ($archive) {
            $archive->archiveTitle(['author' => _t('作者 · %s')], '', '');
        });
        $description = $total > 0 ? sprintf(_t('这位作者已发布 %d 篇文章。'), $total) : _t('这位作者暂时还没有公开内容。');
    } else {
        $title = timellow_capture(function () use ($archive) {
            $archive->archiveTitle([], '', '');
        });
        if ($title === '') {
            $title = _t('归档');
        }
        $description = $total > 0 ? sprintf(_t('共收录 %d 篇文章。'), $total) : _t('这里暂时还没有内容。');
    }

    return [
        'title' => $title !== '' ? $title : _t('归档'),
        'description' => $description
    ];
}

function timellow_get_page_template($widget)
{
    try {
        $reflection = new ReflectionClass($widget);
        if (!$reflection->hasProperty('pageRow')) {
            return null;
        }

        $property = $reflection->getProperty('pageRow');
        $property->setAccessible(true);
        $pageRow = $property->getValue($widget);
        $type = isset($widget->parameter->type) ? (string) $widget->parameter->type : 'index';

        if (strpos($type, '_page') === false) {
            $type .= '_page';
        }

        $indexBase = isset($widget->options->index) ? (string) $widget->options->index : '';
        return \Typecho\Router::url($type, $pageRow, $indexBase);
    } catch (Throwable $exception) {
        return null;
    }
}

function timellow_page_url($widget, $page)
{
    $page = (int) $page;
    if ($page <= 1 && method_exists($widget, 'getArchiveUrl')) {
        $firstUrl = $widget->getArchiveUrl();
        if (!empty($firstUrl)) {
            return $firstUrl;
        }
    }

    $template = timellow_get_page_template($widget);
    if (!$template) {
        return null;
    }

    return str_replace(['{page}', '%7Bpage%7D'], (string) $page, $template);
}

function timellow_article_list_paging_mode()
{
    $mode = trim((string) timellow_option('articleListPagingMode', 'pagination'));
    return in_array($mode, ['pagination', 'loadmore'], true) ? $mode : 'pagination';
}

function timellow_pagination_state($widget)
{
    $total = method_exists($widget, 'getTotal') ? (int) $widget->getTotal() : 0;
    $pageSize = isset($widget->parameter->pageSize) ? (int) $widget->parameter->pageSize : 10;
    $totalPages = $pageSize > 0 ? (int) ceil($total / $pageSize) : 1;
    $current = method_exists($widget, 'getCurrentPage') ? (int) $widget->getCurrentPage() : 1;
    $current = $current > 0 ? $current : 1;

    return [
        'total' => $total,
        'pageSize' => $pageSize,
        'totalPages' => $totalPages,
        'current' => $current,
        'prevUrl' => $current > 1 ? timellow_page_url($widget, $current - 1) : null,
        'nextUrl' => $current < $totalPages ? timellow_page_url($widget, $current + 1) : null
    ];
}

function timellow_render_pagination($widget)
{
    $state = timellow_pagination_state($widget);
    $totalPages = (int) $state['totalPages'];

    if ($totalPages <= 1) {
        return;
    }

    $current = (int) $state['current'];
    $prevUrl = $state['prevUrl'];
    $nextUrl = $state['nextUrl'];

    if (timellow_article_list_paging_mode() === 'loadmore') {
        if (empty($nextUrl)) {
            return;
        }

        echo '<div class="load-more" data-load-more>';
        echo '<a class="load-more-btn" href="' . htmlspecialchars((string) $nextUrl, ENT_QUOTES, 'UTF-8') . '" rel="next" data-load-more-trigger data-next-url="' . htmlspecialchars((string) $nextUrl, ENT_QUOTES, 'UTF-8') . '" data-default-text="' . htmlspecialchars(_t('加载更多'), ENT_QUOTES, 'UTF-8') . '" data-loading-text="' . htmlspecialchars(_t('加载中...'), ENT_QUOTES, 'UTF-8') . '" data-error-text="' . htmlspecialchars(_t('加载失败，点击重试'), ENT_QUOTES, 'UTF-8') . '">';
        echo '<span data-load-more-label>' . htmlspecialchars(_t('加载更多'), ENT_QUOTES, 'UTF-8') . '</span>';
        echo '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="6 9 12 15 18 9"></polyline></svg>';
        echo '</a>';
        echo '</div>';
        return;
    }

    echo '<nav class="pagination" aria-label="' . htmlspecialchars(_t('分页导航'), ENT_QUOTES, 'UTF-8') . '">';
    echo $prevUrl
        ? '<a class="page-link" href="' . htmlspecialchars($prevUrl, ENT_QUOTES, 'UTF-8') . '">' . htmlspecialchars(_t('上一页'), ENT_QUOTES, 'UTF-8') . '</a>'
        : '<span class="page-link is-disabled">' . htmlspecialchars(_t('上一页'), ENT_QUOTES, 'UTF-8') . '</span>';
    echo '<span class="page-status">' . sprintf(_t('第 %1$d / %2$d 页'), $current, $totalPages) . '</span>';
    echo $nextUrl
        ? '<a class="page-link" href="' . htmlspecialchars($nextUrl, ENT_QUOTES, 'UTF-8') . '">' . htmlspecialchars(_t('下一页'), ENT_QUOTES, 'UTF-8') . '</a>'
        : '<span class="page-link is-disabled">' . htmlspecialchars(_t('下一页'), ENT_QUOTES, 'UTF-8') . '</span>';
    echo '</nav>';
}

function timellow_link_host($url)
{
    $host = parse_url((string) $url, PHP_URL_HOST);
    if (!$host) {
        return '';
    }

    return preg_replace('/^www\./i', '', $host);
}

function timellow_parse_links_text($text)
{
    $groups = [];
    $currentGroup = _t('友情链接');
    $lines = preg_split('/\R/u', (string) $text);

    foreach ($lines as $line) {
        $line = trim((string) $line);
        if ($line === '') {
            continue;
        }

        if (strpos($line, '#') === 0) {
            $currentGroup = trim(ltrim($line, '# '));
            if ($currentGroup === '') {
                $currentGroup = _t('友情链接');
            }
            continue;
        }

        $parts = preg_split('/\s*\|\s*/u', $line);
        if (count($parts) < 2) {
            continue;
        }

        $name = trim((string) $parts[0]);
        $url = trim((string) $parts[1]);
        $description = isset($parts[2]) ? trim((string) $parts[2]) : '';
        $image = isset($parts[3]) ? trim((string) $parts[3]) : '';

        if ($name === '' || $url === '') {
            continue;
        }

        if (!isset($groups[$currentGroup])) {
            $groups[$currentGroup] = [];
        }

        $groups[$currentGroup][] = [
            'name' => $name,
            'url' => $url,
            'description' => $description,
            'image' => $image,
            'host' => timellow_link_host($url)
        ];
    }

    return $groups;
}

function timellow_links_block_pattern()
{
    return '/<!--\s*timellow-links-start\s*-->(.*?)<!--\s*timellow-links-end\s*-->/is';
}

function timellow_parse_links_page_text($text)
{
    $text = (string) $text;

    if (preg_match(timellow_links_block_pattern(), $text, $matches)) {
        $groups = timellow_parse_links_text((string) $matches[1]);
        if (!empty($groups)) {
            return [
                'groups' => $groups,
                'source' => 'page-comment'
            ];
        }
    }

    $groups = timellow_parse_links_text($text);
    if (!empty($groups)) {
        return [
            'groups' => $groups,
            'source' => 'page-raw'
        ];
    }

    return [
        'groups' => [],
        'source' => ''
    ];
}

function timellow_links_page_has_intro($text)
{
    $content = preg_replace(timellow_links_block_pattern(), '', (string) $text);
    $content = trim((string) strip_tags((string) $content));
    return $content !== '';
}

function timellow_fetch_friend_links($pageText = '')
{
    static $cache = [];
    $cacheKey = md5((string) $pageText);

    if (isset($cache[$cacheKey])) {
        return $cache[$cacheKey];
    }

    $result = [
        'groups' => [],
        'source' => '',
        'error'  => ''
    ];

    try {
        $db = Typecho_Db::get();
        $rows = $db->fetchAll(
            $db->select('name', 'url', 'sort', 'description', 'image', 'state', 'order')
                ->from('table.links')
                ->where('state = ?', 1)
                ->order('sort', Typecho_Db::SORT_ASC)
                ->order('order', Typecho_Db::SORT_ASC)
        );

        foreach ($rows as $row) {
            $group = trim((string) ($row['sort'] ?? ''));
            $group = $group !== '' ? $group : _t('友情链接');
            if (!isset($result['groups'][$group])) {
                $result['groups'][$group] = [];
            }

            $url = trim((string) ($row['url'] ?? ''));
            if ($url === '') {
                continue;
            }

            $result['groups'][$group][] = [
                'name' => trim((string) ($row['name'] ?? '')),
                'url' => $url,
                'description' => trim((string) ($row['description'] ?? '')),
                'image' => trim((string) ($row['image'] ?? '')),
                'host' => timellow_link_host($url)
            ];
        }

        if (!empty($result['groups'])) {
            $result['source'] = 'database';
            $cache[$cacheKey] = $result;
            return $result;
        }
    } catch (Exception $exception) {
        $result['error'] = $exception->getMessage();
    }

    $pageLinks = timellow_parse_links_page_text($pageText);
    if (!empty($pageLinks['groups'])) {
        $result['groups'] = $pageLinks['groups'];
        $result['source'] = (string) $pageLinks['source'];
    }

    $cache[$cacheKey] = $result;
    return $result;
}

function timellow_moment_safe_url($url)
{
    $url = trim((string) $url);
    if ($url === '') {
        return '';
    }

    if (strpos($url, '//') === 0) {
        return $url;
    }

    $scheme = parse_url($url, PHP_URL_SCHEME);
    if ($scheme === null || in_array(strtolower((string) $scheme), ['http', 'https'], true)) {
        return $url;
    }

    return '';
}

function timellow_moment_media_type($type, $url)
{
    $type = strtoupper(trim((string) $type));
    if ($type !== '') {
        if (in_array($type, ['PHOTO', 'IMAGE', 'IMG', 'PICTURE'], true)) {
            return 'PHOTO';
        }

        if (in_array($type, ['VIDEO', 'MOVIE'], true)) {
            return 'VIDEO';
        }

        return $type;
    }

    $path = (string) parse_url((string) $url, PHP_URL_PATH);
    $extension = strtolower((string) pathinfo($path, PATHINFO_EXTENSION));

    if (in_array($extension, ['mp4', 'webm', 'ogg'], true)) {
        return 'VIDEO';
    }

    return 'PHOTO';
}

function timellow_parse_moment_media($media)
{
    $media = trim((string) $media);
    if ($media === '') {
        return [];
    }

    $decoded = json_decode($media, true);
    if (!is_array($decoded)) {
        $decoded = preg_split('/\R|,/u', $media);
    }

    $items = [];
    foreach ($decoded as $item) {
        if (is_string($item)) {
            $url = timellow_moment_safe_url($item);
            if ($url !== '') {
                $items[] = [
                    'type' => timellow_moment_media_type('', $url),
                    'url' => $url
                ];
            }
            continue;
        }

        if (!is_array($item)) {
            continue;
        }

        $url = timellow_moment_safe_url($item['url'] ?? ($item['src'] ?? ''));
        if ($url === '') {
            continue;
        }

        $items[] = [
            'type' => timellow_moment_media_type($item['type'] ?? '', $url),
            'url' => $url
        ];
    }

    return $items;
}

function timellow_parse_moment_tags($tags)
{
    $tags = trim((string) $tags);
    if ($tags === '') {
        return [];
    }

    $decoded = json_decode($tags, true);
    $rawTags = is_array($decoded) ? $decoded : preg_split('/[,，;；|]+/u', $tags);
    $result = [];

    foreach ($rawTags as $tag) {
        $tag = trim((string) $tag);
        $tag = trim($tag, "# \t\n\r\0\x0B");
        if ($tag !== '' && !in_array($tag, $result, true)) {
            $result[] = $tag;
        }
    }

    return $result;
}

function timellow_moment_content_html($content)
{
    $content = trim((string) $content);
    if ($content === '') {
        return '';
    }

    $escaped = htmlspecialchars($content, ENT_QUOTES, 'UTF-8');
    $escaped = preg_replace_callback('/https?:\/\/[^\s<]+/i', function ($matches) {
        $url = html_entity_decode((string) $matches[0], ENT_QUOTES, 'UTF-8');
        return '<a href="' . htmlspecialchars($url, ENT_QUOTES, 'UTF-8') . '" target="_blank" rel="noopener nofollow">' . $matches[0] . '</a>';
    }, $escaped);

    return nl2br((string) $escaped, false);
}

function timellow_moment_source_profile($source)
{
    $source = trim((string) $source);
    if ($source === '') {
        return [
            'label' => '',
            'icon' => ''
        ];
    }

    $normalized = strtolower($source);
    $labels = [
        'web' => _t('网页'),
        'browser' => _t('网页'),
        'chrome' => 'Chrome',
        'edge' => 'Edge',
        'firefox' => 'Firefox',
        'safari' => 'Safari',
        'api' => 'API',
        'bot' => _t('机器人'),
        'server' => _t('服务器'),
        'cli' => 'CLI',
        'ios' => 'iOS',
        'iphone' => 'iPhone',
        'ipad' => 'iPad',
        'android' => 'Android',
        'app' => 'App',
        'mobile' => _t('手机'),
        'phone' => _t('手机'),
        'tablet' => _t('平板'),
        'wechat' => _t('微信'),
        'weixin' => _t('微信'),
        'pc' => _t('电脑'),
        'desktop' => _t('电脑'),
        'windows' => 'Windows',
        'mac' => 'Mac',
        'macos' => 'macOS'
    ];

    $icon = 'monitor';
    if (preg_match('/api|bot|server|cli|terminal/u', $normalized)) {
        $icon = 'terminal';
    } elseif (preg_match('/ipad|tablet/u', $normalized)) {
        $icon = 'tablet';
    } elseif (preg_match('/ios|iphone|android|app|mobile|phone|wechat|weixin/u', $normalized)) {
        $icon = 'phone';
    }

    return [
        'label' => $labels[$normalized] ?? $source,
        'icon' => $icon
    ];
}

function timellow_moment_source_icon($icon)
{
    switch ($icon) {
        case 'phone':
            return '<svg viewBox="0 0 24 24" aria-hidden="true"><rect x="7" y="2.8" width="10" height="18.4" rx="2.2"></rect><path d="M10.5 18.2h3"></path></svg>';
        case 'tablet':
            return '<svg viewBox="0 0 24 24" aria-hidden="true"><rect x="5.5" y="2.8" width="13" height="18.4" rx="2.4"></rect><path d="M10.5 18.2h3"></path></svg>';
        case 'terminal':
            return '<svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="4" width="18" height="16" rx="2"></rect><path d="m8 9 3 3-3 3"></path><path d="M13 15h4"></path></svg>';
        case 'monitor':
        default:
            return '<svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="4" width="18" height="12" rx="2"></rect><path d="M8 20h8"></path><path d="M12 16v4"></path></svg>';
    }
}

function timellow_moment_source_html($source)
{
    $profile = timellow_moment_source_profile($source);
    if ($profile['label'] === '') {
        return '';
    }

    return '<span class="moment-source-icon moment-source-icon-' . htmlspecialchars((string) $profile['icon'], ENT_QUOTES, 'UTF-8') . '">'
        . timellow_moment_source_icon($profile['icon'])
        . '</span><span>' . htmlspecialchars((string) $profile['label'], ENT_QUOTES, 'UTF-8') . '</span>';
}

function timellow_fetch_moments($limit = 100)
{
    static $cache = [];
    $limit = max(1, min(200, (int) $limit));

    if (isset($cache[$limit])) {
        return $cache[$limit];
    }

    $result = [
        'items' => [],
        'error' => ''
    ];

    try {
        $db = Typecho_Db::get();
        $rows = $db->fetchAll(
            $db->select('mid', 'content', 'tags', 'media', 'created', 'source', 'status', 'location_address')
                ->from('table.moments')
                ->where('status = ?', 'public')
                ->order('created', Typecho_Db::SORT_DESC)
                ->limit($limit)
        );

        foreach ($rows as $row) {
            $result['items'][] = [
                'mid' => (int) ($row['mid'] ?? 0),
                'content' => trim((string) ($row['content'] ?? '')),
                'tags' => timellow_parse_moment_tags($row['tags'] ?? ''),
                'media' => timellow_parse_moment_media($row['media'] ?? ''),
                'created' => (int) ($row['created'] ?? 0),
                'source' => trim((string) ($row['source'] ?? '')),
                'location' => trim((string) ($row['location_address'] ?? ''))
            ];
        }
    } catch (Exception $exception) {
        $result['error'] = $exception->getMessage();
    }

    $cache[$limit] = $result;
    return $result;
}

function timellow_comment_parent_author($coid)
{
    $coid = (int) $coid;
    if ($coid <= 0) {
        return '';
    }

    $db = Typecho_Db::get();
    $comment = $db->fetchRow(
        $db->select('author')
            ->from('table.comments')
            ->where('coid = ?', $coid)
            ->limit(1)
    );

    return !empty($comment['author']) ? (string) $comment['author'] : '';
}

function timellow_comment_content_html($comments)
{
    ob_start();
    $comments->content();
    return trim((string) ob_get_clean());
}

function timellow_comment_content_with_reply($comments)
{
    $content = timellow_comment_content_html($comments);
    if (!$comments->parent) {
        return $content;
    }

    $parentAuthor = timellow_comment_parent_author($comments->parent);
    if ($parentAuthor === '') {
        return $content;
    }

    $prefix = '<span class="comment-reply-to">@' . htmlspecialchars($parentAuthor, ENT_QUOTES, 'UTF-8') . '</span>';
    $result = preg_replace('/^\s*(<p\b[^>]*>)/i', '$1' . $prefix, $content, 1, $count);

    if ($count > 0 && $result !== null) {
        return $result;
    }

    return $prefix . $content;
}

function threadedComments($comments, $options)
{
    $commentClass = 'comment-item';
    if ($comments->levels > 0) {
        $commentClass .= ' is-children';
    }
    if ($comments->authorId && $comments->authorId == $comments->ownerId) {
        $commentClass .= ' is-author';
    }
    ?>
    <li id="li-<?php $comments->theId(); ?>" class="<?php echo $commentClass; ?>">
        <article id="<?php $comments->theId(); ?>" class="comment-card">
            <div class="comment-row">
                <div class="comment-avatar">
                    <?php $comments->gravatar(48, '', 'mp'); ?>
                </div>
                <div class="comment-main">
                    <div class="comment-header">
                        <div class="comment-meta-block">
                            <span class="comment-author">
                                <?php if ($comments->url): ?>
                                    <a href="<?php echo htmlspecialchars((string) $comments->url, ENT_QUOTES, 'UTF-8'); ?>" rel="ugc external nofollow" target="_blank"><?php echo htmlspecialchars((string) $comments->author, ENT_QUOTES, 'UTF-8'); ?></a>
                                <?php else: ?>
                                    <?php echo htmlspecialchars((string) $comments->author, ENT_QUOTES, 'UTF-8'); ?>
                                <?php endif; ?>
                            </span>
                            <time class="comment-date" datetime="<?php $comments->date('c'); ?>"><?php $comments->date('Y-m-d H:i'); ?></time>
                        </div>
                        <div class="comment-actions">
                            <?php $comments->reply('<span>' . htmlspecialchars(_t('回复'), ENT_QUOTES, 'UTF-8') . '</span>'); ?>
                        </div>
                    </div>
                    <div class="comment-content">
                        <?php echo timellow_comment_content_with_reply($comments); ?>
                    </div>
                </div>
            </div>
        </article>
        <?php if ($comments->children): ?>
            <ol class="comment-children">
                <?php $comments->threadedComments($options); ?>
            </ol>
        <?php endif; ?>
    </li>
    <?php
}
