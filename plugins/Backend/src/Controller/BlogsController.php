<?php

namespace Backend\Controller;

use App\Utility\Utils;
use Backend\Controller\CrudController;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\ORM\Entity;

class BlogsController extends CrudController {

    protected $slug = 'title';
    protected $hasSeo = true;
    protected $hasListSeo = false;
    protected $toggleFields = ['featured'];
    protected $multiLangFields = [
        'title' => [
            'input' => 'text',
            'label' => 'Title',
            'validation' => [
                'notBlank' => 'Please input %s!',
            ],
        ],
        'content' => [
            'input' => 'ckeditor',
            'label' => 'Content',
        ],
    ];
    protected $singlePhotos = [
        'thumbnail' => [
            'label' => 'Thumbnail',
            'isRequired' => true,
            'fixRatio' => false,
            'width' => 360,
            'height' => 360,
        ],
    ];
    protected $listViewCols = [
        'blog_title_%languageLabel%.content' => [
            'filter' => 'BlogTitle%upperLanguageLabel%.content',
            'label' => 'Title',
        ],
        'status' => [
            'label' => 'Status',
        ],
        'featured' => [
            'label' => 'Featured',
        ],
        'blog_category.category_title_%languageLabel%.content' => [
            'filter' => 'CategoryTitle%upperLanguageLabel%.content',
            'label' => 'Blog Category',
        ],
    ];
    protected $detailViewCols = [
        'id' => [
            'label' => 'ID',
        ],
        'blog_category.category_title_%languageLabel%.content' => [
            'label' => 'Blog Category',
        ],
        'blog_title_%languageLabel%.content' => [
            'label' => 'Title',
        ],
        'content_%languageLabel%.content' => [
            'label' => 'Content',
        ],
        'published_date' => [
            'label' => 'Publish Date',
        ],
        'status' => [
            'label' => 'Status',
        ],
        'featured' => [
            'label' => 'Featured',
        ],
        'thumbnail' => [
            'label' => 'Thumbnail',
        ],
    ];
    protected $createUpdateFields = [
        'category_id' => [
            'input' => 'dropdown',
            'label' => 'Blog Category',
            'currentValue' => false,
        ],
        'published_date' => [
            'input' => 'datepicker',
            'label' => 'Publish Date',
            'currentValue' => false,
        ],
    ];
    protected $defaultSorting = [
        'field' => 'BlogTitle%upperLanguageLabel%.content',
        'order' => 'ASC',
    ];
    protected $modelName = 'Blogs';
    protected $modelPlugin = 'App';
    protected $containModel = [
        'BlogTitle%upperLanguageLabel%',
        'Content%upperLanguageLabel%',
        'BlogCategories',
        'BlogCategories.CategoryTitle%upperLanguageLabel%',
    ];
    protected $searchingFields = [
        'BlogTitle%upperLanguageLabel%.content',
        'CategoryTitle%upperLanguageLabel%.content',
    ];

    public function initialize() {
        parent::initialize();
        $this->activationFields = [
            'Blogs.status' => $this->model->getStatusList(),
        ];
    }

    protected function _prepareObject(Entity $record) {
        $publishDate = false;
        if (!empty($record) && !empty($record->published_date)) {
            if (is_object($record->published_date)) {
                $publishDate = $record->published_date->i18nFormat('dd/MM/yyyy');
            } else {
                $publishDate = date('d/m/Y', strtotime($record->published_date));
            }
        }
        $this->createUpdateFields['published_date']['currentValue'] = $publishDate;

        Utils::useTables($this, ['App.BlogCategories']);
        $categoryList = $this->BlogCategories->find('all', [
                    'contain' => [
                        'Title' . ucfirst($this->currentLanguage),
                    ],
                    'order' => [
                        'Title' . ucfirst($this->currentLanguage) . '.content' => 'ASC',
                    ],
                ])->toArray();
        $categoryDropdown = [
            0 => __('Not select'),
        ];
        foreach ($categoryList as $category) {
            $categoryDropdown[$category->id] = $category->getTitle();
        }
        $this->createUpdateFields['category_id']['options'] = $categoryDropdown;
        return parent::_prepareObject($record);
    }

}
