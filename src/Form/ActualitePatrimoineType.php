<?php
namespace App\Form;

use App\Entity\ActualitePatrimoine;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\Length;

class ActualitePatrimoineType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titreActualite', TextType::class, [
                'label' => 'Titre de l\'actualité',
                'attr' => [
                    'placeholder' => 'Titre de l\'actualité',
                    'class' => 'w-full rounded-md border border-gray-300 bg-white py-2 px-4 text-base font-medium text-black outline-none focus:border-yellow-500 focus:shadow-md'
                ],
                'required' => true,
                'constraints' => [
                    new Length(['min' => 3, 'max' => 255]),
                ],
            ])
            ->add('imageActualite', FileType::class, [
                'label' => 'Image principale',
                'mapped' => false,
                'required' => true,
                'attr' => [
                    'class' => 'w-full rounded-md border border-gray-300 bg-white py-2 px-4 text-base font-medium text-black outline-none focus:border-yellow-500 focus:shadow-md'
                ],
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
            ->add('contenuActualite', TextareaType::class, [
                'label' => 'Contenu de l\'actualité',
                'attr' => [
                    'class' => 'w-full rounded-md border border-gray-300 bg-white py-2 px-4 text-base font-medium text-black outline-none focus:border-yellow-500 focus:shadow-md',
                    'placeholder' => 'Contenu de l\'actualité'
                ]
            ])
            ->add('imageName', FileType::class, [
                'label' => 'Images supplémentaires (facultatif)',
                'multiple' => true,
                'mapped' => false,
                'required' => false,
                'attr' => [
                    'class' => 'w-full rounded-md border border-gray-300 bg-white py-2 px-4 text-base font-medium text-black outline-none focus:border-yellow-500 focus:shadow-md'
                ],
            ])
            ->add('lienUrl', UrlType::class, [
                'by_reference' => false,
                'required' => false,
                'label' => 'Lien supplémentaire (facultatif)',
                'row_attr' => ['class' => 'lien-url-row'],
                'mapped' => false,
                'attr' => [
                    'class' => 'w-full rounded-md border border-gray-300 bg-white py-2 px-4 text-base font-medium text-black outline-none focus:border-yellow-500 focus:shadow-md',
                    'placeholder' => 'Lien supplémentaire'
                ],
            ])
            ->add('texteLien', TextType::class, [
                'label' => 'Texte du lien supplémentaire (facultatif)',
                'row_attr' => ['class' => 'texte-lien-row'],
                'mapped' => false,
                'required' => false,
                'attr' => [
                    'class' => 'w-full rounded-md border border-gray-300 bg-white py-2 px-4 text-base font-medium text-black outline-none focus:border-yellow-500 focus:shadow-md',
                    'placeholder' => 'Texte du lien'
                ],
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
