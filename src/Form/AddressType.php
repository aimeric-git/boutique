<?php

namespace App\Form;

use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Quel nom souhaitez vous ?',
                'attr' => [
                    'placeholder' => 'Nommez votre adresse'
                ]
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Votre prénom', 
                'attr' => [
                    'placeholder' => 'Entrer votre prénom'
                ]
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Votre nom', 
                'attr' => [
                    'placeholder' => 'Entrer votre nom'
                ]
            ])
            ->add('company', TextType::class, [
                'label' => 'QVotre société(facultatif)', 
                'required' => 'false',
                'attr' => [
                    'placeholder' => 'Entrer le nom de votre société'
                ]
            ])
            ->add('address', TextType::class, [
                'label' => 'Votre adresse', 
                'attr' => [
                    'placeholder' => 'lot A D 10...'
                ]
            ])
            ->add('postal', TextType::class, [
                'label' => 'Code postal', 
                'attr' => [
                    'placeholder' => 'Entrer votre code postal'
                ]
            ])
            ->add('city', TextType::class, [
                'label' => 'Ville', 
                'attr' => [
                    'placeholder' => 'Entrer votre ville'
                ]
            ])
            ->add('country', CountryType::class, [
                'label' => 'Pays', 
                'attr' => [
                    'placeholder' => 'Sélectionner votre pays'
                ]
            ])
            ->add('phone', TelType::class, [
                'label' => 'Votre téléphone', 
                'attr' => [
                    'placeholder' => 'Entrer votre numero'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Ajouter mon adresse', 
                'attr' => [
                    'class' => 'btn btn-info btn-block'
                ]
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}
