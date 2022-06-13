<?php

namespace Backend\Controller;

use App\Utility\Utils;
use Cake\Controller\Controller;
use Cake\Controller\Component\AuthComponent;
use Cake\Core\Configure;
use Cake\Core\Exception\Exception;
use Cake\Event\Event;
use Cake\Network\Session;
use Cake\Routing\Router;
use Cake\Utility\Inflector;

class FsBackendController extends CoreController {

    protected $model = null;
    protected $stopAjax = false;
    protected $currentController = false;
    protected $currentAction = false;
    protected $adminInfo = false;
    protected $currentLanguage = false;
    protected $currentLangCode = false;

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('Security');`
     *
     * @return void
     */
    public function initialize() {
        parent::initialize();
        Utils::useTables($this, [
            'Backend.AdminPermissions',
            'Backend.AdminUsers',
            'Backend.AdminRoles',
            'Backend.LanguageContents',
            'Backend.Photos',
            'Backend.MultiPhotos',
        ]);
        $this->configureAuth();
        $this->viewBuilder()->setLayout('Backend.authorized');
        Utils::useComponents($this, ['Backend.MultiLanguage']);
        $this->currentLanguage = $this->MultiLanguage->getCurrentLanguage();
        $this->currentLangCode = $this->MultiLanguage->getCurrentLanguageCode();
        $this->set('currentLangCode', $this->currentLangCode);
        $this->currentController = $this->request->getParam('controller', false);
        $this->currentAction = $this->request->getParam('action', false);
        $this->set('currentController', $this->currentController);
        $this->set('currentAction', $this->currentAction);
    }

    public function beforeFilter(Event $event) {
        $ret = $this->loadAuthUser();
        if ($ret !== null) {
            return $ret;
        }
        $this->initSidebar();
        $this->initBreadcrumb();
    }

    protected function loadAuthUser() {
        $adminId = $this->Auth->user('id');
        if (!empty($adminId)) {
            $this->adminInfo = $this->AdminUsers->find('all', [
                        'contain' => [
                            'AdminRoles',
                        ],
                        'conditions' => [
                            'AdminUsers.status' => ACTIVE,
                            'AdminUsers.id' => $adminId,
                        ],
                    ])->first();
            if (empty($this->adminInfo)) {
                return $this->redirect('backend/logout');
            }
            Utils::useComponents($this, ['Backend.AdminCommon']);
            $hasPermission = $this->AdminCommon->checkPermission($this->adminInfo, $this->currentController);
            if (!$hasPermission) {
                return $this->showInvalidAction();
            }
        }
        $this->set('authUser', $this->adminInfo);
        return null;
    }

    protected function checkIPInWhiteList() {
        Utils::useComponents($this, ['Backend.AdminCommon']);
        $ipList = $this->AdminCommon->getAllIPWhiteList();
        if (empty($ipList)) {
            $ip = Utils::getUserIP();
            $this->AdminCommon->addIPWhiteList($ip);
        } elseif (!Utils::checkIPInWhiteList($ipList)) {
            throw new Exception('Page not found', 404);
        }
    }

    /**
     * configure authentication
     *
     * @return void
     */
    protected function configureAuth() {
        $this->Auth->setConfig('loginRedirect', [
            'controller' => 'Profile',
            'action' => 'index'
        ]);
        $this->Auth->setConfig('loginAction', [
            'controller' => 'Authen',
            'action' => 'login'
        ]);
        $this->Auth->setConfig('logoutRedirect', [
            'controller' => 'Authen',
            'action' => 'login'
        ]);
        $this->Auth->setConfig('storage', 'Backend.BackendSession');
        $this->Auth->setConfig('authenticate', [
            AuthComponent::ALL => [
                'fields' => [
                    'username' => 'email',
                    'password' => 'password'
                ],
                'userModel' => 'Backend.AdminUsers'
            ],
            'Form' => [
                'passwordHasher' => [
                    'className' => 'Default'
                ]
            ]
        ]);
    }

    protected function showInvalidAction() {
        $error = __('You do not have permission for this feature.');
        if ($this->request->is('ajax')) {
            $this->AsyncResponse->showAlert($error);
            return $this->sendAsyncResponse();
        } else {
            $this->Session->write(self::ALERT_KEY, $error);
            return $this->redirectDashboard();
        }
    }

    protected function redirectDashboard() {
        if ($this->request->is('ajax')) {
            $this->AsyncResponse->redirect(Router::url(['controller' => 'Profile'], true));
            return $this->sendAsyncResponse();
        }
        return $this->redirect(Router::url(['controller' => 'Profile'], true));
    }

    protected function initBreadcrumb() {
        $title = __(Inflector::humanize(Inflector::underscore($this->currentController)));
        $breadcrumb = [
            [
                'title' => __('Admin Panel'),
                'href' => '#',
                'class' => 'fake',
            ],
            [
                'title' => $title,
                'href' => Router::url(['controller' => $this->currentController, 'action' => 'index'], true),
                'class' => '',
            ],
        ];
        if ($this->currentAction != 'index') {
            $actionTitle = __(Inflector::humanize($this->currentAction));
            $breadcrumb[] = [
                'title' => $actionTitle,
                'href' => $this->request->getRequestTarget(),
                'class' => '',
            ];
            $title = __($actionTitle) . ' ' . __($title);
        }
        $this->set('breadcrumb', $breadcrumb);
        $this->set('headerTitle', $title);
    }

    protected function initSidebar() {
        Utils::useComponents($this, ['Backend.AdminCommon']);
        $moduleList = Configure::read('moduleList');
        foreach ($moduleList as $index => &$section) {
            foreach ($section['subModules'] as $index => $subSection) {
                $hasPermission = $this->AdminCommon->checkPermission($this->adminInfo, $subSection['controller']);
                if (!$hasPermission) {
                    unset($section['subModules'][$index]);
                }
            }
            if (empty($section['subModules'])) {
                unset($moduleList[$index]);
            }
        }
        $this->set('sideBarList', $moduleList);
    }

}
