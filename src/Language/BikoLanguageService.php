<?php
namespace Drupal\biko_drupal_utils\Language;

/**
 * Class BikoLanguageService
 * @package Drupal\biko_drupal_utils\Language
 *
 */

class BikoLanguageService
{

    var $initialized = false;

    /**
     * Cambia el idioma actual
     *
     * @param string $languageCode
     * @return void
     */
    public function setCurrentLanguage($languageCode)
    {
        // Obtenemos el language manager
        $languageManager = \Drupal::languageManager();

        if (!$this->initialized) {
            // Obtenemos el language Negotiator de Biko, que nos permite cambiar el language
            $bikoLanguageNegotiator = \Drupal::service('biko.language_negotiator');

            // Definimos el negotiator
            $languageManager->setNegotiator($bikoLanguageNegotiator);

            $this->initialized = true;
        }

        $languageManager->reset();
        $languageManager->getNegotiator()->setLanguageCode($languageCode);

    }

    /**
     * Devuelve el idioma actual (wrapper para tenerlo a mano)
     *
     * @return \Drupal\Core\Language\LanguageInterface
     */
    public function getCurrentLanguage()
    {
        return \Drupal::languageManager()->getCurrentLanguage();
    }


}
