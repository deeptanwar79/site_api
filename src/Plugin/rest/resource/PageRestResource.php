<?php

namespace Drupal\site_api\Plugin\rest\resource;

use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ModifiedResourceResponse;

/**
 * Provides a resource to get representation of page content type.
 *
 * @RestResource(
 *   id = "siteapi_page",
 *   label = @Translation("Site Api Page Resource"),
 *   uri_paths = {
 *     "canonical" = "/api/page/{siteapikey}/{node_id}"
 *   }
 * )
 */
class PageRestResource extends ResourceBase {
 
  /**
   * Responds to get request for page content.
   *
   * @param string $siteapikey
   *   Site API key to access the endpoint.
   * @param int $node_id
   *   Node id of page.
   * @return \Drupal\rest\ModifiedResourceResponse
   *   Return modified resource when endpoint is accessed.
   */
  public function get($siteapikey, $node_id) {
    if (\Drupal::config('system.site')->get('siteapikey') != $siteapikey) {
      return new ModifiedResourceResponse('access denied', 401);
    }
    $node = \Drupal::entityTypeManager()->getStorage('node')->load($node_id);
    if(empty($node) || $node->getType() != 'page') {
      return new ModifiedResourceResponse('access denied', 401);
    }
    $response = new ModifiedResourceResponse($node);
    return $response;
  }
}
