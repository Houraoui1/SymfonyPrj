<?php

namespace App\EventListener;

use App\Event\AddPersonneEvent;
use Psr\Log\LoggerInterface;

class PersonneListener
{
    public function __construct(private LoggerInterface $logger){

    }
    public function onAddPersonneListener(AddPersonneEvent $event)
    {
        $this->logger->debug("cc je suis en train d'écouter l'événement lors de l'ajout d'une personne.add et ete bien ajouter avec success " . " ". $event->getPersonne()->getName());
        
    }
}