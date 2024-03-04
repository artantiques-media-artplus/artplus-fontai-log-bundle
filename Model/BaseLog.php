<?php
namespace Fontai\Bundle\LogBundle\Model;

use App\Model;
use Propel\Runtime\Connection\ConnectionInterface;


abstract class BaseLog
{
  protected $modelTranslated;

  public function __construct()
  {
  }

  public function getDataArray()
  {
    $data = json_decode($this->getData(), TRUE);

    foreach ($data as &$values)
    {
      if (isset($values[0][0]['date']) && isset($values[0][0]['timezone']))
      {
        $values[0][0] = new \DateTime($values[0][0]['date'], new \DateTimeZone($values[0][0]['timezone']));
      }

      if (isset($values[1][0]['date']) && isset($values[1][0]['timezone']))
      {
        $values[1][1] = new \DateTime($values[1][0]['date'], new \DateTimeZone($values[1][0]['timezone']));
      }
    }

    return $data;
  }

  public function getModelTranslated()
  {
    if ($this->modelTranslated === NULL)
    {
      $permissionModule = Model\PermissionModuleQuery::create()->findOneByName(str_replace('App\Model', 'FontaiGenerator', $this->getModel()));
      
      if ($permissionModule)
      {
        $this->modelTranslated = $permissionModule->__toString();
      }
    }

    return $this->modelTranslated;
  }

  public function getModuleForm(string $culture)
  {
    global $kernel;

    $container = $kernel->getContainer();
    $formFactory = $container->get('form.factory');
    $tokenStorage = $container->get('security.token_storage');

    $formClassName = sprintf(
      '\App\Form\FontaiGenerator\Edit\%sType',
      str_replace('App\Model\\', '', $this->getModel())
    );

    $modelClassName = $this->getModel();

    return $formFactory->create($formClassName, new $modelClassName(), [
      'culture' => $culture,
      'settings' => $container->get('settings'),
      'user' => $tokenStorage->getToken()->getUser()
    ]);
  }
}