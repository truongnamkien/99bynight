<?php

namespace Backend\Controller\Component;

use App\Utility\Utils;
use Backend\Model\Entity\AdminPermission;
use Backend\Model\Entity\AdminRole;
use Cake\Controller\Component;
use Cake\Core\Configure;

class AdminCommonComponent extends Component {

    protected $permissionList = false;

    public function getAllIPWhiteList() {
        Utils::useTables($this, ['Backend.AdminWhitelistIps']);

        $result = $this->AdminWhitelistIps->find('all', [
            'fields' => [
                'AdminWhitelistIps.ip'
            ]
        ]);

        if (!empty($result)) {
            return $result->toArray();
        }
        return [];
    }

    public function addIPWhiteList($ip) {
        if (empty($ip)) {
            return false;
        }
        Utils::useTables($this, ['Backend.AdminWhitelistIps']);
        $entity = $this->AdminWhitelistIps->newEntity([
            'ip' => $ip
        ]);
        return $this->AdminWhitelistIps->save($entity);
    }

    public function checkSuperPermission($adminInfo) {
        if (!empty($adminInfo) && ($adminInfo->id == 1 || $adminInfo->admin_role_id == AdminRole::ROLE_SUPER_ADMIN_ID)) {
            return true;
        }
        return false;
    }

    public function checkPermission($adminInfo, $controller) {
        if ($this->checkSuperPermission($adminInfo)) {
            return true;
        }
        if (empty($controller) || empty($adminInfo)) {
            return false;
        }
        $exceptList = Configure::read('permissionExcept');
        if (!empty($exceptList[$controller])) {
            return true;
        }
        if ($this->permissionList === false) {
            Utils::useTables($this, ['Backend.AdminPermissions']);
            $this->permissionList = [];
            $permissionInfo = $this->AdminPermissions->find('all', [
                        'conditions' => [
                            'AdminPermissions.admin_role_id' => $adminInfo->admin_role_id,
                        ]
                    ])->first();
            if (!empty($permissionInfo->content)) {
                $this->permissionList = json_decode($permissionInfo->content, true);
            }
        }
        return !empty($this->permissionList[$controller]);
    }

    public function getAllControllers() {
        $moduleList = Configure::read('moduleList');
        $controllerList = [];
        foreach ($moduleList as $index => $section) {
            $controllerList = array_merge($controllerList, array_keys($section['subModules']));
        }
        return $controllerList;
    }

}
