<?php

namespace App\Form;


use App\Entity\Article;
use App\Entity\Auteur;
use App\Entity\Categories;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;

use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class Article1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('content' , CKEditorType::class)
            ->add('auteur', EntityType::class, [
                'class' => Auteur::class,
                'query_builder' => function (EntityRepository $er) {
            return $er->createQueryBuilder('a')
                        ->orderBy('a.name , a.firstname', 'ASC');
                },
                'choice_label' => 'fullName'
                
            ])
           
            
            ->add('preview', FileType::class, array(
                'attr' => array(
                    'placeholder' => 'Photo',
                ),
                'data_class' => null
            ))
            ->add('categories', EntityType::class, [
                'class' => Categories::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.continent , c.country', 'ASC');
                },
                'choice_label' => 'fullContent'
                ])
                
            
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
