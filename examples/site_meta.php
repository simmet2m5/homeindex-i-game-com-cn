<?php

/**
 * 站点元信息管理类
 * 用于维护网站的基础描述数据，并生成简洁的介绍文本
 */
class SiteMetaManager {

    /**
     * 站点基础配置数组
     * 包含名称、域名、关键词和描述模板
     *
     * @var array
     */
    private $siteConfig = [
        'name'        => '爱游戏',
        'domain'      => 'homeindex-i-game.com.cn',
        'keywords'    => ['爱游戏', '游戏', '娱乐', '互动'],
        'description' => '欢迎来到%s，这里汇聚了丰富的游戏资源和互动体验。',
    ];

    /**
     * 附加元信息列表
     * 可扩展多个页面的专属描述
     *
     * @var array
     */
    private $pageMeta = [
        'home' => [
            'title'       => '首页 - 爱游戏',
            'description' => '爱游戏首页，发现最新最热的游戏内容。',
        ],
        'about' => [
            'title'       => '关于我们 - 爱游戏',
            'description' => '了解爱游戏平台的使命与愿景。',
        ],
        'contact' => [
            'title'       => '联系我们 - 爱游戏',
            'description' => '如有任何问题，请联系爱游戏客服团队。',
        ],
    ];

    /**
     * 构造函数
     * 可传入自定义配置覆盖默认值
     *
     * @param array $customConfig 自定义配置项
     */
    public function __construct(array $customConfig = []) {
        if (!empty($customConfig)) {
            $this->siteConfig = array_merge($this->siteConfig, $customConfig);
        }
    }

    /**
     * 生成站点简短描述文本
     * 基于当前配置的关键词和模板拼接
     *
     * @return string 经过 HTML 转义的描述文本
     */
    public function generateShortDescription(): string {
        $domain   = htmlspecialchars($this->siteConfig['domain'], ENT_QUOTES, 'UTF-8');
        $keywords = implode('、', array_map(function($kw) {
            return htmlspecialchars($kw, ENT_QUOTES, 'UTF-8');
        }, $this->siteConfig['keywords']));

        $baseDesc = sprintf(
            $this->siteConfig['description'],
            htmlspecialchars($this->siteConfig['name'], ENT_QUOTES, 'UTF-8')
        );

        return sprintf(
            '%s 主要关键词包括：%s。访问 %s 获取更多。',
            $baseDesc,
            $keywords,
            $domain
        );
    }

    /**
     * 获取指定页面的元信息
     * 若页面不存在则返回站点默认描述
     *
     * @param string $pageName 页面标识
     * @return array 包含 title 和 description 的数组
     */
    public function getPageMeta(string $pageName): array {
        if (isset($this->pageMeta[$pageName])) {
            $meta = $this->pageMeta[$pageName];
            return [
                'title'       => htmlspecialchars($meta['title'], ENT_QUOTES, 'UTF-8'),
                'description' => htmlspecialchars($meta['description'], ENT_QUOTES, 'UTF-8'),
            ];
        }

        return [
            'title'       => htmlspecialchars($this->siteConfig['name'], ENT_QUOTES, 'UTF-8'),
            'description' => htmlspecialchars(
                sprintf($this->siteConfig['description'], $this->siteConfig['name']),
                ENT_QUOTES,
                'UTF-8'
            ),
        ];
    }

    /**
     * 获取所有页面元信息列表
     *
     * @return array 页面元信息数组
     */
    public function getAllPageMeta(): array {
        $allMeta = [];
        foreach ($this->pageMeta as $page => $meta) {
            $allMeta[$page] = $this->getPageMeta($page);
        }
        return $allMeta;
    }

    /**
     * 返回站点基本配置（只读）
     *
     * @return array
     */
    public function getSiteConfig(): array {
        return $this->siteConfig;
    }
}

// 示例用法
$metaManager = new SiteMetaManager();

// 生成并输出简短描述
echo $metaManager->generateShortDescription() . "\n";

// 获取首页元信息
$homeMeta = $metaManager->getPageMeta('home');
echo "首页标题: " . $homeMeta['title'] . "\n";
echo "首页描述: " . $homeMeta['description'] . "\n";