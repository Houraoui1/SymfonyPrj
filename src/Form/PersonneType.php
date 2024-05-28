<?php

namespace App\Form;

use App\Entity\Hobby;
use App\Entity\Job;
use App\Entity\Personne;
use App\Entity\Profile;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class PersonneType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname')
            ->add('name')
            ->add('age')
            ->add('createdAt', null, [
                'widget' => 'single_text',
            ])
            ->add('updatedAt', null, [
                'widget' => 'single_text'
            ])
            ->add('profile',EntityType::class,
            
            ['expanded'=> false,
            'required' => false,
            'class'=>Profile::class,
            'multiple' => false,
            'attr'=>['class'=>'select2List']
            ])
            ->add('hobbies',EntityType::class,
            [
                'expanded'=> false,
            'required' => false,
            'class'=>Hobby::class,
            'multiple' => true,
            
                'attr'=>['class'=>'select2List'],
            'query_builder'=>function(EntityRepository $er)
            {
                return $er->createQueryBuilder('h')
                ->orderBy('h.designation' , 'ASC');
            },
            'choice_label'=>'designation'
            ])
            ->add('job', EntityType::class,
            
            [
                'class'=>Job::class,

                'required' => false,
                'attr'=>[
                    'class'=>'select2List'
                        ],
            ])
            ->add('photo', FileType::class, [
                'label' => 'Brochure (PDF file)',
                
                'mapped' => false,


                'required' => false,

                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/gif',
                            'image/jpg'
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image ',
                    ])
                ],
            ])
            ->add('editer',SubmitType::class)
        
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Personne::class,
        ]);
    }
}
