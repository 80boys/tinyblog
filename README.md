## 项目结构


## 重构后的项目结构

tinyblog/
├── .git/                    # Git 版本控制目录
├── .vscode/                 # VS Code 配置目录
├── app/                     # 应用程序主目录
│   ├── controllers/        # 控制器目录
│   │   ├── Admin/         # 后台管理控制器
│   │   ├── Api/           # API接口控制器  
│   │   └── Front/         # 前台页面控制器
│   ├── models/            # 数据模型目录
│   ├── services/          # 业务逻辑服务层
│   ├── middleware/        # 中间件目录
│   ├── helpers/           # 助手函数目录
│   └── exceptions/        # 异常处理目录
├── config/                 # 配置文件目录
│   ├── app.php            # 应用配置
│   ├── database.php       # 数据库配置
│   └── cache.php          # 缓存配置
├── public/                 # 公共资源目录
│   ├── static/            # 静态资源
│   │   ├── css/          # CSS文件
│   │   ├── js/           # JavaScript文件
│   │   └── images/       # 图片资源
│   ├── themes/            # 主题目录
│   │   ├── default/      # 默认主题
│   │   └── dark/         # 暗色主题
│   └── index.php         # 入口文件
├── resources/             # 资源文件目录
│   ├── views/            # 视图模板
│   ├── lang/             # 多语言文件
│   │   ├── en/          # 英文语言包
│   │   └── zh/          # 中文语言包
│   └── assets/           # 前端资源文件
├── routes/                # 路由配置目录
│   ├── web.php           # Web路由
│   ├── api.php           # API路由
│   └── admin.php         # 后台路由
├── storage/              # 存储目录
│   ├── logs/            # 日志文件
│   ├── cache/           # 缓存文件
│   └── uploads/         # 上传文件
├── tests/               # 测试目录
├── vendor/              # Composer依赖
├── bootstrap/           # 引导程序目录
├── composer.json        # Composer配置
├── .env                 # 环境变量配置
├── .gitignore          # Git忽略配置
└── README.md           # 项目说明文档

## 重构说明

1. 路由独立 (routes/)
   - 采用单独的路由文件管理不同类型的路由
   - 支持路由分组、中间件、参数验证等特性

2. 前后端分离
   - 前端资源统一放在 public/static/ 
   - 支持多主题切换 (public/themes/)
   - API接口统一管理 (app/controllers/Api/)

3. MVC架构优化
   - 控制器分层 (Admin/Api/Front)
   - 模型层独立 (app/models/)
   - 服务层处理业务逻辑 (app/services/)

4. 国际化支持
   - 多语言文件统一管理 (resources/lang/)
   - 支持动态切换语言
   - 配置文件可国际化

5. 主题系统
   - 支持多主题切换
   - 主题资源独立打包
   - 主题配置可视化

6. 其他改进
   - 统一的异常处理
   - 中间件机制
   - 依赖注入容器
   - 缓存系统
   - 日志管理


## 路由设计

1. 双模式路由支持
   - 支持伪静态路由 (example.com/post/123)
   - 支持传统PHP路由 (example.com/post.php?id=123)
   - 自动识别并匹配合适的路由模式

2. 伪静态路由
   - 基于URL重写规则(.htaccess)
   - 支持RESTful风格API
   - 优雅的URL结构
   - 示例:
     ```
     /posts              # 文章列表
     /post/123          # 文章详情
     /category/tech     # 分类页面
     ```

3. 传统PHP路由
   - 保持与旧系统兼容
   - 基于Query String参数
   - 示例:
     ```
     post.php?id=123
     category.php?name=tech
     index.php?page=2
     ```

4. 路由配置
   - 在routes/目录下统一管理
   - web.php处理页面路由
   - api.php处理接口路由
   - 支持路由组和中间件

5. URL生成
   - 智能URL生成器
   - 自动判断使用哪种路由模式
   - 支持配置默认路由模式

6. 性能优化
   - 路由缓存
   - 快速路由匹配
   - 最小化路由查找开销



```
tinyblog/
├── .git/                    # Git 版本控制目录
├── .vscode/                 # VS Code 配置目录
├── app/                     # 应用程序主目录
│   ├── block/              # 页面区块组件
│   │   ├── blog.php        # 博客内容区块
│   │   ├── categories.php  # 分类区块
│   │   ├── footer.php      # 页脚区块
│   │   ├── head.php        # 头部区块
│   │   ├── index.php       # 首页区块
│   │   ├── login.php       # 登录区块
│   │   ├── message.php     # 消息提示区块
│   │   ├── navigation.php  # 导航区块
│   │   ├── navi.php        # 导航菜单区块
│   │   └── pagination.php  # 分页区块
│   ├── blogs/              # 博客相关功能
│   ├── config/             # 配置文件目录
│   ├── end/                # 后端处理目录
│   ├── html/               # 前端资源目录
│   │   ├── css/           # CSS 样式文件
│   │   ├── fonts/         # 字体文件
│   │   ├── js/            # JavaScript 文件
│   │   ├── less/          # LESS 样式文件
│   │   ├── scss/          # SCSS 样式文件
│   │   └── 404.html       # 404错误页面
│   ├── utils/              # 工具类目录
│   └── favicon.ico         # 网站图标
├── autoload.php            # 自动加载文件
├── index.php               # 入口文件
├── README.md               # 项目说明文件
├── robots.txt              # 搜索引擎爬虫规则
└── .gitignore              # Git 忽略文件配置
