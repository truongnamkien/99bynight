<?php

namespace Backend\Controller\Component;

use App\Utility\Utils;
use App\Model\Entity\Config;
use Cake\Controller\Component;
use Cake\Mailer\Email;
use Cake\Log\Log;

class BackendEmailComponent extends Component {

    public function initialize(array $config) {
        parent::initialize($config);
    }

    protected function _beforeSendEmail() {
        Utils::useTables($this, ['Configs']);
        $configList = $this->Configs->find('all', [
                    'conditions' => [
                        'Configs.field IN' => [
                            Config::CONFIG_KEY_SYSTEM_EMAIL,
                            Config::CONFIG_KEY_SYSTEM_EMAIL_PASSWORD,
                            Config::CONFIG_KEY_EMAIL,
                        ]
                    ],
                ])->toArray();
        $emailConfig = Email::getConfigTransport('gmail');
        $viewVars = [];
        foreach ($configList as $config) {
            switch ($config->field) {
                case Config::CONFIG_KEY_SYSTEM_EMAIL;
                    $emailConfig['username'] = $config->content;
                    break;
                case Config::CONFIG_KEY_SYSTEM_EMAIL_PASSWORD;
                    $emailConfig['password'] = $config->content;
                    break;
                case Config::CONFIG_KEY_EMAIL;
                    $viewVars['siteEmail'] = $config->content;
                    break;
            }
        }
        if (empty($emailConfig['username']) || empty($emailConfig['password'])) {
            return false;
        }
        Email::dropTransport('gmail');
        Email::setConfigTransport('gmail', $emailConfig);
        $this->Email = new Email('gmail');
        $this->Email->viewVars($viewVars);
        $this->Email->setFrom([(!empty($viewVars['siteEmail']) ? $viewVars['siteEmail'] : $viewVars['username']) => PAGE_TITLE]);
        $this->Email->setEmailFormat('html');
        return true;
    }

    public function reset() {
        $this->Email->reset();
    }

    private function send() {
        try {
            $ret = $this->Email->send();
            $this->reset();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function sendEmail($data) {
        // Predefine configured values
        $ret = $this->_beforeSendEmail();
        if (!$ret) {
            return false;
        }
        if (!empty($data['template'])) {
            $this->Email->viewBuilder()->setTemplate($data['template'], 'default');
        } else if (!empty($data['layout'])) {
            $this->Email->viewBuilder()->setTemplate($data['layout'], 'default');
        } else {
            $this->Email->viewBuilder()->setTemplate('default', 'default');
        }
        $this->Email->viewVars(['params' => $data]);
        $this->Email->addTo($data['to']);
        if (!empty($data['replyTo'])) {
            $this->Email->setReplyTo($data['replyTo']);
        }
        $this->Email->setSubject($data['subject']);
        if (!empty($data['cc'])) {
            $this->Email->addCc($data['cc']);
        }

        if ($this->Email->to()) {
            return $this->send();
        }
        return false;
    }

    public function sendEmailResetPassword(array $emailData = []) {
        $data = [];
        $data['template'] = 'reset_password';
        $data['layout'] = 'default';
        $data['resetLink'] = $emailData['resetLink'];
        $data['sendAs'] = 'html';
        $data['subject'] = PAGE_TITLE . ' - ' . __('Reset Password');
        $data['to'] = $emailData['toEmail'];
        return $this->sendEmail($data);
    }

}
