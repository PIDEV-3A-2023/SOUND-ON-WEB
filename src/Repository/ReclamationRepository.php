<?php

namespace App\Repository;

use App\Entity\Reclamation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Reclamation>
 *
 * @method Reclamation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reclamation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reclamation[]    findAll()
 * @method Reclamation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReclamationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reclamation::class);
    }

    public function save(Reclamation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Reclamation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return Reclamation[] Returns an array of Reclamation objects
     */
    public function findByUtilisateur($value): array
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.idUser = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->getQuery()
           ->getResult()
       ;
    }

    public function findDate($type,$etat)
    {
        if($type== " " && $etat != " "){
            
            $query = $this->createQueryBuilder('a')
            ->andWhere('a.etat = :etat')
            ->andWhere('a.idUser = 1')
            ->setParameter('etat',$etat)
            ->getQuery();
        return $query->getResult();
        }else if($type == " " && $etat==" "){
            
            $query = $this->createQueryBuilder('a')
            ->andWhere('a.idUser = 1')
            ->getQuery();
        return $query->getResult();
        }
        else if($etat== " " && $type!= " "){
            
            $query = $this->createQueryBuilder('a')
            ->andWhere('a.type = :type')
            ->andWhere('a.idUser = 1')
            ->setParameter('type',$type)
            ->getQuery();
        return $query->getResult();
        }else{
            
            $query = $this->createQueryBuilder('a')
            ->andWhere('a.type = :type')
            ->andWhere('a.etat = :etat')
            ->andWhere('a.idUser = 1')
            ->setParameter('type',$type)
            ->setParameter('etat',$etat)
            ->getQuery();
        return $query->getResult();
        }
        
    }


    public function findReclamationByDescription($requestString)
    {
        $query = $this->createQueryBuilder('a')
            ->where('a.description LIKE :key')
            ->setParameter('key' , '%'.$requestString.'%')->getQuery();
        return $query->getResult();
    }

//    public function findOneBySomeField($value): ?Reclamation
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
