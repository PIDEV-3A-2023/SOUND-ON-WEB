<?php

namespace App\Repository;

use App\Entity\Produit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Produit>
 *
 * @method Produit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Produit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Produit[]    findAll()
 * @method Produit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProduitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Produit::class);
    }

    public function save(Produit $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Produit $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findByID($value): ?Produit
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.id = :val')
           ->setParameter('val', $value)
            ->getQuery()
          ->getOneOrNullResult()
        
        ;
    } 

//    /**
//     * @return Produit[] Returns an array of Produit objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Produit
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
// trier par nom d'utilisateur
public function sortByprix() {
    return $this->createQueryBuilder('e')
        ->orderBy('e.prix', 'ASC')
        ->getQuery()
        ->getResult();
}

// trier par nombre de votes
public function sortByquantite() {
    return $this->createQueryBuilder('e')
        ->orderBy('e.quantite', 'DESC')
        ->getQuery()
        ->getResult();
}
public function sortBylibelle() {
    return $this->createQueryBuilder('e')
        ->orderBy('e.libelle', 'ASC')
        ->getQuery()
        ->getResult();
}
}
