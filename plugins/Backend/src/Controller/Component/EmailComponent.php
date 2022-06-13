<?php

namespace Backend\Controller\Component;

use App\Model\Entity\Config;
use App\Utility\Utils;
use Cake\Controller\Component;
use Cake\Mailer\Email;
use Cake\Log\Log;

class EmailComponent extends Component {

    protected static $emailConfig = [];

    protected function _beforeSendEmail() {
        if (!empty(self::$emailConfig)) {
            return true;
        }
        Utils::useTables($this, ['Configs']);
        $configList = $this->Configs->find('all', [
                    'conditions' => [
                        'Configs.field IN' => [
                            Config::CONFIG_KEY_SYSTEM_EMAIL,
                            Config::CONFIG_KEY_SYSTEM_EMAIL_PASSWORD,
                            Config::CONFIG_KEY_EMAIL,
                            Config::CONFIG_KEY_PHONE,
                        ]
                    ],
                ])->toArray();
        $emailConfig = Email::getConfigTransport('gmail');
        $viewVars = [
            'siteEmail' => false,
            'companyName' => PAGE_TITLE,
        ];
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
                case Config::CONFIG_KEY_PHONE;
                    $viewVars['sitePhone'] = $config->content;
                    break;
            }
        }
        if (empty($emailConfig['username'])) {
            return false;
        }
        if (empty($viewVars['siteEmail'])) {
            $viewVars['siteEmail'] = $emailConfig['username'];
        }

        Email::dropTransport('gmail');
        Email::setConfigTransport('gmail', $emailConfig);
        self::$emailConfig = array_merge(self::$emailConfig, $emailConfig);
        self::$emailConfig = array_merge(self::$emailConfig, $viewVars);
        $this->Email = new Email('gmail');
        $this->Email->viewVars($viewVars);
        $this->Email->from([$viewVars['siteEmail'] => $viewVars['companyName']]);
        $this->Email->emailFormat('html');
        return true;
    }

    public function reset() {
        $this->Email->reset();
    }

    private function send() {
        try {
            $ret = $this->Email->send();
            $this->logResult();
            $this->reset();

            return true;
        } catch (\Exception $e) {
            $this->logResult($e);
            return false;
        }
    }

    private function logResult($e = NULL) {
        if (is_array($this->Email->from())) {
            $from = implode(",", $this->Email->from());
        } else {
            $from = $this->Email->from();
        }
        if (is_array($this->Email->to())) {
            $to = implode(",", $this->Email->to());
        } else {
            $to = $this->Email->to();
        }
        $attachments = $this->Email->attachments();

        $message = PHP_EOL . "-------------------------" . PHP_EOL;
        $message .= "Send From : '" . $from . "' " . ($e instanceof Exception ? 'unsuccessfully' : 'successfully') . PHP_EOL;
        $message .= "Send email to : '" . $to . "' " . ($e instanceof Exception ? 'unsuccessfully' : 'successfully') . PHP_EOL;
        $message .= "Subject email : " . $this->Email->subject() . PHP_EOL;
        $message .= "Attachment list : " . PHP_EOL;
        foreach ($attachments as $name => $file) {
            $message .= $file['file'] . PHP_EOL;
        }
        if ($e instanceof Exception) {
            $message .= "The error is : " . $e->getMessage() . PHP_EOL;
        }
        $message .= "-------------------------" . PHP_EOL;

        Log::info($message);
    }

    public function sendEmail($data) {
        $this->Email->template($data['template'], (!empty($data['layout']) ? $data['layout'] : false));

        $this->Email->addTo($data['to']);
        $this->Email->subject($data['subject']);
        if (isset($data['cc']) && !empty($data['cc'])) {
            $this->Email->addCc($data['cc']);
        }
        if (isset($data['attachment']) && !empty($data['attachment'])) {
            $this->Email->addAttachments(array($data['attachment']));
        }
        $this->Email->viewVars(array('params' => $data));

        if ($this->Email->to()) {
            if (isset($data['replyTo']) && !empty($data['replyTo'])) {
                $this->Email->replyTo($data['replyTo']);
            }
            try {
                return $this->send();
            } catch (\Exception $e) {
                return false;
            }
        }
        return false;
    }

    public function sendEmailContactAdmin($contact) {
        if (empty($contact)) {
            return false;
        }
        $ret = $this->_beforeSendEmail();
        if (!$ret) {
            return false;
        }
        $data = [];
        $data['template'] = 'contact_admin';
        $data['layout'] = 'default';
        $data['contact'] = $contact;
        $data['to'] = self::$emailConfig['siteEmail'];
        $data['subject'] = PAGE_TITLE . ' - ' . __('Contact from user');
        $data['sendAs'] = 'html';

        return $this->sendEmail($data);
    }

}
