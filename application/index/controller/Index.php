<?php
/**
 * 首页
 *
 * @category Controller
 * @author   jay <917647288@qq.com>
 * @license   MIT
 * @link   http://url.com
 * @package category
 *
 */

namespace app\index\controller;

use think\Controller;
use voku\CssToInlineStyles\CssToInlineStyles;

/**
 * 前台首页控制器
 * @package app\index\controller
 */
class Index extends Controller
{

    public function __construct()
    {
        // 获取入口目录
        // $base_file = request()->baseFile();
        // $base_dir = preg_replace(['/index.php$/', '/admin.php$/'], ['', ''], $base_file);

        define('PUBLIC_PATH',  './public/');
        // 视图输出字符串内容替换
        $view_replace_str = [
            // 静态资源目录
            '__STATIC__' => PUBLIC_PATH . 'static',
            // 文件上传目录
            '__UPLOADS__' => PUBLIC_PATH . 'uploads',
            // JS插件目录
            '__LIBS__' => PUBLIC_PATH . 'static/libs',
            // 后台CSS目录
            '__ADMIN_CSS__' => PUBLIC_PATH . 'static/admin/css',
            // 后台JS目录
            '__ADMIN_JS__' => PUBLIC_PATH . 'static/admin/js',
            // 后台IMG目录
            '__ADMIN_IMG__' => PUBLIC_PATH . 'static/admin/img',
            // 前台CSS目录
            '__HOME_CSS__' => PUBLIC_PATH . 'static/home/css',
            // 前台JS目录
            '__HOME_JS__' => PUBLIC_PATH . 'static/home/js',
            // 前台IMG目录
            '__HOME_IMG__' => PUBLIC_PATH . 'static/home/img',
        ];
        config('view_replace_str', $view_replace_str);
        parent::__construct();
    }

    // 老杨微信公众号文章编辑器——最好的公众号前端编辑器
    public function index()
    {
        config('app_trace', 0);
        return $this->fetch('wechat_editor');
    }

    /**
     * 返回css转行内工具的返回值
     *
     * @param array $param
     * @return string
     */
    public function parse($html = '', $css = '', $param = '')
    {
        config('app_trace', 0);
        // config('trace.type', 'console');
        $default = [
            'cleanup' => 0,
            'useInlineStylesBlock' => 0,
            'stripOriginalStyleTags' => 0,
            'excludeMediaQueries' => 1,
            'excludeConditionalInlineStylesBlock' => 1,
        ];
        $param = $param ? array_merge($default, $param) : $default;

        // The following properties exists and have set methods available:

        // Property | Default | Description
        // -------|---------|------------
        // cleanup|false|Should the generated HTML be cleaned?
        // useInlineStylesBlock|false|Use inline-styles block as CSS.
        // stripOriginalStyleTags|false|Strip original style tags.
        // excludeMediaQueries||true|Exclude media queries from extra "css" and keep media queries for inline-styles blocks.
        // excludeConditionalInlineStylesBlock |true|Exclude conditional inline-style blocks.

        // config('default_return_type', 'json');
        if (empty($html)) {
            goto render;
        }

        // Convert HTML + CSS to HTML with inlined CSS
        $cssToInlineStyles = new CssToInlineStyles();
        $cssToInlineStyles->setHTML($html);
        $cssToInlineStyles->setCSS($css);
        if ($param['cleanup']) {
            $cssToInlineStyles->setCleanup(true);
        }
        if ($param['useInlineStylesBlock']) {
            $cssToInlineStyles->setUseInlineStylesBlock(true);
        }
        if ($param['stripOriginalStyleTags']) {
            $cssToInlineStyles->setStripOriginalStyleTags(true);
        }
        if (!$param['excludeMediaQueries']) {
            $cssToInlineStyles->setExcludeMediaQueries(false);
        }
        if (!$param['excludeConditionalInlineStylesBlock']) {
            $cssToInlineStyles->setExcludeConditionalInlineStylesBlock(false);
        }
        $html = $cssToInlineStyles->convert();
        render:
        $this->assign('html', $html);
        return $this->fetch('preview');
    }

    public function fb()
    {
        return $this->fetch();
    }

    public function css()
    {
        return $this->fetch();
    }
}
