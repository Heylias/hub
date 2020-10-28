<?php

namespace App\Form;

use App\Form\TagType;
use App\Entity\Language;
use App\Entity\Fanfiction;
use App\Entity\Tags;
use App\Form\ApplicationType;
use App\Form\FanficImageType;
use App\Repository\TagsRepository;
use App\Repository\LanguageRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class FictionType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Title',
                'attr' => [
                    'placeholder'=>"Title of your fiction"
                ]
            ])
            ->add('summary', TextareaType::class, $this->getConfiguration('Summary','Give the summary of your work'))
            ->add('language', EntityType::class, [
                'class' => Language::class,
                'query_builder' => function (LanguageRepository $lr) {
                    return $lr->createQueryBuilder('language')
                        ->orderBy('language.short', 'ASC');
                },
                'choice_label' => 'short',
            ])
            ->add('coverImage', UrlType::class, $this->getConfiguration('Cover', 'Insert your fiction\'s cover image'))
            ->add('tags', EntityType::class, [
                'class' => Tags::class,
                'query_builder' => function (TagsRepository $tr) {
                    return $tr->createQueryBuilder('tags')
                        ->orderBy('tags.name', 'ASC');
                },
                'choice_label' => 'name',
                'multiple' => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Fanfiction::class,
        ]);
    }
}
