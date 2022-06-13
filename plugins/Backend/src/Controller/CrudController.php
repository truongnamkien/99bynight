<?php

namespace Backend\Controller;

use App\Utility\Utils;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\I18n\Date;
use Cake\ORM\Entity;
use Cake\Routing\Router;
use Cake\Utility\Inflector;
use Cake\Validation\Validation;
use Sluggable\Utility\Slug;

class CrudController extends FsBackendController {

    protected $model = null;
    protected $modelPlugin = 'App';
    protected $modelName = false;
    protected $multiLangFields = [];
    protected $hasOrder = [];
    protected $hasListSeo = false;
    protected $hasSeo = false;
    protected $slug = false;
    protected $invalidActions = [];
    protected $filterFields = [];
    protected $activationFields = [];
    protected $toggleFields = [];
    protected $singlePhotos = [];
    protected $recordPerPage = 10;
    protected $languageList = [];
    protected $listViewCols = [];
    protected $detailViewCols = [];
    protected $createUpdateFields = [];
    protected $defaultSorting = [];
    protected $containModel = [];
    protected $searchingFields = [];
    protected $manyToManyFields = [];

    public function initialize() {
        parent::initialize();
        if (empty($this->languageList)) {
            $this->languageList = Configure::read('LanguageList');
        }
        Utils::useTables($this, [$this->modelPlugin . '.' . $this->modelName]);
        $this->model = $this->{$this->modelName};
        $parseList = [
            'listViewCols',
            'detailViewCols',
            'defaultSorting',
            'containModel',
            'searchingFields',
        ];
        foreach ($parseList as $key) {
            if (!empty($this->$key)) {
                $jsonStr = json_encode($this->$key);
                $jsonStr = str_replace('%languageLabel%', $this->currentLanguage, $jsonStr);
                $jsonStr = str_replace('%upperLanguageLabel%', ucfirst($this->currentLanguage), $jsonStr);
                $this->$key = json_decode($jsonStr, true);
            }
        }
        $this->set('languageList', $this->languageList);
    }

    public function beforeFilter(Event $event) {
        if (!empty($this->invalidActions) && in_array($this->currentAction, $this->invalidActions)) {
            return $this->showInvalidAction();
        }
        return parent::beforeFilter($event);
    }

    public function index() {
        if (!empty($this->activationFields)) {
            foreach ($this->activationFields as $field => $optionList) {
                $options = [];
                foreach ($optionList as $value => $optionItem) {
                    $options[$value] = $optionItem['label'];
                }
                $this->filterFields[$field] = [
                    'options' => $options,
                ];
            }
        }
        if (!empty($this->toggleFields)) {
            foreach ($this->toggleFields as $field) {
                $this->filterFields[$field] = [
                    'options' => [
                        ACTIVE => __('On'),
                        INACTIVE => __('Off'),
                    ],
                ];
            }
        }
        $this->set('modelName', $this->modelName);
        $this->set('filterFields', $this->filterFields);
        $this->set('searchingFields', $this->searchingFields);
        $this->set('listViewCols', $this->listViewCols);
        $this->set('defaultSorting', $this->defaultSorting);
        $this->set('mainNav', $this->_mainNav());
        $this->render('/Element/Crud/list_view');
    }

    protected function prepareCoreConditions() {
        return [];
    }

    public function loadList() {
        if (!$this->request->is('ajax')) {
            return $this->redirectDashboard();
        }
        $pageIndex = $this->request->getData('pageIndex', 1);
        $pageSize = $this->request->getData('pageSize', 0);
        $sortField = $this->request->getData('sortField', false);
        $sortOrder = $this->request->getData('sortOrder', 'ASC');
        $filterList = $this->request->getData('filter', []);
        $keyword = $this->request->getData('filter_keyword', []);
        $conditions = $this->prepareCoreConditions();
        if (!empty($filterList)) {
            foreach ($filterList as $filterField => $filterValue) {
                if ($filterValue !== '' && $filterValue !== false) {
                    $conditions[$filterField] = $filterValue;
                }
            }
        }
        if (!empty($this->searchingFields) && !empty($keyword)) {
            $keyword = mb_strtolower($keyword);
            $keywordConditions = [];
            foreach ($this->searchingFields as $searchingField) {
                $keywordConditions['LOWER(' . $searchingField . ') LIKE'] = "%{$keyword}%";
            }
            if (!empty($keywordConditions)) {
                $conditions['OR'] = $keywordConditions;
            }
        }
        if (empty($pageSize) || $pageSize <= 0) {
            $pageSize = !empty($this->recordPerPage) && $this->recordPerPage > 0 ? $this->recordPerPage : 10;
        }
        $options = [
            'page' => $pageIndex > 0 ? $pageIndex : 1,
            'limit' => $pageSize,
        ];
        if (!empty($conditions)) {
            $options['conditions'] = $conditions;
        }
        if (!empty($sortField)) {
            $options['order'] = [
                $sortField => $sortOrder,
            ];
        }
        if (!empty($this->containModel)) {
            $options['contain'] = $this->containModel;
        }
        $recordList = $this->model->find('all', $options)->toArray();
        foreach ($recordList as $record) {
            $record->actionList = $this->_setActions($record);
        }
        $tableView = new \Cake\View\View();
        $tableView->setLayout(false);
        $tableView->set('recordList', $recordList);
        $tableView->set('listViewCols', $this->listViewCols);
        $tableView->set('activationFields', $this->activationFields);
        $tableView->set('toggleFields', $this->toggleFields);
        $tableView->set('modelName', $this->modelName);
        $tableView->set('singlePhotos', $this->singlePhotos);
        $tableHtml = $tableView->render('Backend.Element/Crud/list_view_table');
        $this->AsyncResponse->html('#list-data', $tableHtml);

        $totalRecords = $this->model->find('all', $options)->count();
        $totalPage = ceil($totalRecords / $pageSize);
        $paginationView = new \Cake\View\View();
        $paginationView->setLayout(false);
        $paginationView->set('totalPage', $totalPage);
        $paginationView->set('pageIndex', $pageIndex);
        $paginationView->set('pageSize', $pageSize);
        $paginationView->set('totalRecords', $totalRecords);
        $paginationHtml = $paginationView->render('Backend.Element/Crud/list_view_pagination');
        $this->AsyncResponse->html('#list-pagination', $paginationHtml);
        return $this->sendAsyncResponse();
    }

    protected function _setActions($record) {
        $actionList = [
            'edit' => [
                'url' => Router::url(['action' => 'edit', $record->id], true),
                'label' => 'Edit',
                'icon' => 'edit',
                'textColor' => 'yellow',
            ],
            'detail' => [
                'url' => Router::url(['action' => 'detail', $record->id], true),
                'label' => 'Detail',
                'icon' => 'eye',
                'textColor' => 'green',
            ],
            'delete' => [
                'url' => Router::url(['action' => 'delete', $record->id], true),
                'label' => 'Delete',
                'icon' => 'trash',
                'textColor' => 'red',
            ],
        ];
        if (!empty($this->hasSeo)) {
            $actionList['seo'] = [
                'url' => Router::url(['action' => 'seo', $record->id], true),
                'label' => 'SEO Keyword',
                'textColor' => 'blue',
                'icon' => 'search',
            ];
        }
        if (!empty($this->hasOrder)) {
            $actionList['move'] = [
                'url' => Router::url(['action' => 'move', $record->id], true),
                'label' => 'Move',
                'textColor' => 'green',
                'icon' => 'sort',
            ];
        }
        if (!empty($this->invalidActions)) {
            foreach ($this->invalidActions as $action) {
                unset($actionList[$action]);
            }
        }
        return $actionList;
    }

    public function detail($id) {
        $contain = [];
        if (!empty($this->singlePhotos)) {
            foreach ($this->singlePhotos as $field => $photoInfo) {
                $contain[] = str_replace('_', '', ucwords(Inflector::pluralize($field), '_'));
            }
        }
        $record = $this->_getRecord($id, $contain, true);
        if (empty($record)) {
            $this->Flash->error(__('Data cannot found.'), ['plugin' => 'Backend']);
            return $this->redirect(['action' => 'index']);
        }
        $displayField = $this->model->getDisplayField();
        $this->pageTitle = __('Detail');
        $this->set('headerTitle', $this->pageTitle);
        $this->set('currentRecord', $record);
        $this->set('detailViewCols', $this->detailViewCols);
        $this->set('activationFields', $this->activationFields);
        $this->set('toggleFields', $this->toggleFields);
        $this->set('singlePhotos', $this->singlePhotos);
        $this->set('modelName', $this->modelName);
        $this->set('mainNav', $this->_mainNav($id));
        $this->render('Backend.Element/Crud/detail_view');
    }

    protected function _getRecord($id = null, $contain = [], $parsed = false) {
        $record = null;
        if (!empty($id)) {
            $conditions = $this->prepareCoreConditions();
            $conditions[$this->modelName . '.id'] = $id;
            $options = [
                'conditions' => $conditions,
            ];
            if (!empty($this->containModel)) {
                $contain = array_merge($contain, $this->containModel);
            }
            if (!empty($contain)) {
                $options['contain'] = $contain;
            }
            $record = $this->model->find('all', $options)->first();
        }
        return $record;
    }

    protected function _mainNav($id = false) {
        $navList = [];
        if ($this->currentAction == 'index') {
            if (!empty($this->hasListSeo)) {
                $navList['seo'] = [
                    'url' => Router::url(['action' => 'seo', 0], true),
                    'label' => 'SEO Keyword',
                    'icon' => 'search',
                    'button' => 'secondary',
                ];
            }
            $navList['add'] = [
                'url' => Router::url(['action' => 'add'], true),
                'label' => 'Add New',
                'icon' => 'plus',
                'button' => 'warning',
            ];
        } else {
            $navList['index'] = [
                'url' => Router::url(['action' => 'index'], true),
                'label' => 'List',
                'icon' => 'list-alt',
                'button' => 'secondary',
            ];
        }
        if (!empty($id)) {
            if ($this->currentAction != 'edit') {
                $navList['edit'] = [
                    'url' => Router::url(['action' => 'edit', $id], true),
                    'label' => 'Edit',
                    'icon' => 'edit',
                    'button' => 'warning',
                ];
            }
            if ($this->currentAction != 'detail') {
                $navList['detail'] = [
                    'url' => Router::url(['action' => 'detail', $id], true),
                    'label' => 'Detail',
                    'icon' => 'eye',
                    'button' => 'success',
                ];
            }
            $navList['delete'] = [
                'url' => Router::url(['action' => 'delete', $id], true),
                'label' => 'Delete',
                'icon' => 'trash',
                'button' => 'danger',
            ];
        }
        if (!empty($this->invalidActions)) {
            foreach ($this->invalidActions as $action) {
                unset($navList[$action]);
            }
        }
        return $navList;
    }

    public function add() {
        $this->pageTitle = __('Add New');
        $this->set('headerTitle', $this->pageTitle);
        $this->createUpdate();
    }

    public function edit($id) {
        $this->pageTitle = __('Edit');
        $this->set('headerTitle', $this->pageTitle);
        $this->createUpdate($id);
    }

    protected function handleSubmittedData() {
        $submitData = $this->request->getData();
        $errorList = [];
        if (!empty($this->singlePhotos)) {
            foreach ($this->singlePhotos as $field => $photoInfo) {
                if (!empty($photoInfo['isRequired']) && empty($submitData[$field])) {
                    $photoRecord = false;
                    if (!empty($submitData['recordId'])) {
                        Utils::useTables($this, ['Backend.Photos']);
                        $photoRecord = $this->Photos->getSinglePhoto($submitData['recordId'], $this->modelName, $field);
                    }
                    if (empty($photoRecord)) {
                        $errorList[$field] = sprintf(__('Please upload %s!'), __($photoInfo['label']));
                    }
                } else {
                    if (!empty($submitData[$field])) {
                        $photoPath = WWW_ROOT . $submitData[$field];
                        $photoSize = @getimagesize($photoPath);
                        $width = !empty($photoSize[0]) ? $photoSize[0] : 0;
                        $height = !empty($photoSize[1]) ? $photoSize[1] : 0;
                        if (!empty($photoInfo['width']) && $width < $photoInfo['width']) {
                            $errorList[$field] = sprintf(__('Minimum width of %s is %s px.'), __($photoInfo['label']), $photoInfo['width']);
                        } else if (!empty($photoInfo['height']) && $height < $photoInfo['height']) {
                            $errorList[$field] = sprintf(__('Minimum height of %s is %s px.'), __($photoInfo['label']), $photoInfo['height']);
                        } else if (!empty($photoInfo['width']) && !empty($photoInfo['height']) && !empty($photoInfo['fixRatio'])) {
                            if (($photoInfo['width'] / $photoInfo['height']) != ($width / $height)) {
                                $errorList[$field] = sprintf(__('Size of %s must be %s x %s px.'), __($photoInfo['label']), $photoInfo['width'], $photoInfo['height']);
                            }
                        }
                    }
                }
            }
        }
        unset($submitData['recordId']);
        return [
            'submitData' => $submitData,
            'errors' => $errorList,
        ];
    }

    protected function createUpdate($id = false) {
        if ($id) {
            $record = $this->_getRecord($id);
            if (empty($record)) {
                $this->Flash->error(__('Data cannot found.'), ['plugin' => 'Backend']);
                return $this->redirect(['action' => 'index']);
            }
        } else {
            $record = $this->model->newEntity();
        }
        $this->_prepareManyToManyFields($record);
        $ret = $this->_prepareObject($record);
        if (!$ret) {
            return $this->redirect(['action' => 'index']);
        }
        $this->_prepareObjectMultiLanguage($record);
        $this->set('currentRecord', $record);
        $this->set('mainNav', $this->_mainNav($id));
        $this->render('Backend.Element/Crud/create_update_view');
    }

    protected function _prepareObject(Entity $record) {
        $inputList = !empty($this->createUpdateFields) ? $this->createUpdateFields : [];
        if (!empty($record)) {
            foreach ($inputList as $field => &$inputItem) {
                if ($inputItem['currentValue'] === false) {
                    if (isset($record->$field)) {
                        $inputItem['currentValue'] = $record->$field;
                    } else if (!empty($inputItem['defaultValue'])) {
                        $inputItem['currentValue'] = $inputItem['defaultValue'];
                    }
                }
            }
        }
        $inputList = $this->_prepareCommonObject($inputList, $record);
        $this->set('inputList', $inputList);
        return true;
    }

    protected function _prepareObjectMultiLanguage(Entity $record) {
        if (!empty($this->multiLangFields)) {
            $multiLangInputTypes = [];
            foreach ($this->languageList as $languageCode => $languageLabel) {
                foreach ($this->multiLangFields as $field => $fieldInfo) {
                    $fieldInfo['currentValue'] = false;
                    $fieldStr = 'language_' . $languageCode . '_' . $field;
                    $multiLangInputTypes[$languageCode][$fieldStr] = $fieldInfo;
                    if (!empty($record)) {
                        if (!empty($record->$fieldStr)) {
                            $multiLangInputTypes[$languageCode][$fieldStr]['currentValue'] = $record->$fieldStr;
                        } elseif (!empty($record->id)) {
                            $multiLangInputTypes[$languageCode][$fieldStr]['currentValue'] = $this->_parseMultiLangField($record->id, $languageCode, $field);
                        }
                    }
                }
            }
            $this->set('multiLangInputTypes', $multiLangInputTypes);
        }
    }

    protected function _parseMultiLangField($targetId, $languageCode, $field) {
        $languageContent = $this->LanguageContents->find('all', [
                    'conditions' => [
                        'target_id' => $targetId,
                        'target_type' => $this->modelName,
                        'language' => $languageCode,
                        'field' => $field,
                    ],
                ])->first();
        if (!empty($languageContent)) {
            return $languageContent->content;
        }
        return false;
    }

    protected function _prepareCommonObject($inputList, Entity $record) {
        if (!empty($this->toggleFields)) {
            foreach ($this->toggleFields as $field) {
                $inputList[$field] = [
                    'input' => 'checkbox_toggle',
                    'label' => ucwords($field),
                    'currentValue' => !empty($record) && isset($record->$field) ? $record->$field : false,
                ];
            }
        }
        if (!empty($this->activationFields)) {
            foreach ($this->activationFields as $field => $optionList) {
                $fieldSection = explode('.', $field);
                $fieldTable = count($fieldSection) > 1 ? $fieldSection[0] : $this->modelName;
                $fieldName = $fieldSection[count($fieldSection) - 1];
                if ($fieldTable != $this->modelName || !empty($inputList[$fieldName])) {
                    continue;
                }
                $options = [];
                foreach ($optionList as $value => $optionItem) {
                    $options[$value] = $optionItem['label'];
                }
                $defaultValue = ACTIVE;
                if (empty($options[$defaultValue])) {
                    $defaultValue = false;
                }
                $inputList[$fieldName] = [
                    'input' => 'dropdown',
                    'label' => ucwords($fieldName),
                    'options' => $options,
                    'currentValue' => !empty($record) && isset($record->$fieldName) ? $record->$fieldName : $defaultValue,
                ];
            }
        }
        if (!empty($this->singlePhotos)) {
            Utils::useTables($this, ['Backend.Photos']);
            foreach ($this->singlePhotos as $field => $photoInfo) {
                if (!empty($record->id)) {
                    $photoRecord = $this->Photos->getSinglePhoto($record->id, $this->modelName, $field);
                }
                $inputList[$field] = array_merge($photoInfo, [
                    'input' => 'uploadPhoto',
                    'label' => $photoInfo['label'],
                    'accept' => implode(',', Configure::read('photoUpload.uploadAccept')),
                    'currentValue' => !empty($photoRecord) ? $photoRecord : false,
                ]);
            }
        }
        if (!empty($this->multiPhotos)) {
            Utils::useTables($this, ['Backend.Photos']);
            foreach ($this->multiPhotos as $field => $photoInfo) {
                if (!empty($record->id)) {
                    $photoList = $this->Photos->getListPhoto($record->id, $this->modelName, $field);
                }
                $inputList[$field] = [
                    'input' => 'multi-photo',
                    'label' => ucwords($field),
                    'accept' => implode(',', Configure::read('photoUpload.uploadAccept')),
                    'currentValue' => !empty($photoList) ? $photoList : false,
                ];
                if (!empty($photoInfo['additionFields'])) {
                    $inputList[$field]['additionFields'] = $photoInfo['additionFields'];
                }
            }
        }
        return $inputList;
    }

    public function submitUpdate() {
        if (!$this->request->is('ajax')) {
            return $this->redirectDashboard();
        }
        $id = $this->request->getData('recordId', false);
        if ($id) {
            $record = $this->_getRecord($id);
        } else {
            $record = $this->model->newEntity();
        }
        if (empty($record)) {
            $this->AsyncResponse->showAlert(__('Data cannot found.'));
            return $this->sendAsyncResponse();
        }
        $handle = $this->handleSubmittedData();
        $submitData = $handle['submitData'];
        $errorList = $handle['errors'];
        $record = $this->model->patchEntity($record, $submitData);
        if (!empty($this->toggleFields)) {
            foreach ($this->toggleFields as $field) {
                $record->$field = !empty($submitData[$field]) ? 1 : 0;
            }
        }
        $this->validateMultiLanguageFields($submitData, $errorList);
        $this->validateManyToManyFields($submitData);
//        $this->validateMultiPhotos($submitData, $errorList);
        if (empty($errorList) && $this->model->save($record)) {
            if (!empty($this->multiLangFields)) {
                foreach ($this->languageList as $languageCode => $languageLabel) {
                    foreach ($this->multiLangFields as $field => $fieldInfo) {
                        $value = !empty($submitData['language_' . $languageCode . '_' . $field]) ? $submitData['language_' . $languageCode . '_' . $field] : false;
                        $languageContent = $this->LanguageContents->updateLanguageContent($record->id, $this->modelName, $languageCode, $field, $value);
                        if (!empty($this->slug) && $this->slug == $field && !empty($languageContent)) {
                            Utils::useTables($this, ['Backend.Slugs']);
                            $slug = Slug::generate(":content", $languageContent);
                            $this->Slugs->updateSlug($record->id, $this->modelName, $languageCode, $slug);
                        }
                    }
                }
            } else {
                $languageCode = Configure::read('DefaultLanguage');
                if (!empty($this->slug) && !empty($record->{$this->slug})) {
                    Utils::useTables($this, ['Backend.Slugs']);
                    $slug = Slug::generate(":{$this->slug}", $record);
                    $this->Slugs->updateSlug($record->id, $this->modelName, $languageCode, $slug);
                }
            }
            if (!empty($this->singlePhotos)) {
                Utils::useTables($this, ['Backend.Photos']);
                foreach ($this->singlePhotos as $field => $photoInfo) {
                    if (!empty($submitData[$field])) {
                        $this->Photos->updateSinglePhoto($record->id, $this->modelName, $field, $submitData[$field]);
                    }
                }
            }
            if (!empty($this->multiPhotos) && !empty($photoIds)) {
                Utils::useTables($this, ['Backend.Photos']);
                $this->Photos->updateListPhoto($record->id, $this->modelName, $field, $submitData[$field]);
            }
            if (!empty($this->hasOrder) && empty($record->display_order)) {
                $record->display_order = $record->id;
                $this->model->save($record);
            }
            $this->handleManyToManyFields($record->id);
            $this->Flash->success(__('The data has been saved.'), ['plugin' => 'Backend']);
            $this->AsyncResponse->redirect(Router::url(['action' => 'index'], true));
            return $this->sendAsyncResponse();
        }
        $errors = $record->getErrors();
        foreach ($errors as $field => $fieldErrors) {
            if (empty($errorList[$field])) {
                $errorList[$field] = array_shift($fieldErrors);
            }
        }
        $this->AsyncResponse->run("$('.form-group').removeClass('has-danger');");
        $this->AsyncResponse->run("$('.form-control').removeClass('is-invalid');");
        if (!empty($errorList)) {
            foreach ($errorList as $field => $errorMsg) {
                $this->AsyncResponse->run("$('#form-group-{$field}').addClass('has-danger');");
                $this->AsyncResponse->run("$('#{$field}.form-control').addClass('is-invalid');");
            }
            $this->AsyncResponse->showAlert(is_array($errorList) ? implode('<br>', $errorList) : $errorList);
        }
        return $this->sendAsyncResponse();
    }

    protected function validateManyToManyFields($submitData) {
        if (!empty($this->manyToManyFields)) {
            foreach ($this->manyToManyFields as $field => $info) {
                if (!empty($submitData[$field])) {
                    $this->manyToManyFields[$field]['value'] = $submitData[$field];
                    unset($submitData[$field]);
                }
            }
        }
    }

    protected function _prepareManyToManyFields($record) {
        if (!empty($this->manyToManyFields) && !empty($record) && !empty($record->id)) {
            foreach ($this->manyToManyFields as $field => $info) {
                if (!empty($this->createUpdateFields[$field])) {
                    $tableName = $info['class'];
                    $foreignKey = $info['foreignKey'];
                    Utils::useTables($this, [$tableName]);
                    $model = $this->$tableName;
                    $relationList = $model->find('all', [
                                'conditions' => [
                                    $foreignKey => $record->id,
                                ],
                            ])->toArray();
                    $idList = [];
                    foreach ($relationList as $relationEntity) {
                        if (!empty($relationEntity->$field)) {
                            $idList[$relationEntity->$field] = $relationEntity->$field;
                        }
                    }
                    $this->createUpdateFields[$field]['currentValue'] = $idList;
                }
            }
        }
    }

    protected function handleManyToManyFields($recordId) {
        if (!empty($recordId) && !empty($this->manyToManyFields)) {
            foreach ($this->manyToManyFields as $field => $info) {
                $tableName = $info['class'];
                $foreignKey = $info['foreignKey'];
                Utils::useTables($this, [$tableName]);
                $model = $this->$tableName;
                $model->deleteAll([
                    $foreignKey => $recordId,
                ]);
                if (!empty($this->manyToManyFields[$field]['value'])) {
                    foreach ($this->manyToManyFields[$field]['value'] as $value) {
                        $relationEntity = $model->newEntity([
                            $foreignKey => $recordId,
                            $field => $value,
                        ]);
                        $model->save($relationEntity);
                    }
                }
            }
        }
    }

    public function submitPhoto() {
        Utils::useComponents($this, ['Backend.Upload']);
        $destinationFolder = Configure::read('Upload.PhotoFolder');
        $this->Upload->setDestination($destinationFolder);
        $this->Upload->setAllowExtensions(Configure::read('photoUpload.extensions'));
        $ret = $this->Upload->handleUpload('filedata', 'Photo');
        if (!empty($ret['error'])) {
            return $this->sendAjax(0, $ret['error']);
        }
        if (empty($ret['file'])) {
            return $this->sendAjax(0, __('Upload failed!'));
        }
        $photoPath = $destinationFolder . $ret['file'];
        $photoPath = str_replace(WWW_ROOT, '', $photoPath);
        return $this->sendAjax(1, '', [
                    'photoUrl' => $photoPath,
        ]);
    }

    public function delete($id) {
        $record = $this->_getRecord($id);
        if (empty($record)) {
            $this->Flash->error(__('Data cannot found.'), ['plugin' => 'Backend']);
        } else {
            $this->model->delete($record);
            Utils::useTables($this, [
                'Backend.Photos',
                'Backend.Slugs',
                'Backend.LanguageContents',
            ]);
            $this->Photos->deleteAll([
                'target_id' => $id,
                'target_type' => $this->modelName,
            ]);
            $this->Slugs->deleteAll([
                'target_id' => $id,
                'target_type' => $this->modelName,
            ]);
            $this->LanguageContents->deleteAll([
                'target_id' => $id,
                'target_type' => $this->modelName,
            ]);
            $this->Flash->success(__('The data has been deleted.'), ['plugin' => 'Backend']);
        }
        return $this->redirect(['action' => 'index']);
    }

    protected function validateMultiLanguageFields($submitData, &$errorList) {
        if (!empty($this->multiLangFields)) {
            foreach ($this->multiLangFields as $field => $fieldInfo) {
                if (!empty($fieldInfo['validation']) && is_array($fieldInfo['validation'])) {
                    foreach ($fieldInfo['validation'] as $validateAction => $validationInfo) {
                        if (is_array($validationInfo) && isset($validationInfo['errorMsg'])) {
                            $errorStr = $validationInfo['errorMsg'];
                        } elseif (is_string($validationInfo)) {
                            $errorStr = $validationInfo;
                        }
                        foreach ($this->languageList as $languageCode => $languageLabel) {
                            if (!empty($fieldInfo['allowEmpty']) && empty($submitData['language_' . $languageCode . '_' . $field])) {
                                continue;
                            }
                            $fieldLabel = __($fieldInfo['label']);
                            if (count($this->languageList) > 1) {
                                $fieldLabel .= ' (' . __($languageLabel) . ')';
                            }
                            $tmpErrorStr = __($errorStr);
                            $tmpErrorStr = str_replace('%s', $fieldLabel, $tmpErrorStr);
                            if (is_array($validationInfo) && isset($validationInfo['validationValue'])) {
                                $tmpErrorStr = str_replace('%validationValue%', $validationInfo['validationValue'], $tmpErrorStr);
                            }
                            $value = !empty($submitData['language_' . $languageCode . '_' . $field]) ? $submitData['language_' . $languageCode . '_' . $field] : false;
                            $ret = true;
                            if (method_exists('Cake\\Validation\\Validation', $validateAction)) {
                                $validateValue = is_array($validationInfo) && isset($validationInfo['validationValue']) ? $validationInfo['validationValue'] : false;
                                $ret = Validation::$validateAction($value, $validateValue);
                            }
                            if (!$ret && !empty($tmpErrorStr)) {
                                $errorList[] = $tmpErrorStr;
                            }
                        }
                    }
                }
            }
        }
    }

    public function move($id) {
        if (empty($this->hasOrder)) {
            $this->Flash->error(__('You do not have permission for this feature.'), ['plugin' => 'Backend']);
            return $this->redirect(['action' => 'index']);
        }
        $record = $this->_getRecord($id);
        if (empty($record)) {
            $this->Flash->error(__('Data cannot found.'), ['plugin' => 'Backend']);
            return $this->redirect(['action' => 'index']);
        }
        if (!empty($this->multiLangFields) && empty($record->display_field)) {
            $record->set('display_field', $this->_getObjectDisplayField($record));
        }
        $sameLevelObjects = $this->_getSameLevelObject($record);
        if (count($sameLevelObjects) <= 1) {
            $this->Flash->error(__('There is only 1 item in the list. Cannot move!'), ['plugin' => 'Backend']);
            return $this->redirect(['action' => 'index']);
        }
        $this->set(compact('record', 'sameLevelObjects'));
        $this->set('_serialize', ['record']);

        $this->pageTitle = __('Move');
        $this->set('headerTitle', $this->pageTitle);
        $this->set('mainNav', $this->_mainNav($id));
        $this->render('Backend.Element/Crud/move_view');
    }

    public function submitMove() {
        if (!$this->request->is('ajax')) {
            return $this->redirectDashboard();
        }
        $id = $this->request->getData('recordId', false);
        if ($id) {
            $record = $this->_getRecord($id);
        }
        if (empty($record)) {
            $this->Flash->error(__('Data cannot found.'), ['plugin' => 'Backend']);
            $this->AsyncResponse->redirect(Router::url(['action' => 'index'], true));
            return $this->sendAsyncResponse();
        }
        $submitData = $this->request->getData();
        $sameLevelObjects = $this->_getSameLevelObject($record);
        $targetRecord = $this->_getRecord($submitData['target_id']);
        if (empty($targetRecord)) {
            $this->Flash->error(__('Data cannot found.'), ['plugin' => 'Backend']);
        } elseif ($submitData['position'] < 0) {
            for ($i = 0; $i < count($sameLevelObjects); $i++) {
                if ($sameLevelObjects[$i]->display_order >= $targetRecord->display_order) {
                    break;
                }
                $nextDisplayOrder = $sameLevelObjects[$i]->display_order;
            }
        } else {
            for ($i = count($sameLevelObjects) - 1; $i >= 0; $i--) {
                if ($sameLevelObjects[$i]->display_order <= $targetRecord->display_order) {
                    break;
                }
                $nextDisplayOrder = $sameLevelObjects[$i]->display_order;
            }
        }
        if (isset($nextDisplayOrder)) {
            $record->display_order = ($nextDisplayOrder + $targetRecord->display_order) / 2;
        } elseif ($submitData['position'] < 0) {
            $record->display_order = $targetRecord->display_order - 0.001;
        } else {
            $record->display_order = $targetRecord->display_order + 0.001;
        }
        if ($this->model->save($record)) {
            $this->Flash->success(__('The data has been saved.'), ['plugin' => 'Backend']);
        }
        $this->AsyncResponse->redirect(Router::url(['action' => 'index'], true));
        return $this->sendAsyncResponse();
    }

    protected function _getObjectDisplayField(Entity $record) {
        if (!empty($record) && empty($record->display_field) && !empty($this->multiLangFields)) {
            $langKeys = array_keys($this->multiLangFields);
            $firstField = array_shift($langKeys);
            return $this->_parseMultiLangField($record->id, Configure::read('DefaultLanguage'), $firstField);
        }
        return false;
    }

    protected function _getSameLevelObject(Entity $record) {
        if (empty($this->hasOrder) || empty($record)) {
            return [];
        }
        $conditions = [];
        foreach ($this->hasOrder['filter'] as $field) {
            if (isset($record->$field)) {
                $conditions[$field] = $record->$field;
            }
        }
        $sameLevelObjects = $this->model->find('all', [
                    'conditions' => $conditions,
                    'order' => [
                        'display_order' => 'asc',
                    ],
                ])->toArray();

        foreach ($sameLevelObjects as $sameRecord) {
            if (empty($sameRecord->display_field) && !empty($this->multiLangFields)) {
                $sameRecord->set('display_field', $this->_getObjectDisplayField($sameRecord));
            }
        }
        return $sameLevelObjects;
    }

    public function seo($id = 0) {
        if (($id === 0 && empty($this->hasListSeo)) || $id > 0 && empty($this->hasSeo)) {
            $this->Flash->error(__('You do not have permission for this feature.'), ['plugin' => 'Backend']);
            return $this->redirect(['action' => 'index']);
        }
        if ($id > 0) {
            $record = $this->_getRecord($id);
            if (empty($record)) {
                $this->Flash->error(__('Data cannot found.'), ['plugin' => 'Backend']);
                return $this->redirect(['action' => 'index']);
            }
        }
        Utils::useTables($this, ['Backend.Seos']);
        $errorList = [];
        $seoList = $this->Seos->find('all', [
                    'conditions' => [
                        'target_id' => $id,
                        'target_type' => $this->modelName,
                    ],
                ])->toArray();
        $seoObjects = [];
        foreach ($seoList as $seo) {
            $seoObjects[$seo->language] = $seo;
        }

        if ($this->request->is('post') || $this->request->is('put')) {
            $submitData = $this->request->getData();
            Utils::useComponents($this, ['Backend.Upload']);
            $destinationFolder = Configure::read('Upload.PhotoFolder');
            $this->Upload->setDestination($destinationFolder);
            $photoInfo = [
                'width' => 600,
                'height' => 315,
            ];
            foreach ($this->languageList as $languageCode => $languageLabel) {
                $photoError = [];
                if (!empty($_FILES['seo_data_' . $languageCode . '_thumbnail_photo']['name'])) {
                    $ret = $this->Upload->handleUpload('seo_data_' . $languageCode . '_thumbnail_photo', 'Thumbnail');
                    if (!empty($ret['error'])) {
                        $photoError[] = $ret['error'];
                    } else {
                        $photoPath = $destinationFolder . $ret['file'];
                        $photoSize = @getimagesize($photoPath);
                        $width = !empty($photoSize[0]) ? $photoSize[0] : 0;
                        $height = !empty($photoSize[1]) ? $photoSize[1] : 0;
                        if (!empty($photoInfo['width']) && $width < $photoInfo['width']) {
                            $photoError[] = __('Minimum width is ') . $photoInfo['width'] . ' px';
                        }
                        if (!empty($photoInfo['height']) && $height < $photoInfo['height']) {
                            $photoError[] = __('Minimum height is ') . $photoInfo['height'] . ' px';
                        }
                    }
                }
                if (!empty($photoError)) {
                    $errorList['seo_data'][$languageCode]['thumbnail_photo'] = $photoError;
                } elseif (!empty($photoPath)) {
                    $submitData['seo_data'][$languageCode]['thumbnail'] = str_replace(WWW_ROOT, '', $photoPath);
                }
                unset($submitData['seo_data'][$languageCode]['thumbnail_photo']);
                if (empty($seoObjects[$languageCode])) {
                    $seoObjects[$languageCode] = $this->Seos->newEntity([
                        'target_id' => $id,
                        'target_type' => $this->modelName,
                        'language' => $languageCode,
                        'content' => json_encode($submitData['seo_data'][$languageCode]),
                    ]);
                } else {
                    $seoObjects[$languageCode]->content = json_encode($submitData['seo_data'][$languageCode]);
                }
            }
            if (empty($errorList)) {
                foreach ($seoObjects as $seo) {
                    $this->Seos->save($seo);
                }
                $this->Flash->success(__('The data has been saved.'), ['plugin' => 'Backend']);
                return $this->redirect(['action' => 'index']);
            } else {
                $this->set('alertContent', implode('<br>', $errorList));
            }
        }
        foreach ($seoObjects as $seo) {
            $seo->content = json_decode($seo->content, true);
        }
        $this->set('seoList', $seoObjects);
        $this->set(compact('record'));
        $this->set('_serialize', ['record']);

        $this->pageTitle = __('Seo');
        $this->set('headerTitle', $this->pageTitle);
        $this->set('mainNav', $this->_mainNav($id));
        $this->render('Backend.Element/Crud/seo_view');
    }

}
