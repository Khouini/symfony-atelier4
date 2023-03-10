<?php

namespace App\Repository;

use App\Entity\Student;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Student>
 *
 * @method Student|null find($id, $lockMode = null, $lockVersion = null)
 * @method Student|null findOneBy(array $criteria, array $orderBy = null)
 * @method Student[]    findAll()
 * @method Student[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StudentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Student::class);
    }

    public function save(Student $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Student $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    //    /**
    //     * @return Student[] Returns an array of Student objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('s.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Student
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
    public function orderByEmail()
    {
        $req = $this->createQueryBuilder('s')
            ->orderBy("s.Email", 'ASC')
            ->setMaxResults(3)
            ->getQuery()
            ->getResult();
        return $req;
    }

    public function findByEmail($email)
    {
        $req = $this->createQueryBuilder('s')
            ->where("s.Email like :val")
            ->setParameter('val', "%" . $email . "%")->getQuery()
            ->getResult();
        return $req;
    }

    // public function findByEmail($value): array
    // {
    //     return $this->createQueryBuilder('s')
    //         ->andWhere('s.Email = :val')
    //         ->setParameter('val', $value)
    //         ->setMaxResults(10)
    //         ->getQuery()
    //         ->getResult();
    // }

    public function searchByAdmis()
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery('SELECT s FROM App\Entity\Student s WHERE s.moyenne > =10');
        return $query->getResult();
    }

    // public function searchByMoyenne($min, $max)
    // {
    //     $entityManager = $this->getEntityManager();
    //     $query = $entityManager->createQuery('SELECT s FROM App\Entity\Student s WHERE s.moyenne >=' . $min and 's.moyenne <=' . $max);
    //     return $query->getResult();
    // }

    public function findByMoyenne($min, $max)
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager
            ->createQuery('SELECT s FROM App\Entity\Student s WHERE s.moyenne between :min AND :max')->setParameters(['min' => $min, 'max' => $max]);
        return $query->getResult();
    }

    public function findByClassroomName($name): array
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager
            ->createQuery('SELECT avg(s.moyenne)  as Moyenne, c.name FROM App\Entity\Student s JOIN  s.classroom c  WHERE c.name = :name')->setParameter('name', $name);
        return $query->getResult();
    }
}
