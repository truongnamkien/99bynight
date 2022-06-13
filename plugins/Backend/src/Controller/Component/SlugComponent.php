<?php

namespace Backend\Controller\Component;

use App\Utility\Utils;
use Cake\Controller\Component;
use Cake\Core\Configure;

class SlugComponent extends Component {

    public function getTargetId($slug, $type = false, $detectLanguage = true) {
        if (empty($slug)) {
            return 0;
        }
        Utils::useTables($this, ['Backend.Slugs']);
        $conditions = [
            'name' => $slug,
        ];
        if ($detectLanguage) {
            Utils::useComponents($this, ['Backend.MultiLanguage']);
            $languageCode = $this->MultiLanguage->getCurrentLanguageCode();
            $conditions['language'] = $languageCode;
        }
        if (!empty($type)) {
            $conditions['target_type'] = $type;
        }
        $object = $this->Slugs->find('all', [
                    'conditions' => $conditions,
                ])->first();
        if (!empty($object)) {
            return $object->target_id;
        }
        return 0;
    }

    public function getTargetObject($slug, $type = false, $detectLanguage = true) {
        if (empty($slug)) {
            return null;
        }
        $object = $this->getSlugObjectBySlug($slug, $type, $detectLanguage);
        if (!empty($object)) {
            $model = $object->target_type;
            Utils::useTables($this, [$model]);
            $targetObject = $this->$model->findById($object->target_id)->first();
            return $targetObject;
        }
        return null;
    }

    public function getSlugObjectBySlug($slug, $type = false, $detectLanguage = true) {
        if (empty($slug)) {
            return null;
        }
        Utils::useTables($this, ['Backend.Slugs']);
        Utils::useComponents($this, ['Backend.MultiLanguage']);
        $languageCode = $this->MultiLanguage->getCurrentLanguageCode();
        $conditions = [
            'name' => $slug,
        ];
        if ($detectLanguage) {
            Utils::useComponents($this, ['Backend.MultiLanguage']);
            $languageCode = $this->MultiLanguage->getCurrentLanguageCode();
            $conditions['language'] = $languageCode;
        }
        if (!empty($type)) {
            $conditions['target_type'] = $type;
        }
        $object = $this->Slugs->find('all', [
                    'conditions' => $conditions,
                ])->first();
        return $object;
    }

    public function getSlugObject($targetId, $type, $languageCode = false) {
        if (empty($targetId) || empty($type)) {
            return null;
        }
        Utils::useTables($this, ['Backend.Slugs']);
        if ($languageCode === false) {
            Utils::useComponents($this, ['Backend.MultiLanguage']);
            $languageCode = $this->MultiLanguage->getCurrentLanguageCode();
        }
        $slug = $this->Slugs->find('all', [
                    'conditions' => [
                        'target_id' => $targetId,
                        'target_type' => $type,
                        'language' => $languageCode,
                    ],
                ])->first();
        return $slug;
    }

}
