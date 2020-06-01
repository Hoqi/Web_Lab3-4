<?php

namespace App\DataBase;

use App\Entity\User;
use App\Entity\Feedback;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use Doctrine\ORM\Query\Expr\Join;

class DataBaseMenager
{
    private $entityManger;

    public function __construct(EntityManagerInterface $em)
    {
        $this->entityManger = $em;
    }
    
    


}
