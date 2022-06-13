<?php

namespace Backend\Controller;

use App\Utility\Utils;
use Backend\Controller\CrudController;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\ORM\Entity;

class PagesController extends CrudController {

    protected $slug = 'title';
    protected $hasSeo = true;
    protected $hasOrder = [
        'filter' => []
    ];
    protected $multiLangFields = [
        'title' => [
            'input' => 'text',
            'label' => 'Title',
            'validation' => [
                'notBlank' => 'Please input %s!',
                'maxLength' => [
                    'validationValue' => 60,
                    'errorMsg' => '%s limit is %validationValue% characters!',
                ],
            ],
        ],
        'content' => [
            'input' => 'ckeditor',
            'label' => 'Content',
        ],
    ];
    protected $listViewCols = [
        'page_title_%languageLabel%.content' => [
            'filter' => 'PageTitle%upperLanguageLabel%.content',
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
        'page_title_%languageLabel%.content' => [
            'label' => 'Title',
        ],
        'content_%languageLabel%.content' => [
            'label' => 'Content',
        ],
        'status' => [
            'label' => 'Status',
        ],
    ];
    protected $createUpdateFields = [];
    protected $defaultSorting = [
        'field' => 'PageTitle%upperLanguageLabel%.content',
        'order' => 'ASC',
    ];
    protected $modelName = 'Pages';
    protected $modelPlugin = 'App';
    protected $containModel = [
        'PageTitle%upperLanguageLabel%',
        'Content%upperLanguageLabel%',
    ];
    protected $searchingFields = [
        'PageTitle%upperLanguageLabel%.content',
    ];

    public function initialize() {
        parent::initialize();
        $this->activationFields = [
            'Pages.status' => $this->model->getStatusList(),
        ];
    }

}
