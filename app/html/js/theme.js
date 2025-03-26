// 主题初始化脚本
document.addEventListener("DOMContentLoaded", function () {
  // 切换代码高亮主题的函数
  function switchHighlightTheme(theme) {
    // 获取所有的高亮样式链接
    const highlightStyles = document.querySelectorAll(
      "link[data-highlight-theme]"
    );

    // 遍历所有样式，启用匹配当前主题的样式，禁用其他样式
    highlightStyles.forEach((link) => {
      if (link.getAttribute("data-highlight-theme") === theme) {
        link.disabled = false;
      } else {
        link.disabled = true;
      }
    });

    // 设置cookie以便服务器端也能识别主题
    document.cookie = `theme=${theme}; path=/; max-age=31536000`;
  }

  // 更新主题图标显示
  function updateThemeIcons(theme) {
    const lightIcon = document.getElementById("lightIcon");
    const darkIcon = document.getElementById("darkIcon");

    if (theme === "dark") {
      if (lightIcon) lightIcon.classList.add("hidden");
      if (darkIcon) darkIcon.classList.remove("hidden");
      // 直接设置背景色，避免闪烁
      document.documentElement.style.backgroundColor = "#121212";
      document.documentElement.style.color = "#e0e0e0";
    } else {
      if (lightIcon) lightIcon.classList.remove("hidden");
      if (darkIcon) darkIcon.classList.add("hidden");
      // 恢复默认背景色
      document.documentElement.style.backgroundColor = "";
      document.documentElement.style.color = "";
    }
  }

  // 恢复保存的主题
  const savedTheme = localStorage.getItem("theme");
  if (savedTheme) {
    document.documentElement.setAttribute("data-theme", savedTheme);
    // 设置代码高亮主题
    switchHighlightTheme(savedTheme);
    // 更新图标
    updateThemeIcons(savedTheme);
  } else {
    // 检查系统偏好
    const prefersDarkScheme = window.matchMedia("(prefers-color-scheme: dark)");
    if (prefersDarkScheme.matches) {
      document.documentElement.setAttribute("data-theme", "dark");
      localStorage.setItem("theme", "dark");
      // 设置代码高亮主题
      switchHighlightTheme("dark");
      // 更新图标
      updateThemeIcons("dark");
    } else {
      // 默认使用浅色主题
      switchHighlightTheme("light");
      // 更新图标
      updateThemeIcons("light");
    }
  }

  // 监听主题切换按钮点击事件
  const themeToggle = document.getElementById("themeToggle");
  if (themeToggle) {
    // 切换主题
    themeToggle.addEventListener("click", function () {
      const currentTheme =
        document.documentElement.getAttribute("data-theme") || "light";
      const newTheme = currentTheme === "light" ? "dark" : "light";

      // 添加过渡类，实现平滑过渡
      document.documentElement.classList.add("theme-transition");
      document.body.classList.add("theme-transition");

      // 修改主题属性
      document.documentElement.setAttribute("data-theme", newTheme);
      localStorage.setItem("theme", newTheme);

      // 切换代码高亮主题
      switchHighlightTheme(newTheme);

      // 更新图标
      updateThemeIcons(newTheme);

      // 如果存在代码高亮刷新函数，则调用它
      if (typeof window.applyCodeHighlighting === "function") {
        setTimeout(function () {
          window.applyCodeHighlighting();
        }, 100);
      }
    });
  }

  // 监听系统主题变化
  const prefersDarkScheme = window.matchMedia("(prefers-color-scheme: dark)");
  prefersDarkScheme.addEventListener("change", function (e) {
    // 仅当用户没有手动设置主题时才应用系统主题
    if (!localStorage.getItem("theme")) {
      const newTheme = e.matches ? "dark" : "light";
      document.documentElement.setAttribute("data-theme", newTheme);

      // 切换代码高亮主题
      switchHighlightTheme(newTheme);

      // 更新图标
      updateThemeIcons(newTheme);

      // 如果存在代码高亮刷新函数，则调用它
      if (typeof window.applyCodeHighlighting === "function") {
        setTimeout(function () {
          window.applyCodeHighlighting();
        }, 100);
      }
    }
  });

  // 在页面完全加载后添加过渡类，这样初始加载时不会有过渡效果导致闪烁
  window.addEventListener("load", function () {
    document.documentElement.classList.add("theme-transition");
    document.body.classList.add("theme-transition");
  });
});
