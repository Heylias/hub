<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

class StatsService{

    private $manager;

    public function __construct(EntityManagerInterface $manager){
        $this->manager = $manager;
    }

    public function getUsersCount(){
        return $this->manager->createQuery('SELECT COUNT(u) FROM App\Entity\User u')->getSingleScalarResult();
    }

    public function getFictionsCount(){
        return $this->manager->createQuery('SELECT COUNT(a) FROM App\Entity\Fanfiction a')->getSingleScalarResult();
    }

    public function getCommentsCount(){
        return $this->manager->createQuery('SELECT COUNT(c) FROM App\Entity\Comment c')->getSingleScalarResult();
    }

    public function getTagsCount(){
        return $this->manager->createQuery('SELECT COUNT(t) FROM App\Entity\Tags t')->getSingleScalarResult();
    }

    public function getChaptersCount(){
        return $this->manager->createQuery('SELECT COUNT(ch) FROM App\Entity\Chapter ch')->getSingleScalarResult();
    }

    public function getGenresCount(){
        return $this->manager->createQuery('SELECT COUNT(g) FROM App\Entity\Genre g')->getSingleScalarResult();
    }

    public function getLanguagesCount(){
        return $this->manager->createQuery('SELECT COUNT(l) FROM App\Entity\Language l')->getSingleScalarResult();
    }

    public function getFictionsStats($direction){
        return $this->manager->createQuery(
            'SELECT AVG(c.rating) as note, f.title, f.id, f.coverImage, u.pseudonym, u.userImage
            FROM App\Entity\Comment c
            JOIN c.fanfiction f
            JOIN f.author u
            GROUP BY f
            ORDER BY note '. $direction
        )
        ->setMaxResults(5)
        ->getResult();
    }

}