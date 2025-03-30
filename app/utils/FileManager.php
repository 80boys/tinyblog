<?php
namespace App\Utils;

/**
 * 文件操作工具类
 */
class FileManager
{
    /**
     * 读取PHP配置文件
     * 
     * @param string $path 文件路径
     * @param array $default 默认返回值
     * @return array
     */
    public static function readPhpConfigFile($path, $default = [])
    {
        if (!file_exists($path)) {
            return $default;
        }
        return require($path);
    }
    
    /**
     * 保存PHP配置文件
     * 
     * @param string $path 文件路径
     * @param array $data 要保存的数据
     * @return bool
     */
    public static function savePhpConfigFile($path, $data)
    {
        try {
            $content = "<?php\nreturn " . var_export($data, true) . ";\n";
            return file_put_contents($path, $content) !== false;
        } catch (\Exception $e) {
            error_log("保存配置文件失败: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * 读取博客文件
     * 
     * @param string $path 博客文件路径
     * @return array
     */
    public static function readBlogFile($path)
    {
        if (!file_exists($path)) {
            return [];
        }
        
        // 根据文件扩展名选择不同的读取方式
        $extension = pathinfo($path, PATHINFO_EXTENSION);
        
        if ($extension === 'php') {
            return require($path);
        } else if ($extension === 'json') {
            $content = file_get_contents($path);
            return json_decode($content, true) ?: [];
        } else {
            return [];
        }
    }
    
    /**
     * 保存博客文件
     * 
     * @param string $path 保存路径
     * @param array $data 博客数据
     * @return bool
     */
    public static function saveBlogFile($path, $data)
    {
        try {
            $extension = pathinfo($path, PATHINFO_EXTENSION);
            
            if ($extension === 'php') {
                $content = "<?php\nreturn " . var_export($data, true) . ";\n";
            } else if ($extension === 'json') {
                $content = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            } else {
                return false;
            }
            
            // 确保目录存在
            $dir = dirname($path);
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }
            
            return file_put_contents($path, $content) !== false;
        } catch (\Exception $e) {
            error_log("保存博客文件失败: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * 删除文件
     * 
     * @param string $path 文件路径
     * @return bool
     */
    public static function deleteFile($path)
    {
        if (file_exists($path)) {
            return unlink($path);
        }
        return false;
    }
    
    /**
     * 获取目录下的所有文件
     * 
     * @param string $dir 目录路径
     * @param array $extensions 文件扩展名过滤
     * @param array $excludeFiles 要排除的文件
     * @return array
     */
    public static function getFiles($dir, $extensions = [], $excludeFiles = [])
    {
        if (!is_dir($dir)) {
            return [];
        }
        
        $files = [];
        $pattern = empty($extensions) ? '*' : '*.{' . implode(',', $extensions) . '}';
        
        foreach (glob($dir . '/' . $pattern, GLOB_BRACE) as $file) {
            if (!in_array(basename($file), $excludeFiles)) {
                $files[] = $file;
            }
        }
        
        return $files;
    }
}