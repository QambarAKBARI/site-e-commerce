<?php

namespace App\Repository;

use App\Entity\Produit;
use App\Service\Search\SearchService;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;



/**
 * @method Produit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Produit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Produit[]    findAll()
 * @method Produit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProduitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, Produit::class);
        $this->paginator = $paginator;
    }




    /**
     * Récupère les produits en lien avec une recherche
     * @return PaginationInterface
     */
    public function findSearch(SearchService $search): PaginationInterface
    {

        $query = $this
            ->createQueryBuilder('p')
            ->select('p', 'm')
            ->join('p.marque', 'm');

        if (!empty($search->q)) {
            $query = $query
                ->andWhere('p.nomProduit LIKE :q')
                ->setParameter('q', "%{$search->q}%");
        }

        if (!empty($search->min)) {
            $query = $query
                ->andWhere('p.prix >= :min')
                ->setParameter('min', $search->min);
        }

        if (!empty($search->max)) {
            $query = $query
                ->andWhere('p.prix <= :max')
                ->setParameter('max', $search->max);
        }


        if (!empty($search->marques)) {
            $query = $query
                ->andWhere('m.id IN (:marque)')
                ->setParameter('marque', $search->marques);
        }

        return $this->paginator->paginate(
            $query,
            $search->page,
            9
        );
    }


    /**
     * Récupère les produits en lien avec une recherche
     * @return PaginationInterface
     */
    public function findAllProducts(): PaginationInterface
    {
        $query = $this->findAll();
        return $this->paginator->paginate(
            $query,
            9
        );
    }

    // /**
    //  * @return Produit[] Returns an array of Produit objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Produit
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
