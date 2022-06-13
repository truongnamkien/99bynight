<?php

namespace Backend\Controller\Component;

use App\Utility\Utils;
use Backend\Controller\CoreController;
use Cake\Controller\Component;
use Cake\Core\Configure;
use Cake\I18n\I18n;

class MultiLanguageComponent extends Component {

    public static $_currentLanguage = false;
    public static $_currentLanguageCode = false;
    protected static $_languageSessionKey = 'CurrentLanguage:Session';
    protected static $_languageSessionCode = 'CurrentLanguageCode:Session';
    protected static $_languageList = [];

    public function __construct() {
        self::$_languageList = Configure::read('LanguageList');
        $this->autoDetectLanguage();
    }

    public function autoDetectLanguage() {
        $session = CoreController::$_instance->Session;
        if ($session->check(self::$_languageSessionCode) && $session->check(self::$_languageSessionKey)) {
            $languageCode = $session->read(self::$_languageSessionCode);
            self::$_currentLanguage = $session->read(self::$_languageSessionKey);
        } else {
            $languageCode = Configure::read('DefaultLanguage');
            self::$_currentLanguage = strtolower(self::$_languageList[$languageCode]);
            self::$_currentLanguageCode = $languageCode;
            $session->write(self::$_languageSessionKey, self::$_currentLanguage);
            $session->write(self::$_languageSessionCode, $languageCode);
        }
        $localeList = Configure::read('LocaleList');
        I18n::setLocale($localeList[$languageCode]);
    }

    public function getCurrentLanguage() {
        if (!self::$_currentLanguage) {
            $session = CoreController::$_instance->Session;
            self::$_currentLanguage = $session->read(self::$_languageSessionKey);
        }
        if (!self::$_currentLanguage) {
            self::$_currentLanguage = $languageCode[$this->getCurrentLanguageCode()];
        }
        return self::$_currentLanguage;
    }

    public function getCurrentLanguageCode() {
        if (self::$_currentLanguageCode === false) {
            $session = CoreController::$_instance->Session;
            self::$_currentLanguageCode = $session->read(self::$_languageSessionCode);
        }
        if (self::$_currentLanguageCode === false) {
            self::$_currentLanguageCode = Configure::read('DefaultLanguage');
        }
        return self::$_currentLanguageCode;
    }

    public function setCurrentLanguage($languageCode) {
        if (!isset(self::$_languageList[$languageCode])) {
            return false;
        }
        $language = strtolower(self::$_languageList[$languageCode]);
        self::$_currentLanguage = $language;
        self::$_currentLanguageCode = $languageCode;
        $session = CoreController::$_instance->Session;
        $session->write(self::$_languageSessionKey, $language);
        $session->write(self::$_languageSessionCode, $languageCode);

        $localeList = Configure::read('LocaleList');
        I18n::setLocale($localeList[$languageCode]);

        return true;
    }

}
