<?php

namespace beejjacobs\Sermons\Controller;

use beejjacobs\Sermons\Model\Sermon;

/**
 * @Access(admin=true)
 */
class SermonsController {
  public function indexAction() {
    return [
        '$view' => [
            'title' => 'Sermons',
            'name' => 'beejjacobs/sermons/views/admin/sermons.php'
        ],
        'sermons' => Sermon::findAll()
    ];
  }
}