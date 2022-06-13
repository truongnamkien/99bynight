<?php

namespace Backend\Controller;

use Backend\Controller\CrudController;
use Backend\Model\Entity\AdminRole;
use Backend\Model\Entity\AdminUser;
use Cake\Event\Event;
use Cake\ORM\Entity;
use Cake\Routing\Router;
use App\Utility\Utils;

class AdminUsersController extends CrudController {

    protected $listViewCols = [
        'email' => [
            'filter' => 'AdminUsers.email',
            'label' => 'Email',
        ],
        'admin_role.name' => [
            'filter' => 'AdminRoles.name',
            'label' => 'Admin Role',
        ],
        'status' => [
            'label' => 'Status',
        ],
    ];
    protected $detailViewCols = [
        'id' => [
            'label' => 'ID',
        ],
        'email' => [
            'label' => 'Email',
        ],
        'admin_role.name' => [
            'label' => 'Admin Role',
        ],
        'status' => [
            'label' => 'Status',
        ],
    ];
    protected $createUpdateFields = [
        'email' => [
            'input' => 'email',
            'label' => 'Email',
            'currentValue' => false,
        ],
        'admin_role_id' => [
            'input' => 'dropdown',
            'label' => 'Role',
            'currentValue' => false,
        ],
        'password' => [
            'input' => 'password',
            'label' => 'Password',
            'currentValue' => false,
        ],
        'password_confirm' => [
            'input' => 'password',
            'label' => 'Confirm Password',
            'currentValue' => false,
        ],
    ];
    protected $defaultSorting = [
        'field' => 'AdminUsers.email',
        'order' => 'ASC',
    ];
    protected $modelName = 'AdminUsers';
    protected $modelPlugin = 'Backend';
    protected $containModel = [
        'AdminRoles',
    ];
    protected $searchingFields = [
        'AdminUsers.email',
        'AdminRoles.name',
    ];

    public function initialize() {
        parent::initialize();
        $this->activationFields = [
            'AdminUsers.status' => $this->model->getStatusList(),
        ];
        $roleList = $this->AdminRoles->find('all', [
                    'order' => [
                        'AdminRoles.id' => 'ASC',
                    ],
                ])->toArray();
        $roleDropdown = [];
        foreach ($roleList as $role) {
            $roleDropdown[$role->id] = [
                'label' => $role->name,
                'iconClass' => 'success',
            ];
        }
        $this->activationFields['AdminUsers.admin_role_id'] = $roleDropdown;
    }

    protected function _prepareObject(Entity $record) {
        $roleList = $this->AdminRoles->find('list', [
                    'keyField' => 'id',
                    'valueField' => 'name',
                    'order' => [
                        'AdminRoles.id' => 'ASC',
                    ],
                ])->toArray();
        $this->createUpdateFields['admin_role_id']['options'] = $roleList;
        return parent::_prepareObject($record);
    }

    public function edit($id) {
        if ($this->adminInfo->id == $id) {
            $this->Flash->error(__('Cannot edit yourself!'), ['plugin' => 'Backend']);
            return $this->redirect(['action' => 'index']);
        }
        if ($id == 1) {
            $this->Flash->error(__('Cannot edit yourself!'), ['plugin' => 'Backend']);
            return $this->redirect(['action' => 'index']);
        }
        return parent::edit($id);
    }

    public function delete($id = null) {
        if ($this->adminInfo->id == $id) {
            $this->Flash->error(__('Cannot edit yourself!'), ['plugin' => 'Backend']);
            return $this->redirect(['action' => 'index']);
        }
        if ($id == 1) {
            $this->Flash->error(__('Cannot edit yourself!'), ['plugin' => 'Backend']);
            return $this->redirect(['action' => 'index']);
        }
        $adminUser = $this->_getRecord($id);
        if ($adminUser->admin_role_id == AdminRole::ROLE_SUPER_ADMIN_ID && $this->adminInfo->admin_role_id != AdminRole::ROLE_SUPER_ADMIN_ID) {
            $this->Flash->error(__('Cannot edit Supper Admin'), ['plugin' => 'Backend']);
            return $this->redirect(['action' => 'index']);
        }
        return parent::delete($id);
    }

    protected function _mainNav($id = false) {
        $navList = parent::_mainNav($id);
        if ($id == 1) {
            unset($navList['edit']);
            unset($navList['delete']);
        }
        return $navList;
    }

    protected function _setActions($record) {
        $actions = parent::_setActions($record);
        if ($record->id == 1) {
            unset($actions['edit']);
            unset($actions['delete']);
        }
        return $actions;
    }

}
