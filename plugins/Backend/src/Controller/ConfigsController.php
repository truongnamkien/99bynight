<?php

namespace Backend\Controller;

use App\Utility\Utils;
use App\Model\Entity\Config;
use Backend\Controller\CrudController;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\ORM\Entity;

class ConfigsController extends FsBackendController {

    protected $createUpdateFields = [
        Config::CONFIG_KEY_PHONE => [
            'input' => 'text',
            'label' => 'Phone',
            'currentValue' => false,
        ],
        Config::CONFIG_KEY_EMAIL => [
            'input' => 'email',
            'label' => 'Email',
            'currentValue' => false,
        ],
        Config::CONFIG_KEY_SYSTEM_EMAIL => [
            'input' => 'email',
            'label' => 'System Email',
            'currentValue' => false,
        ],
        Config::CONFIG_KEY_SYSTEM_EMAIL_PASSWORD => [
            'input' => 'password',
            'label' => 'System Email Password',
            'currentValue' => false,
        ],
        Config::CONFIG_KEY_KEYWORD => [
            'input' => 'multi_tag',
            'label' => 'Web Keyword',
            'currentValue' => false,
        ],
        Config::CONFIG_KEY_SOCIAL_FACEBOOK => [
            'input' => 'text',
            'label' => 'Fanpage Facebook',
            'currentValue' => false,
        ],
        Config::CONFIG_KEY_DESCRIPTION_VIETNAMESE => [
            'input' => 'textarea',
            'label' => 'Web Description',
            'currentValue' => false,
        ],
        Config::CONFIG_KEY_WORKING_MON_FRI => [
            'input' => 'textarea',
            'label' => 'Working Time (Mon - Fri)',
            'currentValue' => false,
        ],
        Config::CONFIG_KEY_WORKING_SAT => [
            'input' => 'textarea',
            'label' => 'Working Time (Sat)',
            'currentValue' => false,
        ],
        Config::CONFIG_KEY_WORKING_SUN => [
            'input' => 'textarea',
            'label' => 'Working Time (Sun)',
            'currentValue' => false,
        ],
        Config::CONFIG_KEY_ADDRESS_VIETNAMESE => [
            'input' => 'text',
            'label' => 'Address',
            'currentValue' => false,
        ],
        Config::CONFIG_KEY_LOCATION => [
            'input' => 'google-map',
            'label' => 'Google Map Location',
            'currentValue' => false,
        ],
    ];

    public function initialize() {
        parent::initialize();
        Utils::useTables($this, ['App.Configs']);
        $this->modelName = 'Configs';
        $this->model = $this->Configs;
    }

    public function index() {
        $inputList = $this->createUpdateFields;
        $configList = $this->Configs->find('all')->toArray();
        foreach ($configList as $configInfo) {
            if (isset($inputList[$configInfo->field])) {
                $inputList[$configInfo->field]['currentValue'] = $configInfo->content;
            }
            if ($configInfo->field == Config::CONFIG_KEY_LOCATION) {
                $inputList[$configInfo->field]['currentValue'] = json_decode($configInfo->content, true);
            }
        }
        $this->set('inputList', $inputList);
        $this->render('Backend.Element/Crud/create_update_view');
    }

    public function submitUpdate() {
        if (!$this->request->is('ajax')) {
            return $this->redirectDashboard();
        }
        $submitData = $this->request->getData();
        $errorList = [];
        $deletedFields = [];
        $locationContent = [
            'longtitude' => !empty($submitData['longtitude_' . Config::CONFIG_KEY_LOCATION]) ? $submitData['longtitude_' . Config::CONFIG_KEY_LOCATION] : false,
            'latitude' => !empty($submitData['latitude_' . Config::CONFIG_KEY_LOCATION]) ? $submitData['latitude_' . Config::CONFIG_KEY_LOCATION] : false,
        ];
        $submitData[Config::CONFIG_KEY_LOCATION] = json_encode($locationContent);
        foreach ($this->createUpdateFields as $configField => $fieldInfo) {
            if (!empty($submitData[$configField])) {
                $configInfo = $this->Configs->find('all', [
                            'conditions' => [
                                'field' => $configField,
                            ],
                        ])->first();
                if (empty($configInfo)) {
                    $configInfo = $this->Configs->newEntity([
                        'field' => $configField,
                    ]);
                }
                $configInfo->content = $submitData[$configField];
                if (!$this->Configs->save($configInfo)) {
                    $errors = $configInfo->getErrors();
                    $fieldErrors = array_shift($errors);
                    $errorList[$configField] = __($fieldInfo['label']) . ': ' . array_shift($fieldErrors);
                }
            } else {
                $deletedFields[] = $configField;
            }
        }
        if (!empty($deletedFields)) {
            $this->Configs->deleteAll([
                'field IN' => $deletedFields,
            ]);
        }

        $this->AsyncResponse->run("$('.form-group').removeClass('has-danger');");
        $this->AsyncResponse->run("$('.form-control').removeClass('is-invalid');");
        if (!empty($errorList)) {
            foreach ($errorList as $field => $errorMsg) {
                $this->AsyncResponse->run("$('#form-group-{$field}').addClass('has-danger');");
                $this->AsyncResponse->run("$('#{$field}.form-control').addClass('is-invalid');");
            }
            $this->AsyncResponse->showAlert(is_array($errorList) ? implode('<br>', $errorList) : $errorList);
        } else {
            $this->AsyncResponse->showAlert(__('The data has been saved.'));
        }
        return $this->sendAsyncResponse();
    }

}
