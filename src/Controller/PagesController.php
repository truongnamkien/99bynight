<?php

/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link      http://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */

namespace App\Controller;

use App\Model\Entity\Banner;
use App\Model\Entity\Config;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Network\Exception\ForbiddenException;
use Cake\Network\Exception\NotFoundException;
use Cake\Validation\Validation;
use Cake\View\Exception\MissingTemplateException;
use App\Utility\Utils;

/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/
 *
 * @link http://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class PagesController extends AppController {

    public function beforeFilter(Event $event) {
        $this->Auth->allow();
    }

    /**
     * Displays a view
     *
     * @param string ...$path Path segments.
     * @return void|\Cake\Network\Response
     * @throws \Cake\Network\Exception\ForbiddenException When a directory traversal attempt.
     * @throws \Cake\Network\Exception\NotFoundException When the view file could not
     *   be found or \Cake\View\Exception\MissingTemplateException in debug mode.
     */
    public function display(...$path) {
        $count = count($path);
        if (!$count) {
            return $this->redirectHome();
        }
        if (in_array('..', $path, true) || in_array('.', $path, true)) {
            throw new ForbiddenException();
        }
        $page = null;
        if (!empty($path[0])) {
            $page = $path[0];
        }
        if (isset($path[1])) {
            $this->_setLanguage($path[1]);
        } else {
            $this->_setLanguage(Configure::read('DefaultLanguage'));
        }

        if ($page == 'home') {
            $this->home();
        } elseif ($page == 'contact') {
            $this->contact();
        }
        $this->set(compact('page'));

        try {
            $this->render($page);
        } catch (MissingTemplateException $e) {
            if (Configure::read('debug')) {
                throw $e;
            }
            throw new NotFoundException();
        }
    }

    protected function contact($languageCode = LANGUAGE_VIETNAMESE) {
        $this->_setLanguage($languageCode);
        $this->pageTitle = __('Contact');
        $this->set('bodyClass', 'contact page');
        $this->set('currentPage', 'contact');
        $view = new \Cake\View\View();
        $view->helpers(['Link']);
        $breadcrumb = [
            [
                'url' => $view->Link->contactUrl(),
                'title' => __('Contact'),
            ]
        ];
        $this->set('breadcrumb', $breadcrumb);

        Utils::useComponents($this, ['Backend.MultiLanguage']);
        $currentLanguageCode = $this->MultiLanguage->getCurrentLanguageCode();
        Utils::useTables($this, ['Configs']);
        $configList = $this->Configs->find('all')->toArray();
        foreach ($configList as $config) {
            switch ($config->field) {
                case Config::CONFIG_KEY_EMAIL;
                    $this->set('siteEmail', $config->content);
                    break;
                case Config::CONFIG_KEY_DESCRIPTION_VIETNAMESE;
                    if ($currentLanguageCode === LANGUAGE_VIETNAMESE) {
                        $this->set('siteDescription', $config->content);
                    }
                    break;
                case Config::CONFIG_KEY_ADDRESS_VIETNAMESE;
                    if ($currentLanguageCode === LANGUAGE_VIETNAMESE) {
                        $this->set('siteAddress', $config->content);
                    }
                    break;
                case Config::CONFIG_KEY_PHONE;
                    $this->set('sitePhone', $config->content);
                    break;
                case Config::CONFIG_KEY_LOCATION;
                    $this->set('siteLocation', json_decode($config->content, true));
                    break;
                case Config::CONFIG_KEY_SOCIAL_FACEBOOK;
                    $this->set('siteFacebook', $config->content);
                    break;
                case Config::CONFIG_KEY_WORKING_MON_FRI;
                    $this->set('workingMonFri', $config->content);
                    break;
                case Config::CONFIG_KEY_WORKING_SAT;
                    $this->set('workingSat', $config->content);
                    break;
                case Config::CONFIG_KEY_WORKING_SUN;
                    $this->set('workingSun', $config->content);
                    break;
            }
        }
    }

    public function subscribeSubmit() {
        if (!$this->request->is('ajax')) {
            return $this->redirectHome();
        }
        Utils::useTables($this, ['Subscribes']);
        Utils::useComponents($this, ['FsCore.AsyncResponse']);
        $subscribe = $this->Subscribes->subscribe(!empty($this->request->data['email']) ? trim($this->request->data['email']) : false);
        $errors = $subscribe->errors();
        if (empty($errors)) {
            $this->AsyncResponse->run("jQuery('#newsletter input').val('');");
            $this->AsyncResponse->run('showAlert("' . __('Thanks for subscribing!') . '");');
        } else {
            $errorList = [];
            foreach ($errors as $field => $fieldErrors) {
                $errorList[$field] = implode('<br />', $fieldErrors);
            }
            $this->AsyncResponse->run('showAlert("' . array_shift($errorList) . '");');
        }
        $this->sendAsyncResponse();
    }

    public function contactSubmit() {
        if (!$this->request->is('ajax')) {
            return $this->redirect('/');
        }
        Utils::useTables($this, ['Contacts']);
        Utils::useComponents($this, ['Backend.AsyncResponse', 'Backend.Email']);
        $contact = $this->Contacts->newEntity([
            'email' => !empty($this->request->data['email']) ? trim($this->request->data['email']) : false,
            'phone' => !empty($this->request->data['phone']) ? trim($this->request->data['phone']) : false,
            'message' => !empty($this->request->data['content']) ? trim($this->request->data['content']) : false,
            'fullname' => !empty($this->request->data['fullname']) ? trim($this->request->data['fullname']) : false,
            'ip_address' => Utils::getUserIP(),
        ]);
        $this->AsyncResponse->run("jQuery('#contactForm .successform').hide();");
        $this->AsyncResponse->run("jQuery('#contactForm .errorform').hide();");
        if ($this->Contacts->save($contact)) {
            $this->Email->sendEmailContactAdmin($contact);
            $this->AsyncResponse->run("jQuery('#contactForm input').val('');");
            $this->AsyncResponse->run("jQuery('#contactForm textarea').val('');");
            $this->AsyncResponse->run("jQuery('#contactForm .successform').show();");
        } else {
            $errors = $contact->errors();
            foreach ($errors as $field => $fieldErrors) {
                $this->AsyncResponse->run("jQuery('#contactForm .errorform').show();");
                $this->AsyncResponse->html('#contactForm .errorform p', implode('<br />', $fieldErrors));
                break;
            }
        }
        $this->sendAsyncResponse();
    }

    protected function home($languageCode = LANGUAGE_VIETNAMESE) {
        $this->_setLanguage($languageCode);
        $this->pageTitle = __('Home');
        $this->set('bodyClass', 'home page');
        $this->set('currentPage', 'home');
    }

    public function detail($slug, $languageCode = LANGUAGE_VIETNAMESE) {
        $this->_setLanguage($languageCode);
        Utils::useComponents($this, ['Backend.Slug']);
        $pageId = $this->Slug->getTargetId($slug, 'Pages');
        if (empty($pageId)) {
            return $this->redirectHome();
        }
        Utils::useTables($this, ['Pages']);
        Utils::useComponents($this, ['Backend.MultiLanguage']);
        $currentLanguage = $this->MultiLanguage->getCurrentLanguage();
        $page = $this->Pages->find('all', [
                    'conditions' => [
                        'Pages.id' => $pageId,
                        'Pages.status' => ACTIVE,
                    ],
                    'contain' => [
                        'Title' . ucfirst($currentLanguage),
                        'Content' . ucfirst($currentLanguage),
                    ],
                ])->first();
        if (empty($page)) {
            return $this->redirectHome();
        }
        $this->pageTitle = $page->getTitle();
        $this->pageDesc = Utils::shortDescription($page->getContent(), 50);
        $this->seoId = $page->id;
        $this->seoTarget = 'Pages';

        $view = new \Cake\View\View();
        $view->helpers(['Link']);
        $breadcrumb = [];
        $currentPage = 'pages';
        $currentPage .= '-pageDetail-' . $page->id . '-';
        $this->set('currentPage', $currentPage);
        $this->set('breadcrumb', $breadcrumb);
        $this->set('page', $page);
    }

}
