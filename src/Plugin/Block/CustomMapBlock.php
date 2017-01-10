<?php

namespace Drupal\my_maps\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\node\Entity\Node;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityTypeManager;

/**
 * Provides a 'CustomMapBlock' block.
 *
 * @Block(
 *  id = "custom_map_block",
 *  admin_label = @Translation("Custom map block"),
 * )
 */
class CustomMapBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * Drupal\Core\Entity\EntityTypeManager definition.
   *
   * @var \Drupal\Core\Entity\EntityTypeManager
   */
  protected $entityTypeManager;

  /**
   * Construct.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param string $plugin_definition
   *   The plugin implementation definition.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityTypeManager $entity_type_manager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static($configuration, $plugin_id, $plugin_definition, $container->get('entity_type.manager'));
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'description' => $this->t('My custom map'),
    ] + parent::defaultConfiguration();

  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form['description'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Description'),
      '#description' => '',
      '#default_value' => $this->configuration['description'],
      '#maxlength' => 64,
      '#size' => 64,
      '#weight' => '0',
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['description'] = $form_state->getValue('description');
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];

    // Get the current node object
    $node = \Drupal::routeMatch()->getParameter('node');
    if ($node instanceof Node) {
      $build = [
        '#theme' => 'my_maps',
        '#description' => $this->configuration['description'],
        '#attached' => array(
          'library' => array(
            'my_maps/custom_map',
          ),
          'drupalSettings' => array(
            // Return the first Geofield value
            'geofield' => $node->get('field_geofield')->getValue()[0],
            'title' => $node->getTitle(),
          ),
        ),
      ];
    }

    return $build;
  }

}
