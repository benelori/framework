<?php

namespace Simplex\Calendar\Controller;

use Simplex\Calendar\Service\LeapYearManager;
use Simplex\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LeapYearController extends ControllerBase {

  /**
   * @var \Simplex\Calendar\Service\LeapYearManager
   */
  protected $leapYearManager;

  public function __construct(LeapYearManager $leapYearManager) {
    $this->leapYearManager = $leapYearManager;
  }

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('calendar.leap_year_manager')
    );
  }
  
  public function indexAction($year) {
    if ($this->leapYearManager->isLeapYear($year)) {
      return 'Yep, this is a leap year!';
    }

    return 'Nope, this is not a leap year.';
  }
  
}
