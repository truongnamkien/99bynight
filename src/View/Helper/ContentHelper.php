<?php

namespace App\View\Helper;

use App\Model\Entity\Banner;
use App\Model\Entity\Config;
use App\Model\Entity\StaticContent;
use Cake\Core\Configure;
use Cake\View\Helper;
use Cake\View\View;
use App\Utility\Utils;

class ContentHelper extends Helper {

    const RELATED_BLOGS_PER_LIST = 5;
    const FEATURED_BLOGS_PER_LIST = 3;

    protected static $pageList = [];
    protected static $blogCategories = [];
    public $helpers = ['Url'];

    public function pageHeader($currentPage = false) {
        $view = new \Cake\View\View();
        $view->setLayout(false);
        $this->_setConfig($view);
        $view->set('currentPage', $currentPage);
        Utils::useComponents($this, ['Backend.MultiLanguage']);
        $languaCode = $this->MultiLanguage->getCurrentLanguageCode();
        $view->set('currentLangCode', $languaCode);
        $html = $view->render('/Element/header');
        return $html;
    }

    public function pageFooter($currentPage = false) {
        $view = new \Cake\View\View();
        $view->setLayout(false);
        $this->_setConfig($view);
        $view->set('currentPage', $currentPage);
        $html = $view->render('/Element/footer');
        return $html;
    }

    private function _setConfig($view) {
        Utils::useComponents($this, ['Backend.MultiLanguage']);
        $currentLanguageCode = $this->MultiLanguage->getCurrentLanguageCode();
        Utils::useTables($this, ['Configs']);
        $configList = $this->Configs->find('all')->toArray();
        foreach ($configList as $config) {
            switch ($config->field) {
                case Config::CONFIG_KEY_EMAIL;
                    $view->set('siteEmail', $config->content);
                    break;
                case Config::CONFIG_KEY_DESCRIPTION_VIETNAMESE;
                    if ($currentLanguageCode === LANGUAGE_VIETNAMESE) {
                        $view->set('siteDescription', $config->content);
                    }
                    break;
                case Config::CONFIG_KEY_ADDRESS_VIETNAMESE;
                    if ($currentLanguageCode === LANGUAGE_VIETNAMESE) {
                        $view->set('siteAddress', $config->content);
                    }
                    break;
                case Config::CONFIG_KEY_PHONE;
                    $view->set('sitePhone', $config->content);
                    break;
                case Config::CONFIG_KEY_SOCIAL_FACEBOOK;
                    $view->set('siteFacebook', $config->content);
                    break;
                case Config::CONFIG_KEY_WORKING_MON_FRI;
                    $view->set('workingMonFri', $config->content);
                    break;
                case Config::CONFIG_KEY_WORKING_SAT;
                    $view->set('workingSat', $config->content);
                    break;
                case Config::CONFIG_KEY_WORKING_SUN;
                    $view->set('workingSun', $config->content);
                    break;
            }
        }
    }

    public function pageListHeader($currentPage = false) {
        $pageList = $this->_getPageList();
        if (empty($pageList)) {
            return false;
        }
        $view = new \Cake\View\View();
        $view->setLayout(false);
        $view->set('pageList', $pageList);
        $view->set('currentPage', $currentPage);
        $html = $view->render('/Element/Pages/page_list_header');
        return $html;
    }

    public function pageListFooter($currentPage = false) {
        $pageList = $this->_getPageList();
        if (empty($pageList)) {
            return false;
        }
        $view = new \Cake\View\View();
        $view->setLayout(false);
        $view->set('pageList', $pageList);
        $view->set('currentPage', $currentPage);
        $html = $view->render('/Element/Pages/page_list_footer');
        return $html;
    }

    private function _getPageList() {
        if (empty(self::$pageList)) {
            Utils::useTables($this, ['Pages']);
            self::$pageList = $this->Pages->getActivePages();
        }
        return self::$pageList;
    }

    public function blogListHeader($currentPage = false) {
        $categoryList = $this->_getBlogCategoryList();
        if (empty($categoryList)) {
            return false;
        }
        $view = new \Cake\View\View();
        $view->setLayout(false);
        $view->set('categoryList', $categoryList);
        $view->set('currentPage', $currentPage);
        $html = $view->render('/Element/Blogs/category_list_header');
        return $html;
    }

    public function blogListFooter($currentPage = false) {
        $categoryList = $this->_getBlogCategoryList();
        if (empty($categoryList)) {
            return false;
        }
        $view = new \Cake\View\View();
        $view->setLayout(false);
        $view->set('categoryList', $categoryList);
        $view->set('currentPage', $currentPage);
        $html = $view->render('/Element/Blogs/category_list_footer');
        return $html;
    }

    public function blogCategoryContent($currentPage = false) {
        $categoryList = $this->_getBlogCategoryList();
        if (empty($categoryList)) {
            return false;
        }
        $view = new \Cake\View\View();
        $view->setLayout(false);
        $view->set('categoryList', $categoryList);
        $view->set('currentPage', $currentPage);
        $html = $view->render('/Element/Blogs/category_list_sidebar');
        return $html;
    }

    private function _getBlogCategoryList() {
        if (empty(self::$blogCategories)) {
            Utils::useTables($this, ['BlogCategories']);
            Utils::useComponents($this, ['Backend.MultiLanguage']);
            $currentLanguage = $this->MultiLanguage->getCurrentLanguage();
            $categoryList = $this->BlogCategories->find('all', [
                        'contain' => [
                            'Title' . ucfirst($currentLanguage),
                        ],
                        'conditions' => [
                            'BlogCategories.status' => ACTIVE,
                        ],
                        'order' => [
                            'BlogCategories.display_order' => 'asc',
                        ],
                    ])->toArray();
            if (empty($categoryList)) {
                return [];
            }
            self::$blogCategories = $categoryList;
        }
        return self::$blogCategories;
    }

    public function blogPagination($categoryId = false, $page = 1) {
        Utils::useTables($this, ['Blogs']);
        Utils::useComponents($this, ['Backend.MultiLanguage']);
        $currentLanguage = $this->MultiLanguage->getCurrentLanguage();
        $options = [
            'contain' => [
                'BlogCategories',
            ],
            'conditions' => [
                [
                    'OR' => [
                        'BlogCategories.status' => ACTIVE,
                        'BlogCategories.id IS NULL',
                    ],
                ],
                'Blogs.status' => ACTIVE,
                'Blogs.published_date <=' => date('Y-m-d H:i:s'),
            ],
            'order' => [
                'Blogs.published_date' => 'desc',
            ],
        ];
        if (!empty($categoryId)) {
            $options['conditions']['Blogs.category_id'] = $categoryId;
        }
        $totalBlogs = $this->Blogs->find('all', $options)->count();
        $totalPage = ceil($totalBlogs / BLOG_PER_PAGE);
        $view = new \Cake\View\View();
        $view->setLayout(false);
        $view->set('totalPage', $totalPage);
        $view->set('page', $page);
        $html = $view->render('/Element/Blogs/blog_pagination');
        return $html;
    }

    public function blogList($categoryId = false, $page = 1) {
        Utils::useTables($this, [
            'Blogs',
        ]);
        Utils::useComponents($this, ['Backend.MultiLanguage']);
        $currentLanguage = $this->MultiLanguage->getCurrentLanguage();
        $options = [
            'contain' => [
                'BlogCategories',
                'Title' . ucfirst($currentLanguage),
                'Content' . ucfirst($currentLanguage),
                'Thumbnails',
            ],
            'conditions' => [
                [
                    'OR' => [
                        'BlogCategories.status' => ACTIVE,
                        'BlogCategories.id IS NULL',
                    ],
                ],
                'Blogs.status' => ACTIVE,
                'Blogs.published_date <=' => date('Y-m-d H:i:s'),
            ],
            'order' => [
                'Blogs.published_date' => 'desc',
            ],
            'page' => $page,
            'limit' => BLOG_PER_PAGE,
        ];
        if (!empty($categoryId)) {
            $options['conditions']['Blogs.category_id'] = $categoryId;
        }
        $blogList = $this->Blogs->find('all', $options)->toArray();
        $view = new \Cake\View\View();
        $view->setLayout(false);
        $view->set('blogList', $blogList);
        $html = $view->render('/Element/Blogs/blog_list');
        return $html;
    }

    public function blogRelated($currentBlog = false) {
        Utils::useTables($this, ['Blogs']);
        Utils::useComponents($this, ['Backend.MultiLanguage']);
        $currentLanguage = $this->MultiLanguage->getCurrentLanguage();
        $conditions = [
            [
                'OR' => [
                    'BlogCategories.status' => ACTIVE,
                    'BlogCategories.id IS NULL',
                ],
            ],
            'Blogs.status' => ACTIVE,
            'Blogs.published_date <=' => date('Y-m-d H:i:s'),
        ];
        if (!empty($currentBlog)) {
            $conditions = array_merge($conditions, [
                'Blogs.id <> ' => $currentBlog->id,
                'Blogs.category_id' => $currentBlog->category_id,
            ]);
        }
        $blogList = $this->Blogs->find('all', [
                    'conditions' => $conditions,
                    'contain' => [
                        'BlogCategories',
                        'BlogCategories.Title' . ucfirst($currentLanguage),
                        'Title' . ucfirst($currentLanguage),
                        'Thumbnails',
                    ],
                    'order' => [
                        'Blogs.published_date' => 'desc'
                    ],
                    'limit' => self::RELATED_BLOGS_PER_LIST,
                    'page' => 1,
                ])->toArray();
        if (empty($blogList)) {
            return false;
        }
        $view = new \Cake\View\View();
        $view->setLayout(false);
        $view->set('blogList', $blogList);
        $html = $view->render('/Element/Blogs/related_blog_list');
        return $html;
    }

    public function blogFeatured() {
        Utils::useTables($this, ['Blogs']);
        Utils::useComponents($this, ['Backend.MultiLanguage']);
        $currentLanguage = $this->MultiLanguage->getCurrentLanguage();
        $conditions = [
            'BlogCategories.status' => ACTIVE,
            'Blogs.featured' => ACTIVE,
            'Blogs.status' => ACTIVE,
            'Blogs.published_date <=' => date('Y-m-d H:i:s'),
        ];
        $blogList = $this->Blogs->find('all', [
                    'conditions' => $conditions,
                    'contain' => [
                        'BlogCategories',
                        'BlogCategories.Title' . ucfirst($currentLanguage),
                        'Title' . ucfirst($currentLanguage),
                        'Thumbnails',
                    ],
                    'order' => [
                        'Blogs.published_date' => 'desc'
                    ],
                    'limit' => self::FEATURED_BLOGS_PER_LIST,
                    'page' => 1,
                ])->toArray();
        if (empty($blogList)) {
            return false;
        }
        $view = new \Cake\View\View();
        $view->setLayout(false);
        $view->set('blogList', $blogList);
        $html = $view->render('/Element/Blogs/featured_blog_list');
        return $html;
    }

    public function bannerSlider($bannerPosition = false) {
        if ($bannerPosition === false) {
            return false;
        }
        Utils::useTables($this, ['Banners']);
        $bannerList = $this->Banners->find('all', [
                    'contain' => [
                        'Photos',
                    ],
                    'conditions' => [
                        'Banners.status' => ACTIVE,
                        'Banners.position' => $bannerPosition,
                    ],
                    'order' => [
                        'Banners.display_order' => 'asc',
                    ],
                ])->toArray();
        if (empty($bannerList)) {
            return false;
        }
        $view = new \Cake\View\View();
        $view->setLayout(false);
        $this->_setConfig($view);
        $view->set('bannerPosition', $bannerPosition);
        $view->set('bannerList', $bannerList);
        $html = $view->render('/Element/banner_slider');
        return $html;
    }

    public function workingContactPanel() {
        $view = new \Cake\View\View();
        $view->setLayout(false);
        $this->_setConfig($view);
        Utils::useComponents($this, ['Backend.MultiLanguage']);
        $languaCode = $this->MultiLanguage->getCurrentLanguageCode();
        $view->set('currentLangCode', $languaCode);
        $html = $view->render('/Element/working_hours');
        return $html;
    }

}
