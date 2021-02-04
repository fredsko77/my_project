<?php

namespace App\Form;

use App\Entity\Project;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('details')
            ->add('link')
            ->add('image', FileType::class, [
                'required' => true,
                'multiple' => false,
                'label' => 'Télécharger une image',
                'mapped' => false
            ])
            ->add('techno', ChoiceType::class, [
                'choices' => [
                    'Front-End' => [
                        'HTML' => 'html',
                        'CSS' => 'css',
                        'Javascript' => 'js',
                    ],
                    'Back-End' => [                        
                        'PHP' => 'php',
                        'SQL' => 'sql',
                    ],
                    'Framework ou CMS' => [
                        'ReactJs' => 'react',
                        'VueJs' => 'vue',
                        'Symfony' => 'symfony',
                        'Wordpress' => 'wordpress',                        
                    ],
                    'Librairies' => [
                        'JQuery' => 'jquery',
                        'Axios' => 'axios',
                        'Bootstrap' => 'bootstrap',
                        'Animation On Scroll' => 'aos',
                    ],
                ],
                'multiple' => true,
                'expanded' => true,
                'label' => 'Les technos utilisés pour le site',
            ])
            ->add('status', ChoiceType::class, [
                'choices' => [
                    'Non terminé' => 'in-progress',
                    'Terminé' => 'done'
                ],
                'multiple' => false,
                'expanded' => false,
                'label' => 'Status du projet',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Project::class,
        ]);
    }
}
