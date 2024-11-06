<?php

namespace App\Repository;

use App\Entity\Utilisateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Utilisateur>
 */
class UtilisateurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Utilisateur::class);
    }

    public function sauvegarder(Utilisateur $nouvelUtilisateur, ?bool $isSaved)
    {
        $this->getEntityManager()->persist($nouvelUtilisateur);
        if ($isSaved) {
            $this->getEntityManager()->flush();
        }
        return $nouvelUtilisateur;
    }
}
