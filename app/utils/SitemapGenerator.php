<?php

namespace App\Utils;

class SitemapGenerator
{
    private $baseUrl;
    private $dt;

    public function __construct($baseUrl)
    {
        $this->baseUrl = rtrim($baseUrl, '/');
        $this->dt = new DirectoryTraverser();
    }

    public function generate()
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;

        // 添加首页
        $xml .= $this->createUrlEntry($this->baseUrl . '/', '1.0', 'daily');

        // 添加分类页面
        $xml .= $this->createUrlEntry($this->baseUrl . '/app/block/categories.html', '0.8', 'daily');

        // 添加关于页面
        $xml .= $this->createUrlEntry($this->baseUrl . '/app/block/about.html', '0.6', 'weekly');

        // 添加所有博客文章
        $blogs = $this->dt->getAllBlogs()['blogs'];
        foreach ($blogs as $blog) {
            $blogUrl = $this->baseUrl . '/app/blogs/' . str_replace('.json', '.html', $blog['path']);
            $lastmod = isset($blog['date']) ? date('Y-m-d', strtotime($blog['date'])) : date('Y-m-d');
            $xml .= $this->createUrlEntry($blogUrl, '0.9', 'weekly', $lastmod);
        }

        // 添加分类列表页面
        $categories = [];
        foreach ($blogs as $blog) {
            if (isset($blog['category']) && !empty($blog['category'])) {
                $categories[$blog['category']] = true;
            }
        }
        foreach (array_keys($categories) as $category) {
            $categoryUrl = $this->baseUrl . '/app/block/categories.html?category=' . urlencode($category);
            $xml .= $this->createUrlEntry($categoryUrl, '0.7', 'weekly');
        }

        $xml .= '</urlset>';
        return $xml;
    }

    private function createUrlEntry($loc, $priority = '0.5', $changefreq = 'monthly', $lastmod = null)
    {
        $entry = "  <url>\n";
        $entry .= "    <loc>" . htmlspecialchars($loc) . "</loc>\n";
        if ($lastmod) {
            $entry .= "    <lastmod>" . $lastmod . "</lastmod>\n";
        }
        $entry .= "    <changefreq>" . $changefreq . "</changefreq>\n";
        $entry .= "    <priority>" . $priority . "</priority>\n";
        $entry .= "  </url>\n";
        return $entry;
    }
}
