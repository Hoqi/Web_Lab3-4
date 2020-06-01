<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $admin = new User();
        $admin->setUsername('AdminKrytoi');
        $admin->setPassword($this->passwordEncoder->encodePassword(
            $admin,
            'samplE123'
        ));
        $admin->setRoles([User::rolesArray['Admin']]);

        $manager->persist($admin);

        $user = new User();
        $user->setUsername('AdminneKrytoi');
        $user->setPassword($this->passwordEncoder->encodePassword(
            $user,
            'samplE123'
        ));
        $user->setRoles([User::rolesArray['User']]);

        $manager->persist($user);

        $manager->flush();
    }
}
