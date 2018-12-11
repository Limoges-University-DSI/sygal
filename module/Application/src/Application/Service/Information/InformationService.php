<?php

namespace Application\Service\Information;

use Application\Entity\Db\Information;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;

class InformationService {
    use EntityManagerAwareTrait;

    /**
     * @return Information[]
     */
    public function getInformations()
    {
        $qb = $this->getEntityManager()->getRepository(Information::class)->createQueryBuilder('information')
        ;

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param integer $id
     * @return Information
     */
    public function getInformation($id)
    {
        $qb = $this->getEntityManager()->getRepository(Information::class)->createQueryBuilder('information')
            ->andWhere('information.id = :id')
            ->setParameter('id', $id)
        ;

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs Information partagent le même identifiant [".$id."]", $e);
        }
        return $result;
    }

    /**
     * @param Information $information
     * @return Information
     */
    public function create($information)
    {
        //TODO set createur et modificateur
        $this->getEntityManager()->persist($information);
        try {
            $this->getEntityManager()->flush($information);
        } catch (OptimisticLockException $e) {
            throw new RuntimeException("Problème lors de la création en base d'une Information", $e);
        }
        return $information;
    }

    /**
     * @param Information $information
     * @return Information
     */
    public function update($information)
    {
        //TODO set modificateur
        try {
            $this->getEntityManager()->flush($information);
        } catch (OptimisticLockException $e) {
            throw new RuntimeException("Problème lors de la mise à jour en base d'une Information", $e);
        }
        return $information;
    }

    /**
     * @param Information $information
     */
    public function delete($information)
    {
        $this->getEntityManager()->remove($information);
        try {
            $this->getEntityManager()->flush();
        } catch (OptimisticLockException $e) {
            throw new RuntimeException("Problème lors de la destruction en base d'une Information", $e);
        }
    }
}