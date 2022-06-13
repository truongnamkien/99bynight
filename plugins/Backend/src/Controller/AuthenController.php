<?php

namespace Backend\Controller;

use App\Utility\Utils;
use Backend\Model\Entity\AdminRole;
use Backend\Model\Entity\AdminUser;
use Backend\Model\Entity\AdminVerification;
use Cake\Event\Event;
use Cake\ORM\Entity;
use Cake\Routing\Router;

class AuthenController extends FsBackendController {

    public function initialize() {
        parent::initialize();
        Utils::useTables($this, [
            'Backend.AdminRoles',
            'Backend.AdminUsers',
        ]);
    }

    public function beforeFilter(Event $event) {
        parent::beforeFilter($event);
        $this->Auth->allow([
            'login',
            'forgot',
            'submitForgot',
            'reset',
            'submitReset',
        ]);
        $this->viewBuilder()->setLayout('unauthorized');
        $adminId = $this->Auth->user('id');
        if (!empty($adminId) && $this->currentAction != 'logout') {
            return $this->redirect($this->Auth->getConfig('loginRedirect'));
        }
    }

    public function login() {
        if ($this->request->is('post')) {
            $countRole = $this->AdminRoles->find('all')->count();
            if ($countRole == 0) {
                $roleInfo = $this->AdminRoles->newEntity([
                    'id' => AdminRole::ROLE_SUPER_ADMIN_ID,
                    'name' => AdminRole::ROLE_SUPER_ADMIN_NAME,
                    'status' => ACTIVE,
                ]);
                $this->AdminRoles->save($roleInfo);
            }
            $countUser = $this->AdminUsers->find('all')->count();
            $email = $this->request->getData('email', '');
            if ($countUser == 0) {
                $adminInfo = $this->AdminUsers->newEntity([
                    'email' => $email,
                    'admin_role_id' => AdminRole::ROLE_SUPER_ADMIN_ID,
                    'status' => ACTIVE,
                ]);
                $adminInfo->password = $this->request->getData('password', '');
                $adminInfo->password_confirm = $this->request->getData('password', '');
                $this->AdminUsers->save($adminInfo, ['checkRules' => false]);
            }
            $errorMsg = [];
            $authUser = $this->Auth->identify();
            if ($authUser) {
                $adminInfo = $this->AdminUsers->findById($authUser['id'])->first();
                if ($adminInfo->status == INACTIVE) {
                    $errorMsg[] = __('You account is not allowed to login.');
                } else {
                    $adminInfo->count_login = 0;
                    $adminInfo->last_login = gmdate('Y-m-d H:i:s');
                    $this->AdminUsers->save($adminInfo);
                    $this->Session->delete('Flash.flash');
                    $this->Auth->setUser($adminInfo);
                    return $this->redirect($this->Auth->getConfig('loginRedirect'));
                }
            } else {
                $errorMsg[] = __('Invalid email or password!');
                $adminInfo = $this->AdminUsers->find('all', [
                            'conditions' => [
                                'AdminUsers.email' => $email,
                            ],
                        ])->first();
                if (!empty($adminInfo)) {
                    $adminInfo->count_login++;
                    if ($adminInfo->count_login >= AdminUser::ATTEMPT_LOGIN_TIME) {
                        $errorMsg[] = sprintf(__('Your account has been locked due to %s failed attemps.'), $adminInfo->count_login);
                        $adminInfo->status = AdminUser::USER_STATUS_INACTIVE;
                    } else {
                        $errorMsg[] = sprintf(__('Your account will be locked after %s more tries!'), (AdminUser::ATTEMPT_LOGIN_TIME - $adminInfo->count_login));
                    }
                    $this->AdminUsers->save($adminInfo);
                }
            }
            $this->set('errorMsg', $errorMsg);
        }
        $this->pageTitle = __('Sign in');
    }

    public function forgot() {
        $this->pageTitle = __('Forgot password?');
    }

    public function submitForgot() {
        if (!$this->request->is('ajax')) {
            return $this->redirectDashboard();
        }
        $email = $this->request->getData('email', false);
        if (empty($email)) {
            $this->AsyncResponse->showAlert(sprintf(__('Please input %s!'), __('Email')));
            return $this->sendAsyncResponse();
        }
        $adminInfo = $this->AdminUsers->find('all', [
                    'conditions' => [
                        'AdminUsers.status' => ACTIVE,
                        'AdminUsers.email' => $email,
                    ],
                ])->first();
        if (empty($adminInfo)) {
            $this->AsyncResponse->showAlert(__('Account not found!'));
            return $this->sendAsyncResponse();
        }
        Utils::useTables($this, ['Backend.AdminVerifications']);
        $verifyInfo = $this->AdminVerifications->generateResetCode($adminInfo->id);
        $emailData = [
            'toEmail' => $adminInfo->email,
            'resetLink' => Router::url(['controller' => 'Authen', 'action' => 'reset', $verifyInfo->code], true),
        ];
        Utils::useComponents($this, ['Backend.BackendEmail']);
        $this->BackendEmail->sendEmailResetPassword($emailData);
        $this->AsyncResponse->run("$('#forgot-form #email').val('');");
        $this->AsyncResponse->showAlert(__('Please check your email for next step!'));
        return $this->sendAsyncResponse();
    }

    public function reset($code = false) {
        if (empty($code)) {
            $this->Session->write(self::ALERT_KEY, __('The link is incorrect or expired.'));
            return $this->redirect('backend/login');
        }
        Utils::useTables($this, ['Backend.AdminVerifications']);
        $expiredTime = date('Y-m-d H:i:s', time() - AdminVerification::EXPIRED_TIME);
        $verifyInfo = $this->AdminVerifications->find('all', [
                    'contain' => [
                        'AdminUsers',
                    ],
                    'conditions' => [
                        'AdminUsers.status' => ACTIVE,
                        'AdminVerifications.code' => $code,
                        'AdminVerifications.created > ' => $expiredTime,
                    ],
                ])->first();
        if (empty($verifyInfo)) {
            $this->Session->write(self::ALERT_KEY, __('The link is incorrect or expired.'));
            return $this->redirect('backend/login');
        }
        $this->set('verifyInfo', $verifyInfo);
        $this->pageTitle = __('Reset Password');
    }

    public function submitReset() {
        if (!$this->request->is('ajax')) {
            return $this->redirectDashboard();
        }
        $code = $this->request->getData('code', false);
        Utils::useTables($this, ['Backend.AdminVerifications']);
        $expiredTime = date('Y-m-d H:i:s', time() - AdminVerification::EXPIRED_TIME);
        $verifyInfo = $this->AdminVerifications->find('all', [
                    'contain' => [
                        'AdminUsers',
                    ],
                    'conditions' => [
                        'AdminUsers.status' => ACTIVE,
                        'AdminVerifications.code' => $code,
                        'AdminVerifications.created > ' => $expiredTime,
                    ],
                ])->first();
        if (empty($verifyInfo)) {
            $this->AsyncResponse->showAlert(__('The link is incorrect or expired.'));
            return $this->sendAsyncResponse();
        }
        $password = $this->request->getData('password', false);
        $confirmPassword = $this->request->getData('password_confirm', false);
        if (empty($password)) {
            $this->AsyncResponse->showAlert(sprintf(__('Please input %s!'), __('Password')));
            return $this->sendAsyncResponse();
        }
        if (empty($confirmPassword)) {
            $this->AsyncResponse->showAlert(sprintf(__('Please input %s!'), __('Confirm Password')));
            return $this->sendAsyncResponse();
        }
        if ($password != $confirmPassword) {
            $this->AsyncResponse->showAlert(__('The Confirm Password does not match with the Password.'));
            return $this->sendAsyncResponse();
        }
        $adminInfo = $verifyInfo->admin_user;
        $adminInfo->password = $password;
        $adminInfo->password_confirm = $confirmPassword;
        if (!$this->AdminUsers->save($adminInfo)) {
            $errors = $adminInfo->getErrors();
            $errorList = [];
            foreach ($errors as $field => $fieldErrors) {
                $errorList = array_merge($errorList, $fieldErrors);
            }
            $this->AsyncResponse->showAlert($errorList);
            return $this->sendAsyncResponse();
        }

        $this->AdminVerifications->deleteAll([
            'AdminVerifications.admin_id' => $adminInfo->id,
        ]);
        $this->Auth->setUser($adminInfo);
        return $this->redirectDashboard();
    }

    public function logout() {
        return $this->redirect($this->Auth->logout());
    }

}
