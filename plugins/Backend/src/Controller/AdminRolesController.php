<?php

namespace Backend\Controller;

use App\Utility\Utils;
use Backend\Controller\CrudController;
use Backend\Model\Entity\AdminPermission;
use Backend\Model\Entity\AdminRole;
use Cake\Event\Event;
use Cake\ORM\Entity;
use Cake\Routing\Router;

class AdminRolesController extends CrudController {

    protected $listViewCols = [
        'name' => [
            'filter' => 'AdminRoles.name',
            'label' => 'Name',
        ],
        'status' => [
            'label' => 'Status',
        ],
    ];
    protected $detailViewCols = [
        'id' => [
            'label' => 'ID',
        ],
        'name' => [
            'label' => 'Name',
        ],
        'status' => [
            'label' => 'Status',
        ],
    ];
    protected $createUpdateFields = [
        'name' => [
            'input' => 'text',
            'label' => 'Role Name',
            'currentValue' => false,
        ],
    ];
    protected $defaultSorting = [
        'field' => 'AdminRoles.name',
        'order' => 'ASC',
    ];
    protected $modelName = 'AdminRoles';
    protected $modelPlugin = 'Backend';
    protected $searchingFields = [
        'AdminRoles.name',
    ];

    public function initialize() {
        parent::initialize();
        $this->activationFields = [
            'AdminRoles.status' => $this->model->getStatusList(),
        ];
    }

    public function edit($id) {
        if ($id == AdminRole::ROLE_SUPER_ADMIN_ID) {
            $this->Flash->error(__('Cannot edit Supper Admin!'), ['plugin' => 'Backend']);
            return $this->redirect(['action' => 'index']);
        }
        return parent::edit($id);
    }

    public function delete($id) {
        if ($id == AdminRole::ROLE_SUPER_ADMIN_ID) {
            $this->Flash->error(__('Cannot edit Supper Admin!'), ['plugin' => 'Backend']);
            return $this->redirect(['action' => 'index']);
        }
        return parent::delete($id);
    }

    public function submitPermission() {
        if (!$this->request->is('ajax')) {
            return $this->redirectDashboard();
        }
        $id = $this->request->getData('role_id', false);
        if ($id) {
            $record = $this->_getRecord($id);
        }
        if (empty($record)) {
            $this->Flash->error(__('Data cannot found.'), ['plugin' => 'Backend']);
            $this->AsyncResponse->redirect(Router::url(['action' => 'index'], true));
            return $this->sendAsyncResponse();
        }
        $permissionList = $this->request->getData('permission', []);
        $this->AdminPermissions->deleteAll([
            'AdminPermissions.admin_role_id' => $id,
        ]);
        if (!empty($permissionList)) {
            $permissionInfo = $this->AdminPermissions->newEntity([
                'admin_role_id' => $id,
                'content' => json_encode($permissionList),
            ]);
            $this->AdminPermissions->save($permissionInfo);
        }
        $this->Flash->success(__('The data has been saved.'), ['plugin' => 'Backend']);
        $this->AsyncResponse->redirect(Router::url(['action' => 'index'], true));
        return $this->sendAsyncResponse();
    }

    public function permission($id) {
        if ($id == AdminRole::ROLE_SUPER_ADMIN_ID) {
            $this->Flash->error(__('Cannot change permission for Supper Admin.'), ['plugin' => 'Backend']);
            return $this->redirect(['action' => 'index']);
        }
        $adminRole = $this->_getRecord($id);
        if (empty($adminRole)) {
            $this->Flash->error(__('Data cannot found.'), ['plugin' => 'Backend']);
            return $this->redirect(['action' => 'index']);
        }
        $permissionInfo = $this->AdminPermissions->find('all', [
                    'conditions' => [
                        'AdminPermissions.admin_role_id' => $id,
                    ]
                ])->first();
        $permissionList = [];
        if (!empty($permissionInfo)) {
            $permissionList = json_decode($permissionInfo->content, true);
        }
        Utils::useComponents($this, ['Backend.AdminCommon']);
        $controllerList = $this->AdminCommon->getAllControllers();
        $this->set(compact('controllerList', 'permissionList', 'adminRole'));
        $this->set('_serialize', ['controllerList', 'permissionList', 'adminRole']);
    }

    protected function _mainNav($id = false) {
        $navList = parent::_mainNav($id);
        if ($id == AdminRole::ROLE_SUPER_ADMIN_ID) {
            unset($navList['edit']);
            unset($navList['delete']);
        } elseif ($id) {
            $navList['permission'] = [
                'url' => Router::url(['action' => 'permission', $id], true),
                'label' => 'Set Permission',
                'icon' => 'wrench',
                'button' => 'secondary',
            ];
        }
        return $navList;
    }

    protected function _setActions($record) {
        $actions = parent::_setActions($record);
        if ($record->id == AdminRole::ROLE_SUPER_ADMIN_ID) {
            unset($actions['edit']);
            unset($actions['delete']);
        } elseif ($record->id) {
            $actions['permission'] = [
                'url' => Router::url(['action' => 'permission', $record->id], true),
                'label' => 'Set Permission',
                'icon' => 'wrench',
                'textColor' => 'yellow',
            ];
        }
        return $actions;
    }

}
