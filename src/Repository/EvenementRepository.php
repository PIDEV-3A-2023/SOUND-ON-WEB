<?php

namespace App\Repository;

use App\Entity\Evenement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Evenement>
 *
 * @method Evenement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Evenement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Evenement[]    findAll()
 * @method Evenement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EvenementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Evenement::class);
    }

    public function save(Evenement $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Evenement $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Evenement[] Returns an array of Evenement objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Evenement
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
// trier par nom d'utilisateur
public function sortBytitre() {
    return $this->createQueryBuilder('e')
        ->orderBy('e.titre', 'ASC')
        ->getQuery()
        ->getResult();
}


// trier par nombre de votes
public function sortBydate() {
    return $this->createQueryBuilder('e')
        ->orderBy('e.dateEvenement', 'DESC')
        ->getQuery()
        ->getResult();
}
public function sortByadresse() {
    return $this->createQueryBuilder('e')
        ->orderBy('e.adresse', 'ASC')
        ->getQuery()
        ->getResult();
}



// rechercher par nom d'utilisateur
public function findBytitre($titre) {
    return $this->createQueryBuilder('e')
        ->where('e.titre LIKE :titre')
        ->setParameter('titre', '%'.$titre.'%')
        ->getQuery()
        ->getResult();
}

// rechercher par nombre de votes
public function findBydate($date) {
    return $this->createQueryBuilder('e')
        ->where('e.dateEvenement = :date')
        ->setParameter('date', $date)
        ->getQuery()
        ->getResult();
}
public function findByadresse($adresse) {
    return $this->createQueryBuilder('e')
        ->where('e.adresse LIKE :adresse')
        ->setParameter('adresse', '%'.$adresse.'%')
        ->getQuery()
        ->getResult();
}


public function advancedSearch($titre, $dateEvenement,$adresse,$id)
{
    $qb = $this->createQueryBuilder('e');

    if ($titre) {
        $qb->andWhere('e.titre LIKE :titre')
            ->setParameter('titre', '%'.$titre.'%');
    }

    if ($dateEvenement) {
        $qb->andWhere('e.dateEvenement = :dateEvenement')
            ->setParameter('dateEvenement', $dateEvenement);
    }
    if ($adresse) {
        $qb->andWhere('e.adresse = :adresse')
            ->setParameter('adresse', $adresse);
    }
    if ($id) {
        $qb->andWhere('e.id = :id')
            ->setParameter('id', $id);
    }


    return $qb->getQuery()->getResult();
}


}
