<?php

namespace MyApps\ApiBundle\Controller;

use MyApps\ApiBundle\Utilities\API;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends BaseController
{
    public function createAction()
    {

    }

    public function loginAction(Request $request)
    {
        $postUser = $this->processPostData($request);

        if (!array_key_exists('username', $postUser) || !$postUser['username']
            || !array_key_exists('password', $postUser) || !$postUser['password']) {
            $dataResponse = array(
                'status' => API::MISSING_FIELDS,
                'error' => 'Require user name and password!',
                'data' => null
            );
            $response = new Response($this->serialize($dataResponse), API::HTTP_BAD_REQUEST);

            return $this->setBaseHeaders($response);
        }

        $username = $postUser['username'];
        $password = $postUser['password'];

        $userRepository = $this->getDoctrine()->getRepository('MyAppsCoreBundle:User');

        $user = $userRepository->findOneBy(['username' => $username]);

        if (!$user || $user->isEnabled() != 1) {
            $dataResponse = array(
                'status' => API::NOT_FOUND,
                'error' => 'User not found',
                'data' => null
            );
            $response = new Response($this->serialize($dataResponse), API::HTTP_NOT_FOUND);

            return $this->setBaseHeaders($response);
        }

        $isValid = $this->get('security.password_encoder')
            ->isPasswordValid($user, $password);

        if (!$isValid) {
            $dataResponse = array(
                'status' => API::INCORRECT_PASSWORD,
                'error' => 'The password is incorrect!',
                'data' => null
            );
            $response = new Response($this->serialize($dataResponse), API::HTTP_BAD_REQUEST);

            return $this->setBaseHeaders($response);
        }

        $token = $this->getToken($user);

        $userInfo = $userRepository
            ->_parseRetailerUserProfiles($user, $user)
            //->_parseRetailerUserProfiles($user, $user->getRetailer())
        ;

        $dataResponse = array(
            'status' => API::HTTP_OK,
            'error' => null,
            'data' => array('user' => $userInfo, 'token' => $token)
        );
        $response = new Response($this->serialize($dataResponse), API::HTTP_OK);

        return $this->setBaseHeaders($response);
    }

}
