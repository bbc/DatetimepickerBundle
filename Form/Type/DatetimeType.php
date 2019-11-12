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
use Symfony\Component\OptionsResolver\OptionsResolver;

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
    public function __construct(array $options = array())
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
    public function configureOptions(OptionsResolver $resolver)
    {
        $configs = $this->options;

        $resolver
            ->setDefaults(array(
                'widget' => 'single_text',
                'model_timezone' => 'UTC',
                'view_timezone' => 'UTC',
                'date_format' => 'dd/MM/yyyy H:i',
                'format' =>  "yyyy-MM-dd'T'HH:mm:ssZZZZZ",
                'pickerOptions' => array(),
            ));
    }

    /**
     *
     * @see \Symfony\Component\Form\AbstractType::getParent()
     */
    public function getParent()
    {
        return \Symfony\Component\Form\Extension\Core\Type\DateTimeType::class;
    }
    
    public function getName()
    {
        return $this->getBlockPrefix();
    }

    public function getBlockPrefix()
    {
        return 'collot_datetime';
    }
}
