<?php

namespace AppBundle\EventListener;

// ...

use Avanzu\AdminThemeBundle\Event\ShowUserEvent;
use AppBundle\Model\UserModel;

class ShowUserListener {

    private $securityContext = null;

    public function __construct($securityContext) {
        $this->securityContext = $securityContext;
    }

    public function onShowUser(ShowUserEvent $event) {
        $user = $this->getUser();
        
        $userModel = new UserModel();
        $userModel->setAvatar('')->setUsername($user->getUsername())->setId($user->getId());
        $event->setUser($userModel);

    }

    protected function getUser() {
        return $this->securityContext->getToken()->getUser();
    }

}