<?php
namespace Drupal\biko_utils\Theme;

class BikoThemeService {

    /**
     * Devuelve el nombre del theme activo
     *
     * @return string
     */
    public function getActiveThemeName() {
        return \Drupal::theme()->getActiveTheme()->getName();
    }

    /**
     * Devuelve el nombre del theme por defecto (el de frontend)
     *
     * @return string
     */
    public function getDefaultThemeName() {
        return \Drupal::config('system.theme')->get('default');
    }

    /**
     * Activa un theme
     *
     * @param string $themeName
     *  Nombre del theme
     * @return string
     */
    public function setActiveTheme($themeName) {
        $theme = \Drupal::service('theme.initialization')->initTheme($themeName);
        \Drupal::theme()->resetActiveTheme();
        \Drupal::theme()->setActiveTheme($theme);
    }

}