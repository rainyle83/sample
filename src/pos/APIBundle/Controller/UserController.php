<?php

namespace pos\APIBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use pos\APIBundle\Utilities\API;

class UserController extends BaseController
{
    protected $_userRequireFields = array(
        'user_id',
        'shop_name',
        'contact_name',
        'contact_number',
        'address',
        'province',
        'password',
    );

    public function createAction(Request $request)
    {
        try {
            $userManager = $this->get('fos_user.user_manager');

            $postUser = $this->processPostData($request);

//            $countryCode = array_key_exists('country_code', $postUser) && $postUser['country_code'] != '' ? $postUser['country_code'] : 'vn';
//            $country = $this->getDoctrine()->getRepository('CoreBundle:Country')->findOneByCountryCode($countryCode);
//            if (!$country) {
//                return $this->responseJson(API::INVALID_DATA_INPUT, 'Country code is invalid!', null, API::HTTP_BAD_REQUEST);
//            }
//            $postUser['country_id'] = $country->getId();

            $missingFields = array();
            foreach ($this->_userRequireFields as $value) {
                if (!array_key_exists($value, $postUser) || !$postUser[$value]) {
                    $missingFields[] = $value;
                }
            }
            if (!empty($missingFields)) {
                $dataResponse = API::getData(API::MISSING_FIELDS, 'Missing fields: ' . implode(', ', $missingFields), null);

                return $this->responseJson($dataResponse, API::HTTP_BAD_REQUEST);
            }

            $postUser['is_enable'] = true;
            $result = $this->getDoctrine()->getRepository('CoreBundle:User')->createInAdvance($postUser, $userManager);

            if ($result['error'] != null) {
                $dataResponse = API::getData($result['status'], $result['error'], null);
                return $this->responseJson($dataResponse, API::HTTP_BAD_REQUEST);
            } else {
                $dataResponse = API::getData(HTTP_OK, nul, $result['data']);
                return $this->responseJson($dataResponse, API::HTTP_OK);
            }
        } catch (\Exception $exc) {
            $dataResponse = API::getData(API::INTERNAL_SERVER_ERROR, $exc->getMessage(), null);
            return $this->responseJson($dataResponse, API::HTTP_BAD_REQUEST);
        }
    }

    public function loginAction(Request $request)
    {
        $postUser = $this->processPostData($request);
        if (!array_key_exists('username', $postUser) || !$postUser['username']
            || !array_key_exists('password', $postUser) || !$postUser['password']) {
            $dataResponse = API::getData(API::MISSING_FIELDS, 'Require user name and password!', null);
            return $this->responseJson($dataResponse, API::HTTP_BAD_REQUEST);
        }
        $username = $postUser['username'];
        $password = $postUser['password'];

        $userRepository = $this->getDoctrine()->getRepository('CoreBundle:User');
        $user = $userRepository->findOneBy(['username' => $username]);
        if (!$user || $user->isEnabled() != 1) {
            $dataResponse = API::getData(API::NOT_FOUND, 'User not found', null);
            return $this->responseJson($dataResponse, API::HTTP_NOT_FOUND);
        }

        $isValid = $this->get('security.password_encoder')
            ->isPasswordValid($user, $password);
        if (!$isValid) {
            $dataResponse = API::getData(API::INCORRECT_PASSWORD, 'The password is incorrect!', null);
            return $this->responseJson($dataResponse, API::HTTP_BAD_REQUEST);
        }

        $token = $this->getToken($user);
        $userInfo = $userRepository->_parseRetailerUserProfiles($user);
        $data = array('user' => $userInfo, 'token' => $token);

        $dataResponse = API::getData(API::HTTP_OK, null, $data);

        return $this->responseJson($dataResponse, API::HTTP_OK);
    }
}
