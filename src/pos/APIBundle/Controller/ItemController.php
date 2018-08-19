<?php

namespace pos\APIBundle\Controller;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use pos\APIBundle\Utilities\API;
use pos\CoreBundle\Utilities\Constant;
use pos\CoreBundle\Entity\Item;

class ItemController extends BaseController
{
    public function getListAction(Request $request)
    {
        try {
            $params = $request->query->all();
            $pageSize = $request->query->get('page_size', Constant::PAGE_SIZE);
            $userTableId = $request->query->get('user_id', 0);

            $repository = $this->getDoctrine()->getManager()->getRepository('CoreBundle:Item');

            $items = $repository->listItems($params, false)->getResult();

            $totalItems = $repository->listItems($params, true)->getSingleScalarResult();

            $totalPage = ceil($totalItems * 1.0 / $pageSize);

            $option = array('total_page' => $totalPage);
            $dataResponse = API::getData(API::HTTP_OK, null, $items, $option);

            return $this->responseJson($dataResponse, API::HTTP_OK);
        } catch (\Exception $exc) {
            return $this->responseJson(API::INTERNAL_SERVER_ERROR, $exc->getMessage(), null, API::HTTP_BAD_REQUEST);
        }
    }

    public function getAction(Request $request)
    {
        try {
            $id = $request->query->get('id', 0);
            $item = $this->getDoctrine()
                ->getManager()
                ->getRepository('CoreBundle:Item')
                ->getItem($id);

            $dataResponse = API::getData(API::HTTP_OK, null, $item);

            return $this->responseJson($dataResponse, API::HTTP_OK);
        } catch (\Exception $exc) {
            $dataResponse = API::getData(API::INTERNAL_SERVER_ERROR, $exc->getMessage(), null);
            return $this->responseJson($dataResponse, API::HTTP_BAD_REQUEST);
        }

    }
}
