<?php
namespace Drupal\biko_drupal_utils\Render;

use Drupal\file\Entity\File;
use Drupal\image\Entity\ImageStyle;

/**
 * Class BikoRenderService
 * @package Drupal\biko_drupal_utils\Render
 *
 */

class BikoRenderService
{

    /**
    * Agrega CSS inline generado dinámicamente via #attached
    *
    */
    public function attachInlineCssToHeader($css, array &$variables)
    {
        $dynamic_css = [
        '#type' => 'html_tag',
        '#tag' => 'style',
        '#weight' => 1000,
        '#attributes' => [
          'media' => 'all',
        ],
        '#value' => $css,
      ];

        $description = 'inline-css-' . uniqid();
        $variables['#attached']['html_head'][] = [$dynamic_css, $description];
    }

    /**
    * Agrega JS inline generado dinámicamente via #attached
    *
    */
    public function attachInlineJsToHeader($js, array &$variables)
    {
        $dynamic_js = [
        '#type' => 'html_tag',
        '#tag' => 'script',
        '#weight' => 1000,
        '#value' => $js,
      ];

        $description = 'inline-js-' . uniqid();
        $variables['#attached']['html_head'][] = [$dynamic_js, $description];
    }

    /**
     * Genera un estilo de imagen a disco si ese estilo no existe, y devuelve su url
     *
     * @param int $fileId
     *  Id de la entity del fichero
     * @param string $imageStyle
     *  Nombre del style de la imagen, por ejemplo "half_500x500"
     *
     * @return string|null
     */
    public function getImageStyleUrl($fileId, $imageStyle)
    {
        $file = File::load($fileId);
        if ($file) {
            $style = ImageStyle::load($imageStyle);
            return ($style) ? $style->buildUrl($file->getFileUri()) : null;
        }
        return null;
    }
}
