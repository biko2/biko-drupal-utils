<?php
namespace Drupal\biko_drupal_utils\Entity;

/**
 * Class BikoEntityService
 * @package Drupal\biko_drupal_utils\Entity
 *
 */

class BikoEntityService
{

    /**
     * Obtiene el renderizado de un node
     *
     * @param \Drupal\node\Entity\Node $node
     * @param string $viewMode
     * @return String
     */
    public function getNodeRendering(\Drupal\node\Entity\Node $node, $viewMode = 'full')
    {
        return $this->getEntityRendering($node, $viewMode);
    }

    /**
     * Obtiene el renderizado de un término
     *
     * @param Drupal\taxonomy\Entity\Term $term
     * @param string $viewMode
     * @return String
     */
    public function getTermRendering(\Drupal\taxonomy\Entity\Term $term, $viewMode = 'full')
    {
        return $this->getEntityRendering($term, $viewMode);
    }

    /**
     * Obtiene el renderizado de un block
     *
     * @param \Drupal\block\Entity\Block $block
     * @return string
     */
    public function getBlockRendering(\Drupal\block\Entity\Block $block)
    {
        return $this->getEntityRendering($block, null);
    }



    /**
     * Obtiene el render array de un node
     *
     * @param \Drupal\node\Entity\Node $node
     * @param string $viewMode
     * @return Array
     */
    public function getNodeRenderArray(\Drupal\node\Entity\Node $node, $viewMode = 'full')
    {
        return $this->getEntityRenderArray($node, $viewMode);
    }

    /**
     * Obtiene el render array de un block
     *
     * @param \Drupal\block\Entity\Block $block
     * @return Array
     */
    public function getBlockRenderArray(\Drupal\block\Entity\Block $block)
    {
        return $this->getEntityRenderArray($block, null);
    }

    /**
     * Renderiza una entidad de cualquier tipo (node, block, etc)
     *
     * @param \Drupal\Core\Entity\EntityInterface $entity
     *  Entidad a renderizar (\Drupal\node\Entity\Node, \Drupal\block\Entity/Block, etc)
     * @param $viewMode
     *  View Mode para entidades de tipo Node
     * @return string
     *  HTML con el renderizado de la entidad
     */
    protected function getEntityRendering(\Drupal\Core\Entity\EntityInterface $entity, $viewMode)
    {
        $entityRenderArray = $this->getEntityRenderArray($entity, $viewMode);
        return \Drupal::service('renderer')->render($entityRenderArray);
    }

    /**
     * Obtiene el render array de una entidad de cualquier tipo (node, block, etc)
     *
     * @param \Drupal\Core\Entity\EntityInterface $entity
     *  Entidad (\Drupal\node\Entity\Node, \Drupal\block\Entity/Block, etc)
     * @param $viewMode
     *  View Mode para entidades de tipo Node
     * @return array
     *  Render array de la entidad
     */
    protected function getEntityRenderArray(\Drupal\Core\Entity\EntityInterface $entity, $viewMode)
    {
        $renderController = \Drupal::entityTypeManager()->getViewBuilder($entity->getEntityTypeId());
        if ($viewMode) {
            $entityRenderArray = $renderController->view($entity, $viewMode);
        } else {
            $entityRenderArray = $renderController->view($entity);
        }
        return $entityRenderArray;
    }



    /*
         * Ya no hace falta este tipo de utilidades, ya que en D8 podemos sacar los fields de forma sencilla
     * - $entity->body->value o $entity->get($fieldName)->value
     * - $entity->field_with_multiple_values->value o $entity->field_with_multiple_values[n]->value
     * - $entity->field_with_reference->value o $entity->field_with_reference[n]->entity->method()
     *
    public function getUrl($entity, $fieldName) {
        return $entity->get($fieldName)->entity->url();
    }

    public function getValue($entity, $fieldName) {
        return $entity->get($fieldName)->value;
    }

    public function getValues($entity, $fieldName) {
        $valuesArray = $entity->get($fieldName)->getValue();
        $values = [];
        foreach($valuesArray as $delta => $item) {
            $values[$delta] = $item['value'];
        }
        if (count($values)) {
            return $values;
        }
        else {
            return NULL;
        }
    }
    */
}
