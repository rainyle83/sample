<?php

namespace MyApps\ApiBundle\Controller;

use JMS\Serializer\SerializationContext;
use MyApps\CoreBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BaseController extends Controller
{
    protected function processPostData(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        if ($data === null) {
            $data = [];
        }
        return $data;
    }

    protected function setBaseHeaders(Response $response)
    {
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');
        return $response;
    }

    protected  function serialize($data, $format = 'json') {
        $context = new SerializationContext();
        $context->setSerializeNull(true);

        return $this->get('jms_serializer')->serialize($data, $format);
    }

    protected function getToken(User $user)
    {
        $expiredAt = $this->getTokenExpiryDateTime();
        $token = $this->container->get('lexik_jwt_authentication.encoder')
            ->encode([
                'username' => $user->getUsername(),
                'exp' => $expiredAt,
            ]);

        return array('token' => $token, 'expired_at' => $expiredAt);
    }

    protected function getTokenExpiryDateTime()
    {
        $tokenTtl = $this->container->getParameter('lexik_jwt_authentication.token_ttl');
        $now = new \DateTime();
        $now->add(new \DateInterval('PT'.$tokenTtl.'S'));

        return $now->format('U');
    }
}
