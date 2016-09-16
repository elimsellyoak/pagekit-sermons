<?php


namespace beejjacobs\Sermons\Controller;

/**
 * Class SeriesController
 * @package beejjacobs\Sermons\Controller
 * @Access(admin=true)
 * @Route("/series", name="series", methods="GET")
 */
class SeriesController {
  /**
   * @return array
   */
  public function indexAction() {
    return [
        '$view' => [
            'title' => 'Sermon Series',
            'name' => 'beejjacobs/sermons/views/admin/series.php'
        ],
        'message' => 'Series....'
    ];
  }
}