<?php

namespace Drupal\biko_drupal_utils\Language;

use Drupal\Component\Plugin\PluginManagerInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Site\Settings;
use Drupal\language\LanguageNegotiator;
use Drupal\language\Plugin\LanguageNegotiation\LanguageNegotiationUI;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class responsible for performing language negotiation.
 */
class BikoLanguageNegotiator extends \Drupal\language\LanguageNegotiator {

    var $languageCode = NULL;

    /**
     * {@inheritdoc}
     */
    public function initializeType($type) {
        $language = NULL;
        $method_id = static::METHOD_ID;
        $availableLanguages = $this->languageManager->getLanguages();

        if ($this->languageCode && isset($availableLanguages[$this->languageCode])) {
            $language = $availableLanguages[$this->languageCode];
        }
        else {
            // If no other language was found use the default one.
            $language = $this->languageManager->getDefaultLanguage();
        }

        return array($method_id => $language);
    }

    /**
     * @param string $languageCode
     */
    public function setLanguageCode($languageCode)
    {
        $this->languageCode = $languageCode;
    }


}
