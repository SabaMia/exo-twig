<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    public function searchByTerm($term)
    {
        //Création de la requête pour la fonction recherche
        $queryBuilder = $this->createQueryBuilder('article');
        $query = $queryBuilder
            ->select('article')

            ->leftJoin('article.category', 'category')
            ->leftJoin('article.tag', 'tag')

            ->where('article.content LIKE :term')
            ->orWhere('article.title LIKE :term')
            ->orWhere('category.title LIKE :term')
            ->orWhere('tag.title LIKE :term')

            ->setParameter('term', '%'.$term.'%')
            //setParameter permet de sécuriser la recherche en évitant les injections SQl extérieures
            // et vérifier ce qui a été tapé avant de valider la requête
            ->getQuery();
        return $query->getResult();
        //ensuite il faut aller personnaliser le fichier twig pour afficher le résultat de la recheche
    }

    // /**
    //  * @return Article[] Returns an array of Article objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Article
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
