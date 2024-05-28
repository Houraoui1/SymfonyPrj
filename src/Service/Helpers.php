<?php
namespace App\Service;

use App\Entity\User;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\SecurityBundle\Security as SecurityBundleSecurity;
use Symfony\Component\Security\Core\Security;

class Helpers
{
    private $security;
  public function __construct(LoggerInterface $logger , SecurityBundleSecurity $security){

  }
  public function getUsers():User {

    return $this->security->getUser();


  }


}
    

