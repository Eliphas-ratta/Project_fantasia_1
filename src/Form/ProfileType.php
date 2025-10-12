<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class ProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class)
            ->add('email', EmailType::class)
            ->add('profileImage', ChoiceType::class, [
            'choices' => [
                'Default' => 'default.png',
                'Avatar 1' => 'avatar1.png',
                'Avatar 2' => 'avatar2.png',
                'Avatar 3' => 'avatar3.png',
                'Avatar 4' => 'avatar4.png',
                'Avatar 5' => 'avatar5.png',
                'Avatar 6' => 'avatar6.png',
                'Avatar 7' => 'avatar7.png',
                'Avatar 8' => 'avatar8.png',
                'Avatar 9' => 'avatar9.png',
                'Avatar 10' => 'avatar10.png',
                'Avatar 11' => 'avatar11.png',
                'Avatar 12' => 'avatar12.png',
                'Avatar 13' => 'avatar13.png',
                'Avatar 14' => 'avatar14.png',
                'Avatar 15' => 'avatar15.png',
                'Avatar 16' => 'avatar16.png',
                'Avatar 17' => 'avatar17.png',
                'Avatar 18' => 'avatar18.png',
                'Avatar 19' => 'avatar19.png',
                'Avatar 20' => 'avatar20.png',
                'Avatar 21' => 'avatar21.png',
                'Avatar 22' => 'avatar22.png',
                'Avatar 23' => 'avatar23.png',
                'Avatar 24' => 'avatar24.png',
                'Avatar 25' => 'avatar25.png',
                'Avatar 26' => 'avatar26.png',
                'Avatar 27' => 'avatar27.png',
                'Avatar 28' => 'avatar28.png',
                'Avatar 29' => 'avatar29.png',
                'Avatar 30' => 'avatar30.png',
            ],
            'label' => 'Profile Image',
            'expanded' => true,
            'multiple' => false,
        ])

            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'mapped' => false,
                'required' => false,
                'first_options' => [
                    'label' => 'New Password',
                    'attr' => ['autocomplete' => 'new-password'],
                ],
                'second_options' => [
                    'label' => 'Confirm New Password',
                    'attr' => ['autocomplete' => 'new-password'],
                ],
                'invalid_message' => 'The password fields must match.',
                'constraints' => [
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        'max' => 4096,
                    ]),
                ],
            ]);
    }

   public function configureOptions(OptionsResolver $resolver): void
{
    $resolver->setDefaults([
        'data_class' => User::class,
        'csrf_protection' => true,
        'csrf_field_name' => '_token',
        'csrf_token_id'   => 'profile', // doit correspondre à l’id utilisé dans l’erreur
    ]);
}

}
