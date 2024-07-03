<?php


namespace App\Form;

use App\Entity\ActualitePatrimoine;
use App\Entity\AutresImages;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Url;

class ActualitePatrimoineType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titreActualite', TextType::class, [
                'label' => 'Titre de l\'actualité',
                'attr' => ['placeholder' => 'Titre de l\'actualité'],
                'required' => true,
                'constraints' => [
                    new Length(['min' => 3, 'max' => 50]),
                ],
            ])
            ->add('imageActualite', FileType::class, [
                'label' => 'Photo',
                'mapped' => false,
                'required' => true,
                'constraints' => [
                    new Image([
                        'maxSize' => '16M',
                        'mimeTypes' => [
                            'image/*',
                        ],
                        'mimeTypesMessage' => 'Seuls les formats JPEG, PNG et JPG sont acceptés',
                    ])
                ],
            ])
            ->add('contenuActualite')
            ->add('imageName', FileType::class, [
                'label' => false,
                'multiple' => true,
                'mapped' => false,
                'required' => false
            ])
            ->add('lienUrl', UrlType::class, [
                'by_reference' => false,
                'label' => 'Lien supplémentaire (facultatif)',
                'row_attr' => ['class' => 'lien-url-row'],
                'mapped' => false,
                ])
                
            ->add('texteLien', TextType::class, [
                'label' => 'Texte du lien (facultatif)',
                'row_attr' => ['class' => 'texte-lien-row'],
                'mapped' => false,
                'required' => false
            ])
        ;
            
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ActualitePatrimoine::class,
        ]);
    }
    
}


