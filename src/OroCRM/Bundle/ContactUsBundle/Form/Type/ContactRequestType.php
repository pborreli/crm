<?php
namespace OroCRM\Bundle\ContactUsBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Oro\Bundle\EmbeddedFormBundle\Form\Type\EmbeddedFormInterface;
use Oro\Bundle\EmbeddedFormBundle\Form\Type\CustomLayoutFormTypeInterface;

class ContactRequestType extends AbstractType implements EmbeddedFormInterface, CustomLayoutFormTypeInterface
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'orocrm_contactus_contact_request';
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'firstName',
            'text',
            ['required' => true, 'label' => 'orocrm.contactus.contactrequest.first_name.label']
        );
        $builder->add(
            'lastName',
            'text',
            ['required' => true, 'label' => 'orocrm.contactus.contactrequest.last_name.label']
        );
        $builder->add(
            'emailAddress',
            'text',
            ['required' => true, 'label' => 'orocrm.contactus.contactrequest.email_address.label']
        );
        $builder->add('phone', 'text', ['required' => false, 'label' => 'orocrm.contactus.contactrequest.phone.label']);
        $builder->add('comment', 'textarea', ['label' => 'orocrm.contactus.contactrequest.comment.label']);
        $builder->add('submit', 'submit');
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            [
            'data_class' => 'OroCRM\Bundle\ContactUsBundle\Entity\ContactRequest',
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'oro_channel_aware_form';
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultCss()
    {
        return <<<CSS
body {
    margin: 0;
    color: #000;
    min-width: 320px;
    background: #fff;
    font: 13px/18px Arial, Helvetica, sans-serif;
}

#page div {
    box-sizing: content-box;
    -moz-box-sizing: content-box;
    -webkit-box-sizing: content-box;
}

#page {
    padding: 0 40px;
}

.row-group {
    width: 100%;
}

.row-group:after {
    content: "";
    display: block;
    clear: both;
}

.row-group label {
    display: block;
    clear: both;
    font-weight: normal;
    margin: 0 0 3px;
}

.row-group label em {
    color: #f00;
    font-size: 16px;
}

.row-group .box {
    display: inline-block;
    width: 48.5%;
    min-width: 410px;
    margin: 0 0 5px;
}

.row-group .box:first-child {
    padding-right: 2%;
}

.row-group input[type="text"],
.row-group textarea,
.row-group button {
    box-sizing: border-box;
    -moz-box-sizing: border-box;
    -webkit-box-sizing: border-box;
    border: 1px solid #ccc;
    border-radius: 3px;
    -webkit-border-radius: 3px;
    font-size: 12px;
    color: #000;
    background-color: #fff;
}

.row-group input[type="text"] {
    display: block;
    width: 100%;
    height: 26px;
    line-height: 26px;
    padding: 0 10px;
}

.row-group textarea {
    font-size: 12px;
    display: block;
    min-width: 410px;
    width: 99.5%;
    min-height: 75px;
    resize: vertical;
    margin: 0 0 10px;
}

.row-group button {
    font: 13px/24px Arial, Helvetica, sans-serif;
    height: 28px;
    background: -webkit-gradient(linear, 0% 0%, 0% 100%, from(#ffffff), to(#f1f0f0));
    background: -webkit-linear-gradient(top, #ffffff, #f1f0f0);
    background: -moz-linear-gradient(top, #ffffff, #f1f0f0);
    background: -ms-linear-gradient(top, #ffffff, #f1f0f0);
    background: -o-linear-gradient(top, #ffffff, #f1f0f0);
    padding: 0 25px;
}
CSS;
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultSuccessMessage()
    {
        return '<p>Form has been submitted successfully</p>{back_link}';
    }

    /**
     * {@inheritdoc}
     */
    public function geFormLayout()
    {
        return 'OroCRMContactUsBundle::form.html.twig';
    }
}
