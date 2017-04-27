<?php
/**
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

use Drupal\Core\Template\Attribute;
use Drupal\Core\Template\TwigExtension;
use Drupal\Core\Render\RendererInterface;
use Drupal\Core\Url;
use ReflectionObject;
use Drupal\Core\Datetime\DateFormatterInterface;
use Drupal\Core\Routing\UrlGeneratorInterface;
use Drupal\Core\Theme\ThemeManagerInterface;

class BikoTwigExtension extends TwigExtension
{
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
  public function __construct(RendererInterface $renderer, UrlGeneratorInterface $url_generator, ThemeManagerInterface $theme_manager, DateFormatterInterface $date_formatter)
  {
    parent::__construct($renderer, $url_generator, $theme_manager, $date_formatter);
    $this->renderer = $renderer;
  }

  /**
   * Gets a unique identifier for this Twig extension.
   *
   * @return string
   *   A unique identifier for this Twig extension.
   */
  public function getName()
  {
    return 'biko_twig_extension';
  }

  /**
   * Generates a list of all Twig functions that this extension defines.
   *
   * @return array
   *   A key/value array that defines custom Twig functions. The key denotes the
   *   function name used in the tag, e.g.:
   * @code
   *   {{ testfunc() }}
   * @endcode
   *
   *   The value is a standard PHP callback that defines what the function does.
   */
  public function getFunctions()
  {
    return array(
      new \Twig_SimpleFunction('link_html', array($this, 'linkHtml')),
      new \Twig_SimpleFunction('add_to_array', array($this, 'addToArray')),
      new \Twig_SimpleFunction('path_alias', array($this, 'pathAlias')),
      new \Twig_SimpleFunction('render_node', array($this, 'renderNode')),
      new \Twig_SimpleFunction('render_term', array($this, 'renderTerm')),
      new \Twig_SimpleFunction('render_block', array($this, 'renderBlock')),
      new \Twig_SimpleFunction('render_contact_form', array($this, 'renderContactForm')),
      new \Twig_SimpleFunction('image_style_url', array($this, 'getImageStyleUrl')),
      new \Twig_SimpleFunction('get_class', array($this, 'getClass')),
      new \Twig_SimpleFunction('reflection_export', array($this, 'reflectionExport')),
      new \Twig_SimpleFunction('xdebug', array($this, 'xdebug')),
      new \Twig_SimpleFunction('drupal_format_size', 'format_size'),
      new \Twig_SimpleFunction('drupal_sanitize', array($this, 'clean_text')),
      new \Twig_SimpleFunction('file_get_contents', array($this, 'file_get_contents')),
      new \Twig_SimpleFunction('embed_base64', array($this, 'embed_base64')),
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
   * @example {{ link_html('span>'~item.title~'</span>', item.url, { 'class':['foo', 'bar', 'baz']} ) }}
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
  public function linkHtml($inline_template, $url, $attributes = [])
  {
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



  /**
   * Añade a un array multidimensional los elementos que queramos
   *
   * @example {% set item = add_to_array(item, ['link', '#options', 'attributes', 'class'], ['text-color-dove-gray']) %}
   * @example {{ item.link }}
   *
   * @param array $array
   *   El array original
   * @param array $keys
   *   El "path" hasta el punto del array donde queremos acceder.
   *   Es un array con los keys a usar
   * @param array $arrayToAdd
   *   Datos a añadir
   *
   * @return array
   *   Array con los nuevos datos
   */
  public function addToArray($array, $keys, $arrayToAdd)
  {

//       echo '<pre>'.print_r(array_keys($array['field_taxonomy_image'][0]['#item_attributes']),1).'</pre>';exit;
    // Si tenemos keys, seguimos iterando recursivamente
    if (count($keys)) {
      $currentKey = array_shift($keys);
      if (!isset($array[$currentKey])) {
        $array[$currentKey] = array();
      }
      $array[$currentKey] = $this->addToArray($array[$currentKey], $keys, $arrayToAdd);
    }
    // Si ya no quedan keys, es que hemos llegado a la profundidad deseada
    else {
      if (is_array($arrayToAdd)) {
        $array = array_merge($array, $arrayToAdd);
      }
      else {
        $array = $arrayToAdd;
      }
    }

    return $array;
  }


  /**
   * Obtiene el alias de cualquier path /node/NNN
   *
   * @example {{ path_alias('/node/'~current_node.id, item.link['#options'].language.id) }}
   *
   * @param string $path
   *   Path en formato /node/NNN
   * @param string $languageCode
   *   Cadena del idioma, por ejemplo "es", "en", etc
   *
   * @return string
   *   Alias en formato /{languageCode}/{alias}
   */
  public function pathAlias($path, $languageCode)
  {
    $alias = '';

    if (substr($path, 0, 1) == '/') {

      $alias = \Drupal::service('path.alias_manager')->getAliasByPath($path, $languageCode);
      // Si el alias no devuelve nada, como en las portadas,
      // que produce urls como "/es/node/", lo dejamos vacio para que apunte a "/es"
      if ($alias == '/node/') {
        $alias = null;
      }

      // Obtenemos la config de autodetención de idioma para ver qué código de idioma hay que poner
      $languageNegotiationConfig = \Drupal::config('language.negotiation')->get('url')['prefixes'];

      if (!empty($languageNegotiationConfig[$languageCode])) {
        $alias = '/' . $languageNegotiationConfig[$languageCode] . $alias;
      }

    }

    return $alias;
  }


  /**
   * Obtiene el html de cualquier nodo
   *
   * @example {{ render_node(1) }}
   *
   * @param integer $nodeId
   *  Id del nodo
   *
   * @param string $viewMode
   *  ViewMode (por defecto será null)
   *
   * @return string
   *
   */
  public function renderNode($nodeId, $viewMode = 'full')
  {
    // Obtenemos la entidad del nodo
    $nodeEntity = \Drupal\node\Entity\Node::load($nodeId);

    // Obtenemos el html del nodo
    return \Drupal::service('biko.entity')->getNodeRendering($nodeEntity, $viewMode);
  }

  /**
   * Obtiene el html de cualquier term
   *
   * @example {{ render_term(1) }}
   *
   * @param integer $termId
   *  Id del term
   *
   * @param string $viewMode
   *  ViewMode (por defecto será null)
   *
   * @return string
   *
   */
  public function renderTerm($termId, $viewMode = 'full')
  {
    // Obtenemos la entidad del nodo
    $termEntity = \Drupal\taxonomy\Entity\Term::load($termId);

    // Obtenemos el html del nodo
    return \Drupal::service('biko.entity')->getTermRendering($termEntity, $viewMode);
  }

  /**
   * Obtiene el html de cualquier bloque
   *
   * @example {{ render_block('views_block__product_category_taxomony_menu_block_mobile') }}
   *
   * @param integer $blockId
   *  Id del bloque
   *
   * @return string
   *
   */
  public function renderBlock($blockId)
  {
    // Obtenemos la entidad del bloque
    $blockEntity = \Drupal\block\Entity\Block::load($blockId);

    // Obtenemos el html del bloque
    return \Drupal::service('biko.entity')->getBlockRendering($blockEntity);
  }

  /**
   * Obtiene el html de cualquier ContactForm
   *
   * @example {{ render_contact_form(content.field_contenido_relacionado['#items'].entity.field_form[0].target_id) }}
   *
   * @param integer $formId
   *  Id del bloque
   *
   * @return string
   *
   */
  public function renderContactForm($formId)
  {
    // Obtenemos la entidad del form
    $formEntity = \Drupal\contact\Entity\ContactForm::load($formId);
    if (!empty($formEntity)) {
      $message = \Drupal::entityTypeManager()
        ->getStorage('contact_message')
        ->create(array(
          'contact_form' => $formId,
        ));

      // This works in a controller, use \Drupal::service('entity.form_builder') elsewhere.
      $form = \Drupal::service('entity.form_builder')->getForm($message);
      //$form['#title'] = \Drupal\Component\Utility\SafeMarkup::checkPlain($formEntity->label());
      // Obtenemos el html del bloque
      return $form;
    }
    return null;
  }

  /**
   * Genera un estilo de imagen a disco si ese estilo no existe, y devuelve su url
   *
   * @example {{ image_style_url(content.field_imagen['#items'].entity.id,'half_500x500') }}
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
    return \Drupal::service('biko.render')->getImageStyleUrl($fileId, $imageStyle);
  }

  /**
   * Devuelve la clase del objeto (util en debugeo)
   *
   * @example {{ get_class(view.result[0]) }}
   *
   * @param object $object
   *
   * @return string
   */
  public function getClass($object)
  {
    return get_class($object);
  }


  /**
   * Muestra la clase del objeto (util en debugeo)
   *
   * @example {{ reflection_export(view.result[0]) }}
   *
   * @param object $object
   *
   * @return string
   */
  public function reflectionExport($object)
  {
    return '<pre>'.ReflectionObject::export($object, true).'<pre>';
  }

  /**
   * Renderiza un link a partir de un objeto url, usando una plantilla inline
   *
   * @example {{ link_html('span>'~item.title~'</span>', item.url, { 'class':['foo', 'bar', 'baz']} ) }}
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
  public function clean_text($string)
  {
    return \Drupal::service('pathauto.alias_cleaner')->cleanString($string);
  }


  /**
   * Método dummy para poder usar xdebug desde Twig,
   * añadiendo un breakpoint en la línea "$dummy = object"
   *
   * @example {{ xdebug(view.result[0]) }}
   *
   * @param object $object
   *
   * @return string
   */
  public function xdebug($object)
  {
    $dummy = $object;
    return '';
  }

  /**
   * Obtiene el contenido de un fichero
   *
   * @example {{ file_get_contents('imagen.jpg') }}"/>
   *
   * @param string $path
   *
   * @return string
   */
  public function file_get_contents($path)
  {
    $path = DRUPAL_ROOT.'/'.$path;

    if (is_file($path)) {
      return file_get_contents($path);
    }
    return '';
  }

  /**
   * Obtiene el contenido de un fichero, codificado en base64,
   * y con las cabeceras correspondientes según el mime type del fichero
   *
   * @example <img src="{{ base64_embed('imagen.jpg') }}"/>
   *
   * @param string $path
   *
   * @return string
   */
  public function embed_base64($path)
  {
    $path = DRUPAL_ROOT.'/'.$path;
    $extension = pathinfo($path, PATHINFO_EXTENSION);

    if (is_file($path)) {
      $mime = ($extension == 'svg') ? 'image/svg+xml' : mime_content_type($path);
      $base64Data = base64_encode(file_get_contents($path));
      return 'data:'.$mime.';base64,'.$base64Data;
    }
    return '';
  }

}
