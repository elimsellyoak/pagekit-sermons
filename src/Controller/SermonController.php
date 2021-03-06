<?php

namespace beejjacobs\Sermons\Controller;
use beejjacobs\Sermons\Model\BibleBook;
use beejjacobs\Sermons\Model\Preacher;
use beejjacobs\Sermons\Model\Series;
use beejjacobs\Sermons\Model\Sermon;
use beejjacobs\Sermons\Model\Topic;
use Pagekit\Application;

/**
 * Class SermonController
 * @package beejjacobs\Sermons\Controller
 * @Access(admin=true)
 * @Route("/sermon", name="sermon")
 */
class SermonController {
  /**
   * @Route("/edit", name="edit")
   * @Request({"id": "int"})
   * @param int $id
   * @return array
   */
  public function editAction($id = 0) {
    try {

      if (!$sermon = Sermon::where(compact('id'))->related(Sermon::RELATED)->first()) {

        if ($id) {
          Application::abort(404, __('Invalid sermon id.'));
        }
        $sermon = Sermon::create(['date' => new \DateTime('11:00')]);
      }

      $user = Application::user();
      if(!$user->hasAccess('sermons: manage sermons')) {
        Application::abort(403, __('Insufficient User Rights.'));
      }

      if (!is_null($sermon->bible_books)) {
        $sermon->bible_books = array_values($sermon->bible_books);
      }
      if (!is_null($sermon->topics)) {
        $sermon->topics = array_values($sermon->topics);
      }

      return [
          '$view' => [
              'title' => $id ? __('Edit Sermon') : __('Add Sermon'),
              'name'  => 'sermons/admin/sermon-edit.php'
          ],
          '$data' => [
              'sermon'     => $sermon,
              'statuses' => Sermon::getStatuses(),
              'preachers' => array_values(Preacher::findAll()),
              'series' => array_values(Series::findAll()),
              'topics' => array_values(Topic::findAll()),
              'bible_books' => array_values(BibleBook::findAll())
          ]
      ];

    } catch (\Exception $e) {

      Application::message()->error($e->getMessage());

      return Application::redirect('@sermons/sermon');
    }
  }
}
