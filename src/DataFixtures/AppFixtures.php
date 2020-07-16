<?php

namespace App\DataFixtures;

use App\Entity\Template;
use App\Entity\User;
use App\Service\ChecklistParser;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private UserPasswordEncoderInterface $passwordEncoder;
    private ChecklistParser $checklistParser;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder, ChecklistParser $checklistParser)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->checklistParser = $checklistParser;
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail('chris@vendiadvertising.com');
        $user->setPassword($this->passwordEncoder->encodePassword($user, 'test'));
        $user->addRole('ROLE_CHECKLIST_CREATOR');
        $manager->persist($user);

        $checklistTemplate = (new Template())->setName('Website Launch')->setTemplateFile('website-launch.yaml');
        $manager->persist($checklistTemplate);

        $checklist = $this->checklistParser->parseFileFromRoot(
            '/config/checklists/' . $checklistTemplate->getTemplateFile()
        )
            ->setTemplate($checklistTemplate)
            ->setDescription('Holmen Cheese')
            ->setCreatedBy($user);

        $manager->persist($checklist);

        $manager->flush();
    }
}
