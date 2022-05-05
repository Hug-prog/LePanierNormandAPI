<?php
namespace App\Listeners;

use DateTime;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Symfony\Component\HttpFoundation\Cookie;

class AuthenticationSuccessListener
{
    private $secure = false;
    private $tokenTtl;
    public function __construct($tokenTtl)
    {
        $this->tokenTtl = $tokenTtl;
    }

    public function onAuthenticationSuccess(AuthenticationSuccessEvent $event)
    {
        $response = $event->getResponse();
        $data = $event->getData();
        
        $token=$data['token'];
        //$refresh_token=$data['refresh_token'];

        $data['code'] = 200;
        $data['message'] = 'Request successful';


        $response->headers->setcookie(
            new Cookie('BEARER', $token,
                (new \DateTime())
                ->add(new \DateInterval('PT' . $this->tokenTtl . 'S')), '/', null, $this->secure, true, false, 'none')
        );
        /*$response->headers->setcookie(
            new Cookie('refresh_token', $refresh_token,
                (new \DateTime())
                ->add(new \DateInterval('PT' .'56000'. 'S')), '/', null, $this->secure, true, false, 'none')
        );*/
    }
}