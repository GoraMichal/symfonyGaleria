<?php

namespace App\EntityListener;

use App\Entity\Conference;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\String\Slugger\SluggerInterface;

class ConferenceEntityListener
{
    private $slugger;

    //event listener, gdy dane zostaną zmienione to zmienia się url (w tym przypadku)
    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    private function  prePersist(Conference $conference, LifecycleEventArgs $event)
    {
        $conference->computeSlug($this->slugger);
    }

    private function preUpdate(Conference  $conference, LifecycleEventArgs $event)
    {
        $conference->computeSlug($this->slugger);
    }

}
