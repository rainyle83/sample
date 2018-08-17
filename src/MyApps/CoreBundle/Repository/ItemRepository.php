<?php

namespace MyApps\CoreBundle\Repository;
use MyApps\CoreBundle\Utilities\Constant;

/**
 * ItemRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ItemRepository extends \Doctrine\ORM\EntityRepository
{
    public function getItem($id) {
        $item = $this->find($id);
        if(!$item) {
            return [];
        }
        return $this->_parseEntity($item);
    }

    /**
     * @param Item $entity
     */
    protected function _parseEntity($entity) {
        return [
            'id' => $entity->getId(),
            'name' => $entity->getName(),
            'description' => $entity->getDescription()
        ];
    }

    public function listItems($params, $returnTotal = false) {
        $pageNumber = array_key_exists('page', $params) && $params['page'] ? $params['page'] : 1;
        $pageSize = array_key_exists('page_size', $params) && $params['page_size'] ? $params['page_size'] : Constant::PAGE_SIZE;

        $qb = $this->createQueryBuilder('i');
        $qb
//            ->innerJoin('i.brands', 'b')
//            ->leftJoin('i.role', 'r')
//            ->where($qb->expr()->eq('i.id', ':isId'))
            ->orderBy('i.id', 'DESC')
        ;
//        $parameters['isId'] = 3;
//        $qb->setParameters($parameters);

        if ($returnTotal) {
            $qb = $qb->select('COUNT(i)');
        } else {
            $qb = $qb->select('i')
                ->setFirstResult(($pageNumber-1) * $pageSize)
                ->setMaxResults($pageSize)
            ;
        }

        return $qb->getQuery();
    }
}