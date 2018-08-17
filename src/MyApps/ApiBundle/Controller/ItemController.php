<?php

namespace MyApps\ApiBundle\Controller;

use MyApps\ApiBundle\Utilities\API;
use MyApps\CoreBundle\Utilities\Constant;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use MyApps\CoreBundle\Entity\Item;

class ItemController extends BaseController
{
    public function getListAction(Request $request)
    {
        try {
            $params = $request->query->all();
            $pageSize = $request->query->get('page_size', Constant::PAGE_SIZE);
            $userTableId = $request->query->get('user_id', 0);

            $repository = $this->getDoctrine()->getManager()->getRepository('MyAppsCoreBundle:Item');

            $items = $repository->listItems($params, false)->getResult();

            $totalItems = $repository->listItems($params, true)->getSingleScalarResult();

            $totalPage = ceil($totalItems * 1.0 / $pageSize);

            $dataResponse = array(
                'status' => 200,
                'error' => null,
                'data' => $items,
                'total_page' => $totalPage
            );
            $response = new Response($this->serialize($dataResponse), API::HTTP_OK);
        } catch (\Exception $exc) {
            $dataResponse = array(
                'status' => API::INTERNAL_SERVER_ERROR,
                'error' => $exc->getMessage(),
                'data' => null
            );
            $response = new Response($this->serialize($dataResponse), API::HTTP_BAD_REQUEST);
        }

        return $this->setBaseHeaders($response);
    }

    public function getAction(Request $request)
    {
        try {
            $id = $request->query->get('id', 0);
            $item = $this->getDoctrine()
                ->getManager()
                ->getRepository('MyAppsCoreBundle:Item')
                ->getItem($id);

            $dataResponse = array(
                'status' => 200,
                'error' => null,
                'data' => $item
            );
            $response = new Response($this->serialize($dataResponse), API::HTTP_OK);
        } catch (\Exception $exc) {
            $dataResponse = array(
                'status' => API::INTERNAL_SERVER_ERROR,
                'error' => $exc->getMessage(),
                'data' => null
            );
            $response = new Response($this->serialize($dataResponse), API::HTTP_BAD_REQUEST);
        }

        return $this->setBaseHeaders($response);
    }
}
