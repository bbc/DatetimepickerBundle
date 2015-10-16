<?php

/*
* This file is part of the SCDatetimepickerBundle package.
*
* (c) Stephane Collot
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace SC\DatetimepickerBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType as BaseDateType;

/**
* DatetimeType
*
*/
class DatetimeType extends AbstractType
{
    /**
     *
     * @var array
     */
    private $options;

    /**
    * Constructs
    *
    * @param array $options
    */
    public function __construct(array $options)
    {
        $this->options = $options;

    }

    /**
    * {@inheritdoc}
    */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        if (isset($options['pickerOptions'])) {
            $pickerOptions = array_merge($this->options, $options['pickerOptions']);
        }

        //Set automatically the language
        if(!isset($pickerOptions['language']))
            $pickerOptions['language'] = \Locale::getDefault();
        if($pickerOptions['language'] == 'en')
            unset($pickerOptions['language']);


        // Convert DateTimeInterface objects to date strings before passing to JavaScript.
        foreach ($pickerOptions as $name => $value) {
            if ($value instanceof \DateTime || $value instanceof \DateTimeInterface) {
                if (!$value instanceof \DateTimeImmutable) {
                    $value = clone $value;
                }

                $pickerOptions[$name] = $value->format('Y-m-d H:i:s');
            }
        }

        $view->vars = array_replace($view->vars, array(
            'pickerOptions' => $pickerOptions,
        ));
    }

    /**
    * {@inheritdoc}
    */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $configs = $this->options;

        $resolver
            ->setDefaults(array(
                'widget' => 'single_text',
                'model_timezone' => 'UTC',
                'view_timezone' => 'UTC',
                'format' =>  'dd/MM/yyyy hh:mm',
                'pickerOptions' => array(),
            ));
    }

    /**
     *
     * @see \Symfony\Component\Form\AbstractType::getParent()
     */
    public function getParent()
    {
        return 'datetime';
    }

    /**
     *
     * @see \Symfony\Component\Form\FormTypeInterface::getName()
     */
    public function getName()
    {
        return 'collot_datetime';
    }
}
