<?php
namespace Fontai\Bundle\LogBundle\Twig;

use Fontai\Bundle\LogBundle\Service\Log;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;


class LogExtension extends AbstractExtension
{
  protected $log;

  public function __construct(Log $log)
  {
    $this->log = $log;
  }

  public function getFunctions()
  {
    return [
      new TwigFunction(
        'log',
        [$this, 'getLogList'],
        [
          'needs_environment' => TRUE,
          'is_safe'           => ['html']
        ]
      )
    ];
  }

  public function getLogList(
    \Twig_Environment $environment,
    string $model,
    int $id,
    array $form
  )
  {
    return $environment->render(
      '@Log/list.html.twig',
      [
        'logs' => $this->log->getEvents($model, $id),
        'form' => $form
      ]
    );
  }

  public function getName()
  {
    return 'log';
  }
}
