<?php

namespace App\Repository;

use App\Data\FindData;
use App\Entity\Travel;
use App\Service\Search;
use Couchbase\SearchMetaData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use http\QueryString;
use Symfony\Component\Validator\Constraints\DateTime;
use function Doctrine\ORM\QueryBuilder;

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

    /**
     * Récuperer les Travels en lien avec une recherche
     * @return Paginator
     */
    public function findSearchTravel(FindData $findData): Paginator
    {
        $query = $this->createQueryBuilder('s')
            ->join('s.campusOrganiser', 'c')
            ->join('s.leader', 'o')
            ->join('s.subscriptionedTravelers', 'st')
            ->addSelect('c')
            ->addSelect('o');

        if (!empty($findData->campusToSearchTravel)) {
            $query = $query
                ->andWhere('c.id = ' . $findData->campusToSearchTravel->getId());

        }

        if (!empty($findData->travelByName)) {
            $query = $query
                ->andWhere('s.name LIKE :n')
                ->setParameter('n', "%{$findData->travelByName}%");

        }
        if (!empty($findData->statusId)) {
            $query = $query
                ->andWhere('s.status = 5');
        }

        if ($findData->leaderTravel) {
            $query = $query
                ->andWhere('s.leader = ' . $findData->userConnected->getId());
        }

        if ($findData->travelsSubscripted) {
            $query->andWhere('st.id = ' . $findData->userConnected->getId());

        }
        if ($findData->travelsNotSubscripted) {
           $query->andWhere(':user NOT MEMBER OF s.subscriptionedTravelers')
               ->setParameter('user', $findData->userConnected);

         //dd($query->getQuery()->getSQL());
        }


        //$query->addOrderBy('s.dateStart', 'DESC');
        $requete = $query->getQuery();
        $requete->setMaxResults(20);

        return new Paginator($requete);
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
