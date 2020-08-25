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
        $users = [
            'chris@vendiadvertising.com' => 'Chris Haas',
            'julie@vendiadvertising.com' => 'Julie Haas',
            'kate@vendiadvertising.com' => 'Kate Weis',
            'cj@vendiadvertising.com' => 'CJ Schrunk',
        ];

        foreach ($users as $email => $name) {
            $user = new User($email, $name);
            $user->setPassword($this->passwordEncoder->encodePassword($user, 'test'));
            $user->addRole('ROLE_CHECKLIST_CREATOR');
            $user->addRole('ROLE_ADMIN');
            $user->addRole('ROLE_USER');
            $manager->persist($user);
            unset($user);
        }
        $manager->flush();

        $chrisUser = $manager->getRepository(User::class)->findOneBy(['email' => 'chris@vendiadvertising.com']);
        assert(null !== $chrisUser);

        $checklistTemplate = (new Template())->setName('Website Launch')->setTemplateFile('website-launch.yaml');
        $manager->persist($checklistTemplate);

        $checklist = $this->checklistParser->parseFileFromRoot(
            '/config/checklists/' . $checklistTemplate->getTemplateFile()
        )
            ->setTemplate($checklistTemplate)
            ->setDescription('Holmen Cheese')
            ->setCreatedBy($chrisUser);

        $manager->persist($checklist);

        $manager->flush();
    }
}
