<?php

namespace beejjacobs\Sermons\Model;

use Pagekit\Database\ORM\ModelTrait;

/**
 * Class Series
 * @package beejjacobs\Sermons\Model
 * @Entity(tableClass="@sermons_series")
 */
class Series {

  use ModelTrait;

  const SERMONS = 'sermons';

  /**
   * @Column(type="integer")
   * @Id
   */
  public $id;

  /**
   * @Column(type="string")
   */
  public $name;

  /**
   * @HasMany(targetEntity="beejjacobs\Sermons\Model\Sermon", keyFrom="id", keyTo="sermon_series_id")
   */
  public $sermons;

}