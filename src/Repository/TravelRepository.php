<?php

namespace App\Repository;

use App\Entity\Travel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * @extends ServiceEntityRepository<Travel>
 *
 * @method Travel|null find($id, $lockMode = null, $lockVersion = null)
 * @method Travel|null findOneBy(array $criteria, array $orderBy = null)
 * @method Travel[]    findAll()
 * @method Travel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TravelRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Travel::class);
    }

    public function save(Travel $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Travel $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


    public function findByNonClos(): array
    {
        $today = date("Y-m-d H:i:s ");
        $timestamp = strtotime($today);
        $nextMonth = strtotime("last month", $timestamp);
        $nextMonthDate = date("Y-m-d H:i:s", $nextMonth);
        return $this->createQueryBuilder('s')
            ->andWhere("s.date_start >= :monthPlusOne")
            ->setParameter('monthPlusOne', $nextMonthDate)
            ->orderBy('s.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findByNonClosCampus($id): array
    {
        $today = date("Y-m-d H:i:s ");
        $timestamp = strtotime($today);
        $nextMonth = strtotime("last month", $timestamp);
        $nextMonthDate = date("Y-m-d H:i:s", $nextMonth);
        return $this->createQueryBuilder('s')
            ->innerJoin('s.leader', 'o')
            ->andWhere("s.dateStart >= :monthPlusOne")
            ->setParameter('monthPlusOne', $nextMonthDate)
            ->andWhere('o.campus = :campus')
            ->setParameter('campus', $id)
            ->orderBy('s.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findByResearch($param, $user): array
    {
        //initiation des date
        $date = new DateTime();
        $today = date("Y-m-d H:i:s ");
        $timestamp = strtotime($today);
        $nextMonth = strtotime("lastMonth", $timestamp);
        $nextMonthDate = date("Y-m-d H:i:s", $nextMonth);
        $query = $this->createQueryBuilder('s');

        //recherche par nom
        if ($param->get("litteration") != "") {
            $query->innerJoin('s.leader', 's');
            $query->andWhere('o.campus = :camp');
            $query->setParameter('campus', $param->get("litteration"));
        }

        // recherche par nom
        if ($param->get("nameTravelRecherch") != "") {
            $query->andWhere("s.name LIKE :name_travel");
            $query->setParameter('name_travel', "%" . $param->get("nameTravelRecherch") . "%");
        }

        //recherche par date debut
        if ($param->get("dateStart") != "") {
            $query->andWhere("s.dateStart >= :dateFirs ");
            $query->setParameter('dateStart', $param->get("dateStart"));

        }
        //recherche par date fin
        if ($param->get("dateEnd") != "") {
            $query->andWhere("s.dateStart <= :dateEnd");
            $query->setParameter('dateEnd', $param->get("dateEnd"));
        }
        //recherche par leader
        if ($param->get("leaderTravel") != null) {
            $query->andWhere("s.leader = :lead");
            $query->setParameter('lead', $user->getId());
        }

        //recherche par inscription
        if ($param->get("travelSubscri") != null || $param->get("travelDontSubscri") != null) {

            if ($param->get("travelSubscri") != null && $param->get("travelDontSubscri") == null) {
                $query->innerJoin('s.subscri', 'sub');
                $query->andWhere('i.participate = :participate');
                $query->setParameter('participate', $user->getId());
            } else {
                if ($param->get("travelSubscri") == null && $param->get("travelDontSubscri") != null) {
                    $query->leftJoin('s.subscri', 'sub');
                    $query->andWhere('s.participate != :participate or i.participate is null');
                    $query->setParameter('participate', $user->getId());
                }
            }
        }

        //recherche des travel passée
        if ($param->get("travelEnd") != null) {
            $query->andWhere("s.dateFirs < :ended ");
            $query->setParameter('ended', $date);
        }

        $query->andWhere("s.dateFirs >= :monthPlusOne");
        $query->setParameter('monthPlusOne', $nextMonthDate);
        $query->orderBy('s.dateFirs', 'ASC');
        $requete = $query->getQuery();

        return $requete->getResult();
    }




//    /**
//     * @return Travel[] Returns an array of Travel objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Travel
//{
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
