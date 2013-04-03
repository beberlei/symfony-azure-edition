<?php
namespace WindowsAzure\TaskDemoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('subject', 'text')
            ->add('type', 'entity', array(
                'class' => 'WindowsAzure\TaskDemoBundle\Entity\TaskType',
                'property' => 'label'
            ))
            ->add('dueDate', 'datetime')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array());
    }

    public function getName()
    {
        return 'azure_task';
    }
}

