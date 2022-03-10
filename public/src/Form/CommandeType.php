<?php

namespace App\Form;

use App\Entity\Commande;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommandeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
       
            ->add('adresseLivraison', TextType::class, [
                'label' => 'Adresse complete',
                'attr'  => [
                    'placeholder' => 'Adresse complète'
                ],
                'required' => true
            ])
            ->add('cpLivraison', TextType::class, [
                'label' => 'Code Postal',
                'attr'  => [
                    'placeholder' => 'Code postal'
                ],
                'required' => true
            ])
            ->add('villeLivraison', TextType::class, [
                'label' => 'Ville',
                'attr'  => [
                    'placeholder' => 'Ville'
                ],
                'required' => true
            ])
          
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commande::class,
        ]);
    }
}
