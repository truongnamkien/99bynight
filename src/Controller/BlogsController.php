<?php

namespace App\Controller;

use App\Model\Entity\Blog;
use App\Model\Entity\BlogCategory;
use Cake\Core\Configure;
use Cake\Event\Event;
use App\Utility\Utils;

class BlogsController extends AppController {

    protected static $currentCategory = false;

    public function beforeFilter(Event $event) {
        $this->Auth->allow();
    }

    public function index($languageCode) {
        return $this->prepareListPage($languageCode);
    }

    public function category($slug, $languageCode = LANGUAGE_VIETNAMESE) {
        Utils::useComponents($this, ['Backend.Slug', 'Backend.MultiLanguage']);
        $categoryId = $this->Slug->getTargetId($slug, 'BlogCategories');
        if (empty($categoryId)) {
            return $this->redirectHome();
        }
        Utils::useTables($this, ['BlogCategories']);
        $currentLanguage = $this->MultiLanguage->getCurrentLanguage();
        self::$currentCategory = $this->BlogCategories->find('all', [
                    'conditions' => [
                        'BlogCategories.id' => $categoryId,
                        'BlogCategories.status' => ACTIVE,
                    ],
                    'contain' => [
                        'Title' . ucfirst($currentLanguage),
                    ],
                ])->first();
        if (empty(self::$currentCategory)) {
            return $this->redirectHome();
        }
        $this->set('currentCategory', self::$currentCategory);
        return $this->prepareListPage($languageCode);
    }

    protected function prepareListPage($languageCode) {
        $this->_setLanguage($languageCode);
        $view = new \Cake\View\View();
        $view->helpers(['Link']);
        $breadcrumb = [
            [
                'url' => $view->Link->blogListUrl(),
                'title' => __('Blogs'),
            ],
        ];
        $currentPage = 'blogs';
        if (!empty(self::$currentCategory)) {
            $this->pageTitle = self::$currentCategory->getTitle();
            $currentPage .= '-blogCategory-' . self::$currentCategory->id . '-';
        } else {
            $this->pageTitle = __('Blogs');
        }
        $this->set('currentPage', $currentPage);
        $this->set('breadcrumb', $breadcrumb);
        $this->render('/Blogs/index');
    }

    public function loadBlogs() {
        if (!$this->request->is('ajax')) {
            return $this->redirectHome();
        }
        $categoryId = !empty($this->request->data['categoryId']) ? trim($this->request->data['categoryId']) : 0;
        $page = !empty($this->request->data['page']) ? trim($this->request->data['page']) : 1;
        $view = new \Cake\View\View();
        $view->layout(false);
        $view->loadHelper('Content');
        $blogHtml = $view->Content->blogList($categoryId, $page);
        $paginationHtml = $view->Content->blogPagination($categoryId, $page);
        Utils::useComponents($this, ['Backend.AsyncResponse']);
        $this->AsyncResponse->html("#blog-list", $blogHtml);
        $this->AsyncResponse->html("#blog-pagination", $paginationHtml);
        $this->AsyncResponse->run("
            var postgallery = $('.blog-isotope');
		postgallery.imagesLoaded(function () {
                    postgallery.isotope({
                            itemSelector: '.blog-post',
                            masonry: {
                                    gutter: 30,
                                    columnWidth: '.blog-post'
                            }
                    });
                    setTimeout(function () {
                            postgallery.parent('.gallery-wrap').addClass('loaded');
                    }, 500);
		});
            ");
        return $this->sendAsyncResponse();
    }

    public function detail($slug, $languageCode = LANGUAGE_VIETNAMESE) {
        $this->_setLanguage($languageCode);
        Utils::useComponents($this, ['Backend.Slug']);
        $blogId = $this->Slug->getTargetId($slug, 'Blogs');
        if (empty($blogId)) {
            return $this->redirectHome();
        }
        Utils::useTables($this, ['Blogs']);
        Utils::useComponents($this, ['Backend.MultiLanguage']);
        $currentLanguage = $this->MultiLanguage->getCurrentLanguage();
        $blog = $this->Blogs->find('all', [
                    'conditions' => [
                        'Blogs.id' => $blogId,
                        'Blogs.status' => ACTIVE,
                        [
                            'OR' => [
                                'BlogCategories.status' => ACTIVE,
                                'BlogCategories.id IS NULL',
                            ],
                        ],
                    ],
                    'contain' => [
                        'Title' . ucfirst($currentLanguage),
                        'Content' . ucfirst($currentLanguage),
                        'BlogCategories',
                        'BlogCategories.Title' . ucfirst($currentLanguage),
                        'Thumbnails',
                    ],
                ])->first();
        if (empty($blog)) {
            return $this->redirectHome();
        }
        $this->pageTitle = $blog->getTitle();
        $this->pageDesc = Utils::shortDescription($blog->getContent(), 50);
        $view = new \Cake\View\View();
        $view->helpers(['Link']);
        $breadcrumb = [
            [
                'url' => $view->Link->blogListUrl(),
                'title' => __('Blogs'),
            ],
        ];
        $currentPage = 'blogs';
        if (!empty($blog->blog_category)) {
            $currentPage .= '-blogCategory-' . $blog->blog_category->id . '-';
        }
        $currentPage .= '-blogDetail-' . $blog->id . '-';
        $this->set('currentPage', $currentPage);
        $this->set('breadcrumb', $breadcrumb);
        $this->set('blog', $blog);
    }

}
