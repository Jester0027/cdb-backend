<?php

namespace App\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreateSuperadminCommand extends Command
{
    protected static $defaultName = 'app:create-superadmin';
    private $userRepository;
    private $manager;
    private $tokenGenerator;
    private $passwordEncoder;
    private $validator;
    private $mailer;

    public function __construct(
        UserRepository $userRepository,
        EntityManagerInterface $manager,
        TokenGeneratorInterface $tokenGenerator,
        UserPasswordEncoderInterface $passwordEncoder,
        ValidatorInterface $validator,
        MailerInterface $mailer
    )
    {
        $this->userRepository = $userRepository;
        $this->manager = $manager;
        $this->tokenGenerator = $tokenGenerator;
        $this->passwordEncoder = $passwordEncoder;
        $this->validator = $validator;
        $this->mailer = $mailer;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setDescription('Creates a superadmin user with the provided ADMIN_EMAIL env variable');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        if (empty($_ENV['ADMIN_EMAIL'])) {
            $io->error('No provided username/email address');
            return 1;
        }

        $user = $this->userRepository->findOneBy(['email' => $_ENV['ADMIN_EMAIL']]);
        if (isset($user)) {
            $io->error('A user with this email address already exists');
            return 1;
        }

        $password = substr($this->tokenGenerator->generateToken(), 0, 15);

        if (!$this->createUser($password)) {
            $io->error('The provided email (ADMIN_EMAIL) isn\'t in a valid format');
            return 1;
        }

        try {
            $this->sendEmail($password);
        } catch (TransportExceptionInterface $exception) {
            $io->error(['An error occured while trying to send the email', $exception->getMessage()]);
            return 1;
        }

        $io->success([
            'The user has been created and persisted in the database',
            'An email has been sent to the specified address'
        ]);

        return 0;
    }

    /**
     * @param string $password
     * @return bool
     */
    private function createUser(string $password): bool
    {
        $user = new User();
        $user->setEmail($_ENV['ADMIN_EMAIL']);
        $user->setPassword($this->passwordEncoder->encodePassword($user, $password));
        $user->setRoles(['ROLE_USER', 'ROLE_MANAGER', 'ROLE_SUPERADMIN']);

        $errors = $this->validator->validate($user);
        if (count($errors)) {
            return false;
        }

        $this->manager->persist($user);
        $this->manager->flush();

        return true;
    }

    /**
     * @param string $password
     * @return void
     * @throws TransportExceptionInterface
     */
    private function sendEmail(string $password): void
    {
        $message = (new TemplatedEmail())
            ->from('noreply@coeurdebouviers.be')
            ->to($_ENV['ADMIN_EMAIL'])
            ->subject('Création du compte administrateur')
            ->htmlTemplate('emails/admin-creation.html.twig')
            ->context([
                'password' => $password,
                'emailAddress' => $_ENV['ADMIN_EMAIL']
            ]);

        $this->mailer->send($message);
    }
}
