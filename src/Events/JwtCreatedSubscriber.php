<?php 

namespace App\Events;

use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;

class JwtCreatedSubscriber {

    public function updateJwtData(JWTCreatedEvent $event){

        $user = $event->getUser();

        $data = $event->getData();

        $data['pseudonym'] = $user->getPseudonym();
        
        $data['user'] = $user->getId();

        $event->setData($data);

    }

}