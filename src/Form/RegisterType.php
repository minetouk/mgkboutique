<?php

namespace App\Form;



use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Notifier\Exception\LengthException;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\LengthValidator; // pour la longueur des carractere
use Symfony\Component\Validator\Constraints\NotBlank; //Ajoutez NotBlank pour les champs obligatoires
use Symfony\Component\Validator\Constraints\Length;




class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // Champ pour le nom  de l'utilisateur
            ->add('firstname', TextType::class, [
                'label' => 'Votre prénom',
                //longueur de carractere//
                'constraints' => new length ([
                    'min'=>2,
                    'max' =>30,
                ]),
                'attr' => [
                    'placeholder' => 'Merci de saisir votre prénom'
                ]
            ])

            // Champ pour le prenom de l'utilisateur
            ->add('lastname', TextType::class, [
             //longueur de carractere
                'constraints' => new length([
                    'min'=>2,
                    'max' =>30,
                ]), 

                'label' => 'Votre nom',
                'attr' => [
                    'placeholder' => 'Merci de saisir votre nom'
                ]
            ])
            // Champ pour l'adresse e-mail de l'utilisateur
            ->add('email', EmailType::class, [
                //longueur de carractere
                'constraints' => new length([
                    'min'=>2,
                    'max' =>60,
                ]),   
                'label' => 'Votre email',
                'attr' => [
                    'placeholder' => 'Merci de saisir votre adresse email'
                ]
            ])
            // Champ pour le mtp  de l'utilisateur et qui ce repege 2 fois avec la reconfirmation de mtp

            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Le mot de passe doit être identique.',
                'label' => 'Votre mot de passe',
                'required' => true,
                'first_options' => [
                    'label' => 'Mot de passe',
                    'attr' => [
                        'placeholder' => 'Merci de saisir votre mot de passe.'
                    ]
                ],
                'second_options' => [
                    'label' => 'Confirmez votre mot de passe',
                    'attr' => [
                        'placeholder' => 'Merci de saisir votre mot de passe.'
                    ]
                ]
            ])


            //  btn de validation
            ->add('submit', SubmitType::class, [
                'label' => "S'inscrire"
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
