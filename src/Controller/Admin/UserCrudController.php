<?php

namespace App\Controller\Admin;

use App\Entity\User;
use Doctrine\DBAL\Types\JsonType;
use Doctrine\Migrations\Configuration\Migration\JsonFile;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Mime\Email;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Validator\Constraints\Json;
use Symfony\Component\Validator\Constraints\JsonValidator;
use Symfony\Config\Security\PasswordHasherConfig;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id');
        yield EmailField::new('email');
        yield ArrayField::new('roles');
        yield TextField::new('password');
    }
}
