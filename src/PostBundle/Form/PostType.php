<?php

namespace PostBundle\Form;

use PostBundle\Entity\Post;
use PostBundle\Form\Type\DateTimePickerType;
use PostBundle\Form\Type\TagsInputType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', null, [
                'attr' => ['autofocus' => true],
                'label' => 'label.title'
            ])
            ->add('summary', TextareaType::class, [
                'label' => 'label.summary'
            ])
            ->add('content', null, [
                'attr' => ['rows' => 20],
                'label' => 'label.content'
            ])
            ->add('createdAt', DateTimePickerType::class, [
                'label' => 'label.created_at'
            ])
            ->add('tags', TagsInputType::class, [
                'label' => 'label.tags',
                'required' => false
            ])
            ->add('saveAndCreateNew', SubmitType::class)
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Post::class
        ]);
    }
}