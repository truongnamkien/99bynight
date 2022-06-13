<?php

namespace App\View\Helper;

use Cake\Core\Configure;
use Cake\View\Helper;
use Cake\View\View;
use App\Utility\Utils;

class LinkHelper extends Helper {

    public $helpers = ['Url'];

    public function homeUrl($languageCode = false) {
        if ($languageCode === false) {
            Utils::useComponents($this, ['Backend.MultiLanguage']);
            $languageCode = $this->MultiLanguage->getCurrentLanguageCode();
        }
        if (intval($languageCode) === Configure::read('DefaultLanguage')) {
            return $this->Url->build('/', true);
        } else if (intval($languageCode) === LANGUAGE_VIETNAMESE) {
            return $this->Url->build('/vi', true);
        }
        return $this->Url->build('/en', true);
    }

    public function contactUrl($languageCode = false) {
        if ($languageCode === false) {
            Utils::useComponents($this, ['Backend.MultiLanguage']);
            $languageCode = $this->MultiLanguage->getCurrentLanguageCode();
        }
        if (intval($languageCode) === LANGUAGE_VIETNAMESE) {
            return $this->Url->build('/lien-he', true);
        }
        return $this->Url->build('/contact', true);
    }

    public function pageDetailUrl($page, $languageCode = false) {
        Utils::useComponents($this, ['Backend.Slug']);
        if ($languageCode === false) {
            Utils::useComponents($this, ['Backend.MultiLanguage']);
            $languageCode = $this->MultiLanguage->getCurrentLanguageCode();
        }
        $slug = $this->Slug->getSlugObject($page->id, 'Pages', $languageCode);
        if (!empty($slug)) {
            if (intval($languageCode) === LANGUAGE_VIETNAMESE) {
                return $this->Url->build('/thong-tin/' . $slug->name, true);
            }
            return $this->Url->build('/pages/' . $slug->name, true);
        }
        if (intval($languageCode) === LANGUAGE_VIETNAMESE) {
            return $this->Url->build('/thong-tin', true);
        }
        return $this->Url->build('/pages', true);
    }

    public function blogDetailUrl($blog, $languageCode = false) {
        Utils::useComponents($this, ['Backend.Slug']);
        if ($languageCode === false) {
            Utils::useComponents($this, ['Backend.MultiLanguage']);
            $languageCode = $this->MultiLanguage->getCurrentLanguageCode();
        }
        $slug = $this->Slug->getSlugObject($blog->id, 'Blogs', $languageCode);
        if (!empty($slug)) {
            if (intval($languageCode) === LANGUAGE_VIETNAMESE) {
                return $this->Url->build('/tin-tuc/chi-tiet/' . $slug->name, true);
            }
            return $this->Url->build('/blogs/detail/' . $slug->name, true);
        }
        if (intval($languageCode) === LANGUAGE_VIETNAMESE) {
            return $this->Url->build('/tin-tuc', true);
        }
        return $this->Url->build('/blogs', true);
    }

    public function blogCategoryUrl($category, $languageCode = false) {
        Utils::useComponents($this, ['Backend.Slug']);
        if ($languageCode === false) {
            Utils::useComponents($this, ['Backend.MultiLanguage']);
            $languageCode = $this->MultiLanguage->getCurrentLanguageCode();
        }
        $slug = $this->Slug->getSlugObject($category->id, 'BlogCategories', $languageCode);
        if (!empty($slug)) {
            if (intval($languageCode) === LANGUAGE_VIETNAMESE) {
                return $this->Url->build('/tin-tuc/chu-de/' . $slug->name, true);
            }
            return $this->Url->build('/blogs/category/' . $slug->name, true);
        }
        if (intval($languageCode) === LANGUAGE_VIETNAMESE) {
            return $this->Url->build('/tin-tuc', true);
        }
        return $this->Url->build('/blogs', true);
    }

    public function blogListUrl($languageCode = false) {
        if ($languageCode === false) {
            Utils::useComponents($this, ['Backend.MultiLanguage']);
            $languageCode = $this->MultiLanguage->getCurrentLanguageCode();
        }
        if (intval($languageCode) === LANGUAGE_VIETNAMESE) {
            return $this->Url->build('/tin-tuc', true);
        }
        return $this->Url->build('/blogs', true);
    }

    public function serviceDetailUrl($serviceInfo, $languageCode = false) {
        Utils::useComponents($this, ['Backend.Slug']);
        if ($languageCode === false) {
            Utils::useComponents($this, ['Backend.MultiLanguage']);
            $languageCode = $this->MultiLanguage->getCurrentLanguageCode();
        }
        $slug = $this->Slug->getSlugObject($serviceInfo->id, 'Services', $languageCode);
        if (!empty($slug)) {
            if (intval($languageCode) === LANGUAGE_VIETNAMESE) {
                return $this->Url->build('/dich-vu/' . $slug->name, true);
            }
            return $this->Url->build('/services/' . $slug->name, true);
        }
        if (intval($languageCode) === LANGUAGE_VIETNAMESE) {
            return $this->Url->build('/dich-vu', true);
        }
        return $this->Url->build('/services', true);
    }

    public function specialistDetailUrl($specialist, $languageCode = false) {
        Utils::useComponents($this, ['Backend.Slug']);
        if ($languageCode === false) {
            Utils::useComponents($this, ['Backend.MultiLanguage']);
            $languageCode = $this->MultiLanguage->getCurrentLanguageCode();
        }
        $slug = $this->Slug->getSlugObject($specialist->id, 'Specialists', $languageCode);
        if (!empty($slug)) {
            if (intval($languageCode) === LANGUAGE_VIETNAMESE) {
                return $this->Url->build('/chuyen-khoa/chi-tiet/' . $slug->name, true);
            }
            return $this->Url->build('/specialists/detail/' . $slug->name, true);
        }
        if (intval($languageCode) === LANGUAGE_VIETNAMESE) {
            return $this->Url->build('/chuyen-khoa', true);
        }
        return $this->Url->build('/specialists', true);
    }

    public function specialistCategoryUrl($category, $languageCode = false) {
        Utils::useComponents($this, ['Backend.Slug']);
        if ($languageCode === false) {
            Utils::useComponents($this, ['Backend.MultiLanguage']);
            $languageCode = $this->MultiLanguage->getCurrentLanguageCode();
        }
        $slug = $this->Slug->getSlugObject($category->id, 'SpecialistCategories', $languageCode);
        if (!empty($slug)) {
            if (intval($languageCode) === LANGUAGE_VIETNAMESE) {
                return $this->Url->build('/chuyen-khoa/danh-sach/' . $slug->name, true);
            }
            return $this->Url->build('/specialists/category/' . $slug->name, true);
        }
        if (intval($languageCode) === LANGUAGE_VIETNAMESE) {
            return $this->Url->build('/chuyen-khoa', true);
        }
        return $this->Url->build('/specialists', true);
    }

    public function specialistListUrl($languageCode = false) {
        if ($languageCode === false) {
            Utils::useComponents($this, ['Backend.MultiLanguage']);
            $languageCode = $this->MultiLanguage->getCurrentLanguageCode();
        }
        if (intval($languageCode) === LANGUAGE_VIETNAMESE) {
            return $this->Url->build('/chuyen-khoa', true);
        }
        return $this->Url->build('/specialists', true);
    }

    public function healthcareListUrl($languageCode = false) {
        if ($languageCode === false) {
            Utils::useComponents($this, ['Backend.MultiLanguage']);
            $languageCode = $this->MultiLanguage->getCurrentLanguageCode();
        }
        if (intval($languageCode) === LANGUAGE_VIETNAMESE) {
            return $this->Url->build('/kham-suc-khoe', true);
        }
        return $this->Url->build('/healthcares', true);
    }

    public function healthcarePackageUrl($package, $languageCode = false) {
        Utils::useComponents($this, ['Backend.Slug']);
        if ($languageCode === false) {
            Utils::useComponents($this, ['Backend.MultiLanguage']);
            $languageCode = $this->MultiLanguage->getCurrentLanguageCode();
        }
        $slug = $this->Slug->getSlugObject($package->id, 'HealthCarePackages', $languageCode);
        if (!empty($slug)) {
            if (intval($languageCode) === LANGUAGE_VIETNAMESE) {
                return $this->Url->build('/kham-suc-khoe/chuong-trinh/' . $slug->name, true);
            }
            return $this->Url->build('/healthcares/package/' . $slug->name, true);
        }
        if (intval($languageCode) === LANGUAGE_VIETNAMESE) {
            return $this->Url->build('/kham-suc-khoe', true);
        }
        return $this->Url->build('/healthcares', true);
    }

    public function healthcareCategoryUrl($category, $languageCode = false) {
        Utils::useComponents($this, ['Backend.Slug']);
        if ($languageCode === false) {
            Utils::useComponents($this, ['Backend.MultiLanguage']);
            $languageCode = $this->MultiLanguage->getCurrentLanguageCode();
        }
        $slug = $this->Slug->getSlugObject($category->id, 'HealthCareCategories', $languageCode);
        if (!empty($slug)) {
            if (intval($languageCode) === LANGUAGE_VIETNAMESE) {
                return $this->Url->build('/kham-suc-khoe/hang-muc/' . $slug->name, true);
            }
            return $this->Url->build('/healthcares/category/' . $slug->name, true);
        }
        if (intval($languageCode) === LANGUAGE_VIETNAMESE) {
            return $this->Url->build('/kham-suc-khoe', true);
        }
        return $this->Url->build('/healthcares', true);
    }

    public function healthcareDetailUrl($healthcare, $languageCode = false) {
        Utils::useComponents($this, ['Backend.Slug']);
        if ($languageCode === false) {
            Utils::useComponents($this, ['Backend.MultiLanguage']);
            $languageCode = $this->MultiLanguage->getCurrentLanguageCode();
        }
        $slug = $this->Slug->getSlugObject($healthcare->id, 'HealthCares', $languageCode);
        if (!empty($slug)) {
            if (intval($languageCode) === LANGUAGE_VIETNAMESE) {
                return $this->Url->build('/kham-suc-khoe/chi-tiet/' . $slug->name, true);
            }
            return $this->Url->build('/healthcares/detail/' . $slug->name, true);
        }
        if (intval($languageCode) === LANGUAGE_VIETNAMESE) {
            return $this->Url->build('/kham-suc-khoe', true);
        }
        return $this->Url->build('/healthcares', true);
    }

}
