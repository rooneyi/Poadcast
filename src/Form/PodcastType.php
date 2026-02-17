<?php

namespace App\Form;

use App\Entity\Podcast;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;

class PodcastType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('author')
            ->add('audioFile', FileType::class, [
                'label' => 'Fichier Audio (MP3/WAV)',
                'mapped' => false,
                'required' => true,
                'constraints' => [
                    new File([
                        'maxSize' => '50M',
                        'mimeTypes' => [
                            'audio/mpeg',
                            'audio/wav',
                            'audio/x-wav',
                        ],
                        'mimeTypesMessage' => 'Veuillez uploader un fichier audio valide (MP3 ou WAV)',
                    ])
                ],
            ])
            ->add('coverImage', FileType::class, [
                'label' => 'Image de couverture',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '5120k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Veuillez uploader une image valide (JPG ou PNG)',
                    ])
                ],
            ])
            ->add('duration')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Podcast::class,
        ]);
    }
}
