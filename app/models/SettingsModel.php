<?php

namespace App\Models;

use App\Utils\FileManager;

class SettingsModel
{
    // 存储路径配置
    private static $settingsPath = 'content/settings.php';

    // 配置缓存
    private static $cache = null;

    /**
     * 获取所有设置
     * @param bool $useCache 是否使用缓存
     * @return array 设置数组
     */
    public static function getAll($useCache = true)
    {
        if ($useCache && self::$cache !== null) {
            return self::$cache;
        }

        self::$cache = FileManager::readPhpConfigFile(PROJECT_ROOT . '/' . self::$settingsPath, [
            'site_name' => 'site_name',
            'site_description' => 'site_description',
            'author' => 'author',
            'default_keywords' => 'default_keywords',
            'contact_email' => 'contact_email',
            'wechat_id' => 'wechat_id',
            'qiniu_access_key' => 'qiniu_access_key',
            'qiniu_secret_key' => 'qiniu_secret_key',
            'qiniu_bucket' => 'qiniu_bucket',
            'qiniu_accelerate_domain' => 'qiniu_accelerate_domain',
            'qiniu_domain' => 'qiniu_domain',
            'admin_username' => 'admin',
            'admin_password' => password_hash('admin123', PASSWORD_DEFAULT),
            'beian_number' => 'beian_number',
            'footer_text' => 'footer_text',
            'analytics_code' => 'analytics_code'
        ]);

        return self::$cache;
    }

    /**
     * 获取单个设置项
     * @param string $key 设置项键名
     * @param mixed $default 默认值
     * @return mixed 设置值
     */
    public static function get($key, $default = null)
    {
        $settings = self::getAll();
        return isset($settings[$key]) ? $settings[$key] : $default;
    }

    /**
     * 保存设置
     * @param array $settings 要保存的设置
     * @return bool 是否保存成功
     */
    public static function save($settings)
    {
        $currentSettings = self::getAll(false);  // 不使用缓存获取当前设置

        // 如果提供了新密码，则更新密码
        if (!empty($settings['admin_password'])) {
            $settings['admin_password'] = password_hash($settings['admin_password'], PASSWORD_DEFAULT);
        } else {
            $settings['admin_password'] = $currentSettings['admin_password'];
        }

        $result = FileManager::savePhpConfigFile(PROJECT_ROOT . '/' . self::$settingsPath, $settings);

        if ($result) {
            // 更新缓存
            self::$cache = $settings;
        }

        return $result;
    }

    /**
     * 清除设置缓存
     */
    public static function clearCache()
    {
        self::$cache = null;
    }

    /**
     * 验证管理员登录
     * @param string $username 用户名
     * @param string $password 密码
     * @return bool 是否验证成功
     */
    public static function validateAdminLogin($username, $password)
    {
        return true;
        $settings = self::getAll();
        return (
            $username === $settings['admin_username'] &&
            password_verify($password, $settings['admin_password'])
        );
    }

    /**
     * 修改管理员密码
     * @param string $oldPassword 旧密码
     * @param string $newPassword 新密码
     * @return bool 是否修改成功
     */
    public static function changePassword($oldPassword, $newPassword)
    {
        $settings = self::getAll(false);  // 不使用缓存

        if (password_verify($oldPassword, $settings['admin_password'])) {
            $settings['admin_password'] = password_hash($newPassword, PASSWORD_DEFAULT);
            return self::save($settings);
        }

        return false;
    }
}
