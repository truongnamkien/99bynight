<?php

namespace Backend\Controller;

use App\Utility\Utils;
use Cake\Event\Event;
use Cake\I18n\Date;

class ProfileController extends FsBackendController {

    public function index() {
    }

    public function updateProfile() {
        if (!$this->request->is('ajax')) {
            return $this->redirectDashboard();
        }
        $currentPassword = $this->request->getData('current_password', false);
        if (!$this->adminInfo->verifyPassword($currentPassword)) {
            $this->AsyncResponse->showAlert(__('Your Current Password is incorrect!'));
            return $this->sendAsyncResponse();
        }
        $this->adminInfo->password = $this->request->getData('password', false);
        $this->adminInfo->password_confirm = $this->request->getData('password_confirm', false);
        if (!$this->AdminUsers->save($this->adminInfo)) {
            $errors = $this->adminInfo->getErrors();
            $errorList = [];
            foreach ($errors as $field => $fieldErrors) {
                $errorList = array_merge($errorList, $fieldErrors);
            }
            $this->AsyncResponse->showAlert($errorList);
            return $this->sendAsyncResponse();
        }
        $this->AsyncResponse->run("$('#profile-form .non-disabled').val('');");
        $this->AsyncResponse->showAlert(__('Your Profile has been updated!'));
        return $this->sendAsyncResponse();
    }

}
