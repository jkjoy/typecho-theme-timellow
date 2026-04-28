document.addEventListener('DOMContentLoaded', function () {
  var toggle = document.querySelector('[data-search-toggle]');
  var panel = document.querySelector('[data-search-panel]');
  var themeToggle = document.querySelector('[data-theme-toggle]');
  var themeLabel = document.querySelector('[data-theme-label]');
  var themeKey = 'timellow-theme';
  var root = document.documentElement;
  var input = panel ? panel.querySelector('input[name="s"]') : null;

  function getTheme() {
    return root.getAttribute('data-theme') === 'dark' ? 'dark' : 'light';
  }

  function setTheme(theme) {
    var isDark = theme === 'dark';

    root.setAttribute('data-theme', isDark ? 'dark' : 'light');

    if (themeToggle) {
      themeToggle.setAttribute('aria-pressed', isDark ? 'true' : 'false');
      themeToggle.setAttribute('title', isDark ? '切换浅色模式' : '切换深色模式');
    }

    if (themeLabel) {
      themeLabel.textContent = isDark ? '切换浅色模式' : '切换深色模式';
    }
  }

  if (themeToggle) {
    setTheme(getTheme());

    themeToggle.addEventListener('click', function () {
      var nextTheme = getTheme() === 'dark' ? 'light' : 'dark';

      setTheme(nextTheme);

      try {
        window.localStorage.setItem(themeKey, nextTheme);
      } catch (e) {}
    });
  }

  function fallbackCopyText(text) {
    var textarea = document.createElement('textarea');

    textarea.value = text;
    textarea.setAttribute('readonly', 'readonly');
    textarea.style.position = 'fixed';
    textarea.style.top = '-9999px';
    document.body.appendChild(textarea);
    textarea.select();

    try {
      document.execCommand('copy');
      document.body.removeChild(textarea);
      return true;
    } catch (e) {
      document.body.removeChild(textarea);
      return false;
    }
  }

  function writeText(text, onSuccess, onError) {
    if (navigator.clipboard && window.isSecureContext) {
      navigator.clipboard.writeText(text).then(onSuccess).catch(function () {
        if (fallbackCopyText(text)) {
          onSuccess();
        } else {
          onError();
        }
      });
      return;
    }

    if (fallbackCopyText(text)) {
      onSuccess();
    } else {
      onError();
    }
  }

  function prettifyLanguage(name) {
    var raw = String(name || '').trim();
    var key = raw.toLowerCase();
    var map = {
      js: 'JavaScript',
      javascript: 'JavaScript',
      ts: 'TypeScript',
      typescript: 'TypeScript',
      jsx: 'JSX',
      tsx: 'TSX',
      html: 'HTML',
      xml: 'XML',
      css: 'CSS',
      scss: 'SCSS',
      sass: 'Sass',
      php: 'PHP',
      py: 'Python',
      python: 'Python',
      sh: 'Shell',
      shell: 'Shell',
      bash: 'Bash',
      zsh: 'Zsh',
      json: 'JSON',
      yaml: 'YAML',
      yml: 'YAML',
      md: 'Markdown',
      markdown: 'Markdown',
      sql: 'SQL',
      java: 'Java',
      c: 'C',
      cpp: 'C++',
      csharp: 'C#',
      cs: 'C#',
      go: 'Go',
      rust: 'Rust',
      vue: 'Vue',
      text: 'Text',
      plaintext: 'Text',
      txt: 'Text'
    };

    if (map[key]) {
      return map[key];
    }

    if (!raw) {
      return 'Code';
    }

    return raw.replace(/[_-]+/g, ' ').replace(/\b\w/g, function (letter) {
      return letter.toUpperCase();
    });
  }

  function extractLanguage(node) {
    if (!node) {
      return '';
    }

    var direct = node.getAttribute('data-language') || node.getAttribute('data-lang');

    if (direct) {
      return direct;
    }

    var className = node.className || '';
    var parts = String(className).split(/\s+/);

    for (var i = 0; i < parts.length; i += 1) {
      var part = parts[i];
      var match = part.match(/^(?:language|lang)-([a-z0-9#+._-]+)$/i);

      if (match) {
        return match[1];
      }
    }

    return '';
  }

  function enhanceCodeBlocks() {
    var blocks = document.querySelectorAll('.article-body pre');

    Array.prototype.forEach.call(blocks, function (pre) {
      if (!pre.parentNode || (pre.parentNode.classList && pre.parentNode.classList.contains('code-block-shell'))) {
        return;
      }

      var code = pre.querySelector('code');
      var wrapper = document.createElement('div');
      var toolbar = document.createElement('div');
      var dots = document.createElement('div');
      var label = document.createElement('span');
      var button = document.createElement('button');
      var language = prettifyLanguage(extractLanguage(code) || extractLanguage(pre));
      var dotClasses = ['is-red', 'is-yellow', 'is-green'];
      var source = code ? code.textContent : pre.textContent;

      wrapper.className = 'code-block-shell';
      toolbar.className = 'code-block-toolbar';
      dots.className = 'code-block-dots';
      label.className = 'code-block-language';
      label.textContent = language;
      button.type = 'button';
      button.className = 'code-block-copy';
      button.textContent = '复制';
      button.setAttribute('aria-label', '复制代码');

      for (var i = 0; i < dotClasses.length; i += 1) {
        var dot = document.createElement('span');
        dot.className = 'code-block-dot ' + dotClasses[i];
        dots.appendChild(dot);
      }

      button.addEventListener('click', function () {
        writeText(source, function () {
          button.textContent = '已复制';
          button.classList.remove('is-failed');
          button.classList.add('is-copied');
          window.clearTimeout(button._resetTimer);
          button._resetTimer = window.setTimeout(function () {
            button.textContent = '复制';
            button.classList.remove('is-copied');
          }, 1800);
        }, function () {
          button.textContent = '失败';
          button.classList.remove('is-copied');
          button.classList.add('is-failed');
          window.clearTimeout(button._resetTimer);
          button._resetTimer = window.setTimeout(function () {
            button.textContent = '复制';
            button.classList.remove('is-failed');
          }, 1800);
        });
      });

      pre.parentNode.insertBefore(wrapper, pre);
      toolbar.appendChild(dots);
      toolbar.appendChild(label);
      toolbar.appendChild(button);
      wrapper.appendChild(toolbar);
      wrapper.appendChild(pre);
    });
  }

  enhanceCodeBlocks();

  function enhanceArticleImages() {
    var images = document.querySelectorAll('.article-body img');

    Array.prototype.forEach.call(images, function (img) {
      if (!img.parentNode) {
        return;
      }

      var titleText = (img.getAttribute('title') || '').trim();
      var altText = (img.getAttribute('alt') || '').trim();
      var captionText = titleText || altText;
      var parent = img.parentNode;
      var figure = img.closest ? img.closest('figure') : null;

      if (titleText) {
        img.removeAttribute('title');
      }

      if (parent.tagName === 'A' && parent.childNodes.length === 1 && parent.getAttribute('title')) {
        parent.removeAttribute('title');
      }

      if (figure) {
        if (!figure.querySelector('figcaption') && captionText) {
          var figcaption = document.createElement('figcaption');
          figcaption.textContent = captionText;
          figure.appendChild(figcaption);
        }
        return;
      }

      if (!captionText) {
        return;
      }

      if (parent.classList && parent.classList.contains('article-image-wrap')) {
        return;
      }

      var target = img;
      if (parent.tagName === 'A' && parent.childNodes.length === 1) {
        target = parent;
      }

      var wrapper = document.createElement('span');
      var caption = document.createElement('span');

      wrapper.className = 'article-image-wrap';
      caption.className = 'article-image-caption';
      caption.textContent = captionText;

      target.parentNode.insertBefore(wrapper, target);
      wrapper.appendChild(target);
      wrapper.appendChild(caption);
    });
  }

  enhanceArticleImages();

  function enhanceArticleLinks() {
    var links = document.querySelectorAll('.article-body a[href]');

    Array.prototype.forEach.call(links, function (link) {
      var href = (link.getAttribute('href') || '').trim();

      if (!href || href.charAt(0) === '#') {
        return;
      }

      if (/^(?:mailto:|tel:|javascript:)/i.test(href)) {
        return;
      }

      link.setAttribute('target', '_blank');
      link.setAttribute('rel', 'noopener noreferrer');
    });
  }

  enhanceArticleLinks();

  function enhanceLoadMore() {
    var list = document.querySelector('[data-post-list]');
    var control = document.querySelector('[data-load-more]');
    var trigger = control ? control.querySelector('[data-load-more-trigger]') : null;

    if (!list || !control || !trigger) {
      return;
    }

    var label = trigger.querySelector('[data-load-more-label]');
    var defaultText = trigger.getAttribute('data-default-text') || '加载更多';
    var loadingText = trigger.getAttribute('data-loading-text') || defaultText;
    var errorText = trigger.getAttribute('data-error-text') || defaultText;

    function setLabel(text) {
      if (label) {
        label.textContent = text;
      } else {
        trigger.textContent = text;
      }
    }

    function setLoadingState(isLoading) {
      trigger.setAttribute('aria-disabled', isLoading ? 'true' : 'false');
      trigger.classList.toggle('is-loading', isLoading);
    }

    function completeLoadMore() {
      setLoadingState(false);
      setLabel(defaultText);
      control.hidden = true;
    }

    function getPostKey(item) {
      if (!item) {
        return '';
      }

      var cid = item.getAttribute('data-post-cid');
      if (cid) {
        return 'cid:' + cid;
      }

      var link = item.querySelector('.post-title a[href], a[itemprop="url"][href], a[href]');
      return link ? 'href:' + link.href : '';
    }

    var renderedPosts = {};

    Array.prototype.forEach.call(list.children, function (child) {
      var key = getPostKey(child);

      if (!key) {
        return;
      }

      if (renderedPosts[key] && child.parentNode) {
        child.parentNode.removeChild(child);
        return;
      }

      renderedPosts[key] = true;
    });

    function setNextUrl(url) {
      if (!url) {
        completeLoadMore();
        return;
      }

      trigger.setAttribute('href', url);
      trigger.setAttribute('data-next-url', url);
    }

    trigger.addEventListener('click', function (event) {
      var nextUrl = trigger.getAttribute('data-next-url') || trigger.getAttribute('href');

      if (!nextUrl) {
        event.preventDefault();
        completeLoadMore();
        return;
      }

      if (trigger.getAttribute('aria-disabled') === 'true') {
        event.preventDefault();
        return;
      }

      event.preventDefault();
      setLoadingState(true);
      trigger.classList.remove('is-error');
      setLabel(loadingText);

      fetch(nextUrl, {
        credentials: 'same-origin',
        headers: {
          'X-Requested-With': 'XMLHttpRequest'
        }
      }).then(function (response) {
        if (!response.ok) {
          throw new Error('Failed to fetch next page');
        }

        return response.text();
      }).then(function (html) {
        var parser = new DOMParser();
        var doc = parser.parseFromString(html, 'text/html');
        var nextList = doc.querySelector('[data-post-list]');
        var nextTrigger = doc.querySelector('[data-load-more-trigger]');
        var fragment = document.createDocumentFragment();
        var appended = 0;

        if (!nextList) {
          throw new Error('Missing post list');
        }

        Array.prototype.forEach.call(nextList.children, function (child) {
          if (!child.classList || !child.classList.contains('post-card')) {
            return;
          }

          var key = getPostKey(child);

          if (key && renderedPosts[key]) {
            return;
          }

          fragment.appendChild(document.importNode(child, true));
          if (key) {
            renderedPosts[key] = true;
          }
          appended += 1;
        });

        if (appended < 1) {
          completeLoadMore();
          return;
        }

        list.appendChild(fragment);

        if (nextTrigger) {
          setNextUrl(nextTrigger.getAttribute('data-next-url') || nextTrigger.getAttribute('href'));
          setLoadingState(false);
          setLabel(defaultText);
          return;
        }

        completeLoadMore();
      }).catch(function () {
        setLoadingState(false);
        trigger.classList.add('is-error');
        setLabel(errorText);
      });
    });
  }

  enhanceLoadMore();

  if (!toggle || !panel) {
    return;
  }

  function openPanel() {
    panel.hidden = false;
    toggle.setAttribute('aria-expanded', 'true');
    if (input) {
      input.focus();
    }
  }

  function closePanel() {
    panel.hidden = true;
    toggle.setAttribute('aria-expanded', 'false');
  }

  toggle.addEventListener('click', function () {
    if (panel.hidden) {
      openPanel();
    } else {
      closePanel();
    }
  });

  document.addEventListener('keydown', function (event) {
    if (event.key === 'Escape' && !panel.hidden) {
      closePanel();
    }
  });

  document.addEventListener('click', function (event) {
    if (panel.hidden) {
      return;
    }

    if (!panel.contains(event.target) && !toggle.contains(event.target) && (!themeToggle || !themeToggle.contains(event.target))) {
      closePanel();
    }
  });
});
