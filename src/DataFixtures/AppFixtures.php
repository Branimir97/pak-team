<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
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
        $user1 = new User();
        $user1->setEmail('branimirb51@gmail.com');
        $user1->setFirstName('Branimir');
        $user1->setLastName('Butković');
        $user1->setPassword($this->passwordEncoder->encodePassword($user1, '123456'));
        $user1->setRoles(array('ROLE_USER', 'ROLE_ADMIN'));
        $manager->persist($user1);

        $user2 = new User();
        $user2->setEmail('pakteamauto@gmail.com');
        $user2->setFirstName('Boris');
        $user2->setLastName('Pehar');
        $user2->setPassword($this->passwordEncoder->encodePassword($user2, 'borispakteam'));
        $user2->setRoles(array('ROLE_USER', 'ROLE_ADMIN'));
        $manager->persist($user2);

        $user3 = new User();
        $user3->setEmail('peharroko@gmail.com');
        $user3->setFirstName('Roko');
        $user3->setLastName('Pehar');
        $user3->setPassword($this->passwordEncoder->encodePassword($user3, '654321'));
        $user3->setRoles(array('ROLE_USER', 'ROLE_ADMIN'));
        $manager->persist($user3);

        $manager->flush();
    }
}
