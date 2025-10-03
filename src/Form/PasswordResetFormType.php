<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class PasswordResetFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('newPassword', RepeatedType::class, [
            'type' => PasswordType::class,
            'first_options'  => [
                'label' => 'New Password',
                'attr' => ['autocomplete' => 'new-password'],
            ],
            'second_options' => [
                'label' => 'Confirm New Password',
                'attr' => ['autocomplete' => 'new-password'],
            ],
            'invalid_message' => 'The passwords do not match.',
            'constraints' => [
                new NotBlank(['message' => 'Please enter a password.']),
                new Length(min: 8, minMessage: 'Your password must be at least {{ limit }} characters long.'),
            ],
        ]);
    }
}
