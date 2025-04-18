<?php

namespace App\Models;

use App\Utils\FileManager;

class CategoryModel
{
    // 存储路径配置
    private static $categoriesFile = 'content/categories.php';
    private static $cachePath = 'content/caches.php';

    /**
     * 获取所有分类列表
     * @return array 分类列表
     */
    public static function getAll()
    {
        $categoriesFile = PROJECT_ROOT . '/' . self::$categoriesFile;
        if (!file_exists($categoriesFile)) {
            return [];
        }

        $categories = require $categoriesFile;
        return is_array($categories) ? $categories : [];
    }

    /**
     * 获取分类及其统计信息
     * @return array 分类及其统计信息
     */
    public static function getAllWithStats()
    {
        $caches = BlogsModel::getCaches();
        return isset($caches['categories']) ? $caches['categories'] : [];
    }

    /**
     * 添加新分类
     * @param string $name 分类名称
     * @return bool 是否添加成功
     */
    public static function add($name)
    {
        if (empty($name)) {
            return false;
        }

        $categories = self::getAll();
        if (in_array($name, $categories)) {
            return true; // 分类已存在
        }

        $categories[] = $name;

        // 保存到分类文件
        if (!self::saveCategories($categories)) {
            return false;
        }

        // 更新缓存
        return self::updateCategoryCache($name);
    }

    /**
     * 删除分类
     * @param string $name 要删除的分类名
     * @param string $moveTo 将该分类下的博客移动到的目标分类
     * @return bool 是否删除成功
     */
    public static function delete($name, $moveTo = '未分类')
    {
        if (empty($name) || $name === $moveTo) {
            return false;
        }

        // 更新所有使用该分类的博客
        $caches = BlogsModel::getCaches();
        if (isset($caches['categories'][$name])) {
            foreach ($caches['categories'][$name]['blogs'] as $blogPath) {
                $blog = BlogModel::findByPath($blogPath);
                if ($blog) {
                    $blog->setCategory($moveTo);
                    $blog->save();
                }
            }
        }

        // 从分类列表中删除
        $categories = self::getAll();
        $key = array_search($name, $categories);
        if ($key !== false) {
            unset($categories[$key]);
            $categories = array_values($categories);

            // 保存到分类文件
            if (!self::saveCategories($categories)) {
                return false;
            }

            // 更新缓存
            return BlogsModel::rebuildCache();
        }

        return true;
    }

    /**
     * 重命名分类
     * @param string $oldName 原分类名
     * @param string $newName 新分类名
     * @return bool 是否重命名成功
     */
    public static function rename($oldName, $newName)
    {
        if (empty($oldName) || empty($newName) || $oldName === $newName) {
            return false;
        }

        // 更新所有使用该分类的博客
        $caches = BlogsModel::getCaches();
        if (isset($caches['categories'][$oldName])) {
            foreach ($caches['categories'][$oldName]['blogs'] as $blogPath) {
                $blog = BlogModel::findByPath($blogPath);
                if ($blog) {
                    $blog->setCategory($newName);
                    $blog->save();
                }
            }
        }

        // 更新分类列表
        $categories = self::getAll();
        $key = array_search($oldName, $categories);
        if ($key !== false) {
            $categories[$key] = $newName;

            // 保存到分类文件
            if (!self::saveCategories($categories)) {
                return false;
            }

            // 更新缓存
            return BlogsModel::rebuildCache();
        }

        return false;
    }

    /**
     * 获取指定分类下的博客列表
     * @param string $category 分类名称
     * @param array $filters 过滤条件
     * @return array 博客列表
     */
    public static function getBlogsByCategory($category, $filters = [])
    {
        $caches = BlogsModel::getCaches();
        if (!isset($caches['categories'][$category])) {
            return [];
        }

        $blogs = [];
        foreach ($caches['categories'][$category]['blogs'] as $blogPath) {
            $blog = BlogModel::findByPath($blogPath);
            if ($blog) {
                $blogs[] = $blog;
            }
        }

        return $blogs;
    }

    /**
     * 保存分类列表到文件
     * @param array $categories 分类列表
     * @return bool 是否保存成功
     */
    private static function saveCategories($categories)
    {
        $categoriesFile = PROJECT_ROOT . '/' . self::$categoriesFile;
        return FileManager::savePhpConfigFile($categoriesFile, array_values($categories));
    }

    /**
     * 更新分类缓存
     * @param string $categoryName 分类名称
     * @return bool 是否更新成功
     */
    private static function updateCategoryCache($categoryName)
    {
        $caches = BlogsModel::getCaches();

        // 如果分类不存在于缓存中，添加它
        if (!isset($caches['categories'][$categoryName])) {
            $caches['categories'][$categoryName] = [
                'count' => 0,
                'blogs' => []
            ];

            // 保存缓存
            $cachePath = PROJECT_ROOT . '/' . self::$cachePath;
            return FileManager::savePhpConfigFile($cachePath, $caches);
        }

        return true;
    }
}
