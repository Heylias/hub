<?php

namespace App\Form;

use App\Entity\User;
use App\Form\ApplicationType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class RegistrationType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', TextType::class, $this->getConfiguration("Email", "Your email address"))
            ->add('password', PasswordType::class, $this->getConfiguration("Password", "Your password"))
            ->add('passwordConfirm', PasswordType::class, $this->getConfiguration("Password confirm","Please confirm your password"))
            ->add('pseudonym', TextType::class, $this->getConfiguration("Username", "Your username"))
            ->add('userImage', FileType::class, [
                'label' => "Avatar (jpg,png,gif)",
                'required' =>false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
