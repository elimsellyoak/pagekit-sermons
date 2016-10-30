<?php
namespace beejjacobs\Sermons\Controller;


use beejjacobs\Sermons\Model\Sermon;
use Pagekit\Application;

/**
 * Class SermonApiController
 * @package beejjacobs\Sermons\Controller
 * @Route("sermon", name="sermon")
 */
class SermonApiController {

  /**
   * @Route("/", methods="GET")
   * @Request({"filter": "array", "page":"int"})
   * @param array $filter
   * @param int $page
   * @return mixed
   */
  public function indexAction($filter = [], $page = 0) {
    $query = Sermon::query();
    $filter = array_merge(array_fill_keys(['search', 'preacher', 'order', 'limit'], ''), $filter);

    extract($filter, EXTR_SKIP);

    if (!preg_match('/^(date|title)\s(asc|desc)$/i', $order, $order)) {
      $order = [1 => 'date', 2 => 'desc'];
    }

    $limit = (int) $limit ?: Application::module('sermons')->config('sermons.sermons_per_page');
    $count = $query->count();
    $pages = ceil($count / $limit);
    $page  = max(0, min($pages - 1, $page));

    $sermons = array_values($query->offset($page * $limit)->related(Sermon::RELATED)->limit($limit)->orderBy($order[1], $order[2])->get());

    return compact('sermons', 'pages', 'count');
  }

  /**
   * @Route("/{id}", methods="GET", requirements={"id"="\d+"})
   * @param $id
   * @return mixed
   */
  public function getAction($id) {
    return Sermon::where(compact('id'))->related(Sermon::RELATED)->first();
  }

  /**
   * @Route("/", methods="POST")
   * @Route("/{id}", methods="POST", requirements={"id"="\d+"})
   * @Request({"post": "array", "id": "int"}, csrf=true)
   * @param $data
   * @param int $id
   * @return array
   */
  public function saveAction($data, $id = 0) {
    if (!$id || !$sermon = Sermon::find($id)) {
      if ($id) {
        Application::abort(404, __('Post not found.'));
      }

      $sermon = Sermon::create();
    }

    if (!Application::user()->hasAccess('sermons: manage sermons')) {
      Application::abort(400, __('Access denied.'));
    }

    $sermon->save($data);

    return ['message' => 'success', 'sermon' => $sermon];
  }

  /**
   * @Route("/{id}", methods="DELETE", requirements={"id"="\d+"})
   * @Request({"id": "int"}, csrf=true)
   * @param $id
   * @return array
   */
  public function deleteAction($id) {
    if ($sermon = Sermon::find($id)) {

      if (!Application::user()->hasAccess('sermons: manage sermons')) {
        Application::abort(400, __('Access denied.'));
      }

      $sermon->delete();
    }

    return ['message' => 'success'];
  }

  /**
   * @Route(methods="POST")
   * @Request({"ids": "int[]"}, csrf=true)
   * @param array $ids
   * @return array
   */
  public function copyAction($ids = []) {
    foreach ($ids as $id) {
      if ($sermon = Sermon::find((int) $id)) {
        if(!Application::user()->hasAccess('sermons: manage sermons')) {
          continue;
        }

        $sermon = clone $sermon;
        $sermon->id = null;
        $sermon->title = $sermon->title.' - '.__('Copy');
        $sermon->date = new \DateTime();
        $sermon->save();
      }
    }

    return ['message' => 'success'];
  }

  /**
   * @Route("/bulk", methods="POST")
   * @Request({"posts": "array"}, csrf=true)
   * @param array $sermons
   * @return array
   */
  public function bulkSaveAction($sermons = []) {
    foreach ($sermons as $data) {
      $this->saveAction($data, isset($data['id']) ? $data['id'] : 0);
    }

    return ['message' => 'success'];
  }

  /**
   * @Route("/bulk", methods="DELETE")
   * @Request({"ids": "array"}, csrf=true)
   * @param array $ids
   * @return array
   */
  public function bulkDeleteAction($ids = []) {
    foreach (array_filter($ids) as $id) {
      $this->deleteAction($id);
    }

    return ['message' => 'success'];
  }

}
