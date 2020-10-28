<?php

namespace App\Form;

use App\Entity\Chapter;
use App\Form\ApplicationType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ChapterType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('chapter', TextType::class, $this->getConfiguration("Chapter", "What chapter is it?"))
            ->add('link', UrlType::class, $this->getConfiguration("Link", "The link to your chapter"))
            ->add('title', TextType::class, $this->getConfiguration("Title", "The title of your chapter"))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'chapter' => Chapter::class,
        ]);
    }
}
