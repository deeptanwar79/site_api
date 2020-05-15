<?php

namespace Drupal\site_api\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Serializer;

/**
 * Controller for page content type json endpoint.
 */
class PageJsonController extends ControllerBase {
  
  /**
   * $serializer
   *
   * @var Symfony\Component\Serializer\Serializer
   */
  protected $serializer;
  
  /**
   * Constructs a new PageJsonController.
   *
   * @param Symfony\Component\Serializer\Serializer $serializer
   *   The serializer service.
   */
  public function __construct(Serializer $serializer) {
    $this->serializer = $serializer;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('serializer')
    );
  }
  
  /**
   * Returns JSON representation for page content type
   *
   * @param string $siteapikey
   *   Site API key to access the endpoint.
   * @param int $node_id
   *   Node id of page.
   * @return Symfony\Component\HttpFoundation\JsonResponse
   *   Return json response when endpoint is accessed
   */
  public function getPageJson($siteapikey, $node_id) {
    if (\Drupal::config('system.site')->get('siteapikey') != $siteapikey) {
      return new JsonResponse('accesss denied', 401);
    }
    $node = \Drupal::entityTypeManager()->getStorage('node')->load($node_id);
    if(empty($node) || $node->getType() != 'page') {
      return new JsonResponse('accesss denied', 401);
    }
    $return =  $this->serializer->normalize($node);
    return new JsonResponse($return);
  }
  
}
