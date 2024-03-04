<?php
namespace Fontai\Bundle\LogBundle\Service;

use App\Model;
use Propel\Runtime\ActiveQuery\Criteria;


class Log
{
  public function createEvent(
    $object,
    $admin,
    array $data,
    string $description = NULL
  )
  {
    $log = new Model\Log();

    return $log
    ->setModel(get_class($object))
    ->setObjectId($object->getId())
    ->setAdmin($admin)
    ->setData(json_encode($data))
    ->setDescription($description)
    ->save();
  }

  public function createEventCreate(
    $object,
    $admin
  )
  {
    $log = new Model\Log();
  
    return $log
    ->setModel(get_class($object))
    ->setObjectId($object->getId())
    ->setAdmin($admin)
    ->setCreated(TRUE)
    ->save();
  }
  
  public function createEventArchivate(
    $object,
    $admin
  )
  {
    $log = new Model\Log();
  
    return $log
    ->setModel(get_class($object))
    ->setObjectId($object->getId())
    ->setAdmin($admin)
    ->setArchived(TRUE)
    ->save();
  }

  public function getEvents(
    string $model,
    int $id,
    int $offset = 0,
    int $limit = 20
  )
  {
    return Model\LogQuery::create()
    ->filterByModel($model)
    ->filterByObjectId($id)
    ->orderByCreatedAt(Criteria::DESC)
    ->offset($offset)
    ->limit($limit)
    ->find();
  }
}