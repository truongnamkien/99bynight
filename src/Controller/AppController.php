<?php

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */

namespace App\Controller;

use App\Utility\Utils;
use Backend\Controller\CoreController;
use Cake\Controller\Controller;
use Cake\Event\Event;

class AppController extends CoreController {

    const ALERT_KEY = 'VNCPH:Alert';

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

        $alertContent = $this->Session->read(self::ALERT_KEY);
        if ($alertContent) {
            $this->Session->delete(self::ALERT_KEY);
            $this->set('alertContent', $alertContent);
        }

        /*
         * Enable the following component for recommended CakePHP security settings.
         * see https://book.cakephp.org/3/en/controllers/components/security.html
         */
        //$this->loadComponent('Security');
    }

    public function beforeFilter(Event $event) {
        $this->Auth->allow();
    }

    protected function redirectHome() {
        $view = new \Cake\View\View();
        $view->helpers(['Link']);
        $link = $view->Link->homeUrl();
        return $this->redirect($link);
    }

}
