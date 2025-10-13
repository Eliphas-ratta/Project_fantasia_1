<?php

namespace App\Form;

use App\Entity\Faction;
use App\Entity\Continent;
use App\Entity\Guild;
use App\Entity\Race;
use App\Entity\Religion;
use App\Entity\Technology;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FactionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // 🔹 Nom de la faction
            ->add('name', TextType::class, [
                'label' => 'Name',
                'attr' => [
                    'placeholder' => 'Enter faction name',
                    'class' => 'input-field'
                ],
            ])

            // 🔹 Type de régime
            ->add('regime', TextType::class, [
                'label' => 'Regime type',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Example: Monarchy, Republic, Theocracy...',
                    'class' => 'input-field'
                ],
            ])

            // 🔹 Description
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'required' => false,
                'attr' => [
                    'rows' => 6,
                    'placeholder' => 'Describe this faction...',
                    'class' => 'textarea-field'
                ],
            ])

            // 🔹 Image d’illustration
            ->add('image', FileType::class, [
                'label' => 'Faction image',
                'required' => false,
                'mapped' => false, // pour upload manuel
                'attr' => ['class' => 'form-control'],
            ])

            // 🔹 Continent d’origine
            ->add('continent', EntityType::class, [
                'class' => Continent::class,
                'choice_label' => 'name',
                'required' => false,
                'label' => 'Continent',
                'placeholder' => 'Select a continent',
                'attr' => ['class' => 'select-field'],
            ])

            // 🔹 Guilds associées
            ->add('guilds', EntityType::class, [
                'class' => Guild::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => false,
                'required' => false,
                'label' => 'Guilds',
                'attr' => ['class' => 'select-field'],
            ])

            // 🔹 Races associées
            ->add('races', EntityType::class, [
                'class' => Race::class,
                'choice_label' => 'name',
                'multiple' => true,
                'required' => false,
                'label' => 'Races',
                'attr' => ['class' => 'select-field'],
            ])

            // 🔹 Religions
            ->add('religions', EntityType::class, [
                'class' => Religion::class,
                'choice_label' => 'name',
                'multiple' => true,
                'required' => false,
                'label' => 'Religions',
                'attr' => ['class' => 'select-field'],
            ])

            // 🔹 Technologies
            ->add('technologies', EntityType::class, [
                'class' => Technology::class,
                'choice_label' => 'name',
                'multiple' => true,
                'required' => false,
                'label' => 'Technologies',
                'attr' => ['class' => 'select-field'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Faction::class,
        ]);
    }
}
