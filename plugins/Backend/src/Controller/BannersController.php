<?php

namespace Backend\Controller;

use App\Utility\Utils;
use Backend\Controller\CrudController;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\ORM\Entity;

class BannersController extends CrudController {

    protected $hasOrder = [
        'filter' => [
            'position',
        ]
    ];
    protected $multiLangFields = [
        'title' => [
            'input' => 'textarea',
            'label' => 'Title',
        ],
    ];
    protected $singlePhotos = [
        'banner' => [
            'label' => 'Banner',
            'isRequired' => true,
            'fixRatio' => false,
            'width' => 1320,
            'height' => 720,
        ],
    ];
    protected $listViewCols = [
        'banner_title_%languageLabel%.content' => [
            'filter' => 'BannerTitle%upperLanguageLabel%.content',
            'label' => 'Title',
        ],
        'status' => [
            'label' => 'Status',
        ],
        'position' => [
            'label' => 'Position',
        ],
    ];
    protected $detailViewCols = [
        'id' => [
            'label' => 'ID',
        ],
        'banner_title_%languageLabel%.content' => [
            'label' => 'Title',
        ],
        'status' => [
            'label' => 'Status',
        ],
        'position' => [
            'label' => 'Position',
        ],
        'banner' => [
            'label' => 'Banner',
        ],
    ];
    protected $createUpdateFields = [
    ];
    protected $defaultSorting = [
        'field' => 'BannerTitle%upperLanguageLabel%.content',
        'order' => 'ASC',
    ];
    protected $modelName = 'Banners';
    protected $modelPlugin = 'App';
    protected $containModel = [
        'BannerTitle%upperLanguageLabel%',
    ];
    protected $searchingFields = [
        'BannerTitle%upperLanguageLabel%.content',
    ];

    public function initialize() {
        parent::initialize();
        $this->activationFields = [
            'Banners.status' => $this->model->getStatusList(),
            'Banners.position' => $this->model->getPositionList(),
        ];
    }

    protected function _prepareObject(Entity $record) {
        $this->activationFields['position'] = $this->model->getPositionList($record);
        return parent::_prepareObject($record);
    }

}
