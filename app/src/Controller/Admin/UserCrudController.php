<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Service\UserPassword;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UserCrudController extends AbstractCrudController
{

    public function __construct(private readonly UserPassword $userPassword) {}

    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function persistEntity(EntityManagerInterface $entityManager, object $entityInstance): void
    {
        if ($entityInstance instanceof User) {
            $entityInstance->setEmail(trim(strtolower($entityInstance->getEmail())));
            $this->userPassword->hashPassword($entityInstance);
        }

        $dateImmutable = new \DateTimeImmutable();
        $entityInstance->setDateAdd($dateImmutable);

        parent::persistEntity($entityManager, $entityInstance);
    }

    public function updateEntity(EntityManagerInterface $entityManager, object $entityInstance): void
    {
        if ($entityInstance instanceof User) {
            $entityInstance->setEmail(trim(strtolower($entityInstance->getEmail())));
            if ($entityInstance->getPlainPassword() !== null && $entityInstance->getPlainPassword() !== '') {
                $this->userPassword->hashPassword($entityInstance);
            }
        }
        parent::updateEntity($entityManager, $entityInstance);
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('user.title')
            ->setEntityLabelInPlural('user.title.plural')
            ->setFormOptions(
                [
                    'validation_groups' => ['Default', 'user_create'],
                ],
                [
                    'validation_groups' => ['Default'],
                ]
            );
    }

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('firstname', 'user.firstname');
        yield TextField::new('lastname', 'user.lastname');
        yield EmailField::new('email', 'user.email');
        yield TextField::new('plainPassword')
            ->setLabel('user.password')
            ->setFormType(PasswordType::class)
            ->onlyOnForms();
        yield ChoiceField::new('roles')
            ->setTranslatableChoices([
                'ROLE_USER' => 'user.role.user',
                'ROLE_ADMIN' => 'user.role.admin',
            ])
            ->allowMultipleChoices()
            ->renderExpanded();
        yield ImageField::new('avatar')
            ->setBasePath('uploads/files/')
            ->setUploadDir('public/uploads/files/')
            ->setRequired(false)
            ->setUploadedFileNamePattern('[randomhash].[extension]')
            ->onlyOnForms()
            ->setFileConstraints(
                new \Symfony\Component\Validator\Constraints\File(
                    maxSize: '2M',
                    mimeTypes: [
                        'image/jpeg',
                        'image/png',
                        'image/gif',
                    ],
                    mimeTypesMessage: 'Please upload a valid image file (JPEG, PNG, GIF).',
                    maxSizeMessage: 'The maximum allowed file size is 2MB.',
                )
            );
    }
}
