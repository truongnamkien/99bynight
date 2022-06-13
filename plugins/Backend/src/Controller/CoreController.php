<?php

namespace Backend\Controller;

use App\Utility\Utils;
use Cake\Controller\Component;
use Cake\Controller\Component\AuthComponent;
use Cake\Controller\Controller;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Routing\Router;
use Cake\Utility\Inflector;
use Cake\ORM\TableRegistry;

class CoreController extends Controller {

    const ALERT_KEY = 'CoreController:Alert';

    public static $_globalObjects = [
        'components' => [],
        'tables' => []
    ];
    public static $_instance = null;
    public $helpers = ['App.Minify'];
    public $Session = null;
    protected $seoTarget = false;
    protected $seoId = false;
    protected $seoKeyword = false;
    protected $pageTitle = false;
    protected $pageDesc = false;
    protected $pageImage = false;
    protected $ajaxResponse = [
        'status' => 0,
        'message' => ''
    ];

    public function initialize() {
        parent::initialize();

        self::$_instance = $this;
        $this->Session = $this->request->getSession();

        $this->loadComponent('RequestHandler', [
            'enableBeforeRedirect' => false,
        ]);
        Utils::useComponents($this, [
            'Auth',
            'Flash',
            'Backend.AsyncResponse',
            'Backend.MultiLanguage',
        ]);
        $alertContent = $this->Session->read(self::ALERT_KEY);
        if ($alertContent) {
            $this->Session->delete(self::ALERT_KEY);
            $this->set('alertContent', $alertContent);
        }
    }

    /**
     * Before render callback.
     *
     * @param \Cake\Event\Event $event The beforeRender event.
     * @return \Cake\Network\Response|null|void
     */
    public function beforeRender(Event $event) {
        if (!array_key_exists('_serialize', $this->viewVars) &&
                in_array($this->response->getType(), ['application/json', 'application/xml'])
        ) {
            $this->set('_serialize', true);
        }
        Utils::useComponents($this, ['Backend.MultiLanguage']);
        $langCode = $this->MultiLanguage->getCurrentLanguageCode();
        $this->set('currentAction', $this->request->getParam('action'));
        if ($this->seoId !== false && !empty($this->seoTarget)) {
            Utils::useTables($this, ['Backend.Seos']);
            $seo = $this->Seos->find('all', [
                        'conditions' => [
                            'target_id' => $this->seoId,
                            'target_type' => $this->seoTarget,
                            'language' => $langCode,
                        ],
                    ])->first();
            if (!empty($seo)) {
                $seo->content = json_decode($seo->content, true);
                $this->seoKeyword = !empty($seo->content['keyword']) ? implode(', ', $seo->content['keyword']) : '';
                $this->pageDesc = !empty($seo->content['description']) ? $seo->content['description'] : '';
                $this->pageImage = !empty($seo->content['thumbnail']) ? $seo->content['thumbnail'] : '';
            }
        }
        $this->set('pageDesc', $this->pageDesc);
        $this->set('seoKeyword', $this->seoKeyword);
        $this->set('pageTitle', $this->pageTitle);
        $this->set('pageImage', $this->pageImage);
    }

    /**
     * sendAjax send ajax
     * @param  string $status       status
     * @param  string $errorMessage error message
     * @return null
     */
    protected function sendAjax($status = null, $errorMessage = '', $data = []) {
        $this->viewBuilder()->setLayout(false);
        $this->autoRender = false;
        $this->ajaxResponse['status'] = $status;
        $this->ajaxResponse['message'] = $errorMessage;
        if (!empty($data)) {
            $this->ajaxResponse['data'] = $data;
        }
        $this->response = $this->response->withStringBody(json_encode($this->ajaxResponse));
        $this->response = $this->response->withType('json');
        return $this->response;
    }

    protected function sendAsyncResponse() {
        Utils::useComponents($this, ['Backend.AsyncResponse']);
        $this->viewBuilder()->setLayout(false);
        $this->autoRender = false;
        $this->ajaxResponse = $this->AsyncResponse->getData();
        $this->response = $this->response->withStringBody(json_encode($this->ajaxResponse));
        $this->response = $this->response->withType('json');
        return $this->response;
    }

    protected function _setLanguage($languageCode = LANGUAGE_VIETNAMESE) {
        Utils::useComponents($this, ['Backend.MultiLanguage']);
        $this->MultiLanguage->setCurrentLanguage($languageCode);
    }

    public function loadComponent($name, array $config = []) {
        if (empty(self::$_globalObjects['components'][$name])) {
            self::$_globalObjects['components'][$name] = parent::loadComponent($name, $config);
        }
        return self::$_globalObjects['components'][$name];
    }

    public function loadTable($name) {
        if (empty(self::$_globalObjects['tables'][$name])) {
            self::$_globalObjects['tables'][$name] = TableRegistry::getTableLocator()->get($name);
        }
        return self::$_globalObjects['tables'][$name];
    }

}
