<?php
namespace Drupal\biko_drupal_utils\Render;

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
    * Devuelve la url de la imagen con el estilo indicado generado
    */
    public function getImageStyleUrl($fid, $imageStyle)
    {
        $file = File::load($fid);
        if ($file) {
            $variables = array(
        'style_name' => $imageStyle,
        'uri' => $file->getFileUri(),
      );
      // The image.factory service will check if our image is valid.
      $image = \Drupal::service('image.factory')->get($file->getFileUri());
            if ($image->isValid()) {
                $variables['width'] = $image->getWidth();
                $variables['height'] = $image->getHeight();
            } else {
                $variables['width'] = $variables['height'] = null;
            }
            $logo_build = [
        '#theme' => 'image_style',
        '#width' => $variables['width'],
        '#height' => $variables['height'],
        '#style_name' => $variables['style_name'],
        '#uri' => $variables['uri'],
      ];
      // Add the file entity to the cache dependencies.
      // This will clear our cache when this entity updates.
      $renderer = \Drupal::service('renderer');
            $renderer->addCacheableDependency($logo_build, $file);
      // Return the render array as block content.
      return [
        'logo' => $logo_build,
      ];
        } else {
            // Image not found, return empty block.
      return [];
        }
    }
}
