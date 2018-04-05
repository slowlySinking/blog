<?php

namespace PostBundle\Form\Type;

use PostBundle\Utils\MomentFormatConverter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DateTimePickerType extends AbstractType
{
    /**
     * @var MomentFormatConverter
     */
    private $formatConverter;

    /**
     * DateTimePickerType constructor.
     * @param MomentFormatConverter $formatConverter
     */
    public function __construct(MomentFormatConverter $formatConverter)
    {
        $this->formatConverter = $formatConverter;
    }

    /**
     * @param FormView $view
     * @param FormInterface $form
     * @param array $options
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['attr']['data-date-format'] = $this->formatConverter->convertFormat($options['format']);
        $view->vars['attr']['data-date-locale'] = mb_strtolower(strtr(\Locale::getDefault(), '_', '-'));
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'widget' => 'single_text'
        ]);
    }

    /**
     * @return string
     */
    public function getParent()
    {
        return DateTimeType::class;
    }
}