<?php
/**
 * Lunes, 25 Abril 2016 - diego.espino@biko2.com
 *
 * Clase que extiende a la propia extensión Twig que trae D8 de origen:
 * En esta clase podemos definir nuestras propias funciones y filtros para Twig.
 *
 * ¿Cuándo definir un filtro y cuándo una función?
 * http://twig.sensiolabs.org/doc/advanced.html#extending-twig
 *
 * Un filtro es una forma de transformar los datos mostrados. Por ejemplo
 * para mostrar el contenido de una variable L1K3 Th1s, usaríamos un filtro.
 * (ejemplo : {{ username|l33t }})
 *
 * Una función se usa cuando hay que computar cosas para renderizar un resultado.
 * Por ejemplo, la función {{ dump(username) }} que internamente llama a la
 * función PHP "var_dump".
 *
 */

namespace Drupal\biko_drupal_utils\Template;

use Drupal\Core\Template\TwigExtension;
use Drupal\Core\Render\RendererInterface;
use Drupal\Core\Url;


class BikoTwigExtension extends TwigExtension {
  /**
   * The renderer.
   *
   * @var \Drupal\Core\Render\RendererInterface
   */
  protected $renderer;

  /**
   * Constructs \Drupal\Core\Template\TwigExtension.
   *
   * @param \Drupal\Core\Render\RendererInterface $renderer
   *   The renderer.
   */
  public function __construct(RendererInterface $renderer) {
    parent::__construct($renderer);
    $this->renderer = $renderer;
  }

  /**
   * Gets a unique identifier for this Twig extension.
   *
   * @return string
   *   A unique identifier for this Twig extension.
   */
  public function getName() {
    return 'biko_twig_extension';
  }

  /**
   * Generates a list of all Twig functions that this extension defines.
   *
   * @return array
   *   A key/value array that defines custom Twig functions. The key denotes the
   *   function name used in the tag, e.g.:
   *   @code
   *   {{ testfunc() }}
   *   @endcode
   *
   *   The value is a standard PHP callback that defines what the function does.
   */
  public function getFunctions() {
    return array(
      new \Twig_SimpleFunction('link_html', array($this, 'getLink')),
    );
  }

  /**
   * Generates a list of all Twig filters that this extension defines.
   *
   * @return array
   *   A key/value array that defines custom Twig filters. The key denotes the
   *   filter name used in the tag, e.g.:
   *   @code
   *   {{ foo|testfilter }}
   *   @endcode
   *
   *   The value is a standard PHP callback that defines what the filter does.
  public function getFilters() {
    return array(
        new \Twig_SimpleFilter('price', array($this, 'priceFilter')),
    );
  }
   */

  /**
   * Función de ejemplo:
   *
  public function priceFilter($number, $decimals = 0, $decPoint = '.', $thousandsSep = ',') {
    $price = number_format($number, $decimals, $decPoint, $thousandsSep);
    $price = '$'.$price;
        return $price;
  }
   */

  /**
   * Renderiza un link a partir de un objeto url, usando una plantilla inline
   *
   * @param string $inline_template
   *   Plantilla inline que estará dentro del link renderizado.
   * @param \Drupal\Core\Url|string $url
   *   El objeto URL usado para el link.
   * @param array|\Drupal\Core\Template\Attribute $attributes
   *   Un array opcional o un objeto Attribute con los atributos para el link.
   *
   * @return array
   *   Array de renderizado representando un link html con la URL dada.
   */
  public function getLink($inline_template, $url, $attributes = []) {
    if (!$url instanceof Url) {
      $url = Url::fromUri($url);
    }

    if ($attributes) {
      if ($attributes instanceof Attribute) {
        $attributes = $attributes->toArray();
      }
      if ($existing_attributes = $url->getOption('attributes')) {
        $attributes = array_merge($existing_attributes, $attributes);
      }
      $url->setOption('attributes', $attributes);
    }

    $build = [
      '#type' => 'link',
      '#title' => [
        '#type' => 'inline_template',
        '#template' => $inline_template,
      ],
      '#url' => $url,
    ];

    return $build;
  }


}