<?php

namespace Backend\Controller;

use App\Utility\Utils;
use Backend\Controller\CrudController;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\ORM\Entity;

class ProductCategoriesController extends CrudController {

    protected $hasOrder = [
        'filter' => []
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
        'category_title_%languageLabel%.content' => [
            'filter' => 'CategoryTitle%upperLanguageLabel%.content',
            'label' => 'Title',
        ],
        'status' => [
            'label' => 'Status',
        ],
    ];
    protected $detailViewCols = [
        'id' => [
            'label' => 'ID',
        ],
        'category_title_%languageLabel%.content' => [
            'label' => 'Title',
        ],
        'status' => [
            'label' => 'Status',
        ],
    ];
    protected $createUpdateFields = [];
    protected $defaultSorting = [
        'field' => 'CategoryTitle%upperLanguageLabel%.content',
        'order' => 'ASC',
    ];
    protected $modelName = 'ProductCategories';
    protected $modelPlugin = 'App';
    protected $containModel = [
        'CategoryTitle%upperLanguageLabel%',
    ];
    protected $searchingFields = [
        'CategoryTitle%upperLanguageLabel%.content',
    ];

    public function initialize() {
        parent::initialize();
        $this->activationFields = [
            'ProductCategories.status' => $this->model->getStatusList(),
        ];
    }

}
