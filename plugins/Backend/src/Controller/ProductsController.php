<?php

namespace Backend\Controller;

use App\Utility\Utils;
use Backend\Controller\CrudController;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\ORM\Entity;

class ProductsController extends CrudController {

    protected $hasOrder = [
        'filter' => [
            'category_id',
        ]
    ];
    protected $multiLangFields = [
        'title' => [
            'input' => 'text',
            'label' => 'Title',
            'validation' => [
                'notBlank' => 'Please input %s!',
            ],
        ],
    ];
    protected $listViewCols = [
        'product_title_%languageLabel%.content' => [
            'filter' => 'ProductTitle%upperLanguageLabel%.content',
            'label' => 'Title',
        ],
        'status' => [
            'label' => 'Status',
        ],
        'product_category.category_title_%languageLabel%.content' => [
            'filter' => 'CategoryTitle%upperLanguageLabel%.content',
            'label' => 'Product Category',
        ],
    ];
    protected $detailViewCols = [
        'id' => [
            'label' => 'ID',
        ],
        'product_category.category_title_%languageLabel%.content' => [
            'label' => 'Product Category',
        ],
        'product_title_%languageLabel%.content' => [
            'label' => 'Title',
        ],
        'status' => [
            'label' => 'Status',
        ],
    ];
    protected $createUpdateFields = [
        'category_id' => [
            'input' => 'dropdown',
            'label' => 'Product Category',
            'currentValue' => false,
        ],
    ];
    protected $defaultSorting = [
        'field' => 'ProductTitle%upperLanguageLabel%.content',
        'order' => 'ASC',
    ];
    protected $modelName = 'Products';
    protected $modelPlugin = 'App';
    protected $containModel = [
        'ProductTitle%upperLanguageLabel%',
        'ProductCategories',
        'ProductCategories.CategoryTitle%upperLanguageLabel%',
    ];
    protected $searchingFields = [
        'ProductTitle%upperLanguageLabel%.content',
        'CategoryTitle%upperLanguageLabel%.content',
    ];

    public function initialize() {
        parent::initialize();
        $this->activationFields = [
            'Products.status' => $this->model->getStatusList(),
        ];
    }

    protected function _prepareObject(Entity $record) {
        Utils::useTables($this, ['App.ProductCategories']);
        $categoryList = $this->ProductCategories->find('all', [
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
