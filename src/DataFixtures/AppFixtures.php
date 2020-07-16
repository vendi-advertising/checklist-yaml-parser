<?php

namespace App\DataFixtures;

use App\Entity\ChecklistTemplate;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private UserPasswordEncoderInterface $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail('chris@vendiadvertising.com');
        $user->setPassword($this->passwordEncoder->encodePassword($user, 'test'));
        $user->addRole('ROLE_CHECKLIST_CREATOR');
        $manager->persist($user);

        $checklist = (new ChecklistTemplate())->setName('Website Launch')->setTemplateFile('website-launch.yaml');
        $manager->persist($checklist);

        $manager->flush();
    }
}
