<?php

namespace OroCRM\Bundle\ContactBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use Oro\Bundle\SecurityBundle\Annotation\Acl;
use Oro\Bundle\SecurityBundle\Annotation\AclAncestor;

use Oro\Bundle\SoapBundle\Entity\Manager\ApiEntityManager;

use OroCRM\Bundle\ContactBundle\Entity\Contact;
use OroCRM\Bundle\AccountBundle\Entity\Account;

class ContactController extends Controller
{
    /**
     * @Route("/view/{id}", name="orocrm_contact_view", requirements={"id"="\d+"})
     *
     * @Template
     * @Acl(
     *      id="orocrm_contact_view",
     *      type="entity",
     *      permission="VIEW",
     *      class="OroCRMContactBundle:Contact"
     * )
     */
    public function viewAction(Contact $contact)
    {
        return [
            'entity' => $contact,
        ];
    }

    /**
     * @Route("/info/{id}", name="orocrm_contact_info", requirements={"id"="\d+"})
     *
     * @Template
     * @AclAncestor("orocrm_contact_view")
     */
    public function infoAction(Contact $contact)
    {
        return array(
            'entity'  => $contact
        );
    }

    /**
     * Create contact form
     * @Route("/create", name="orocrm_contact_create")
     * @Template("OroCRMContactBundle:Contact:update.html.twig")
     * @Acl(
     *      id="orocrm_contact_create",
     *      type="entity",
     *      permission="CREATE",
     *      class="OroCRMContactBundle:Contact"
     * )
     */
    public function createAction()
    {
        // add predefined account to contact
        $contact   = null;
        $accountId = $this->getRequest()->get('account');
        if ($accountId) {
            $repository = $this->getDoctrine()->getRepository('OroCRMAccountBundle:Account');
            /** @var Account $account */
            $account = $repository->find($accountId);
            if ($account) {
                /** @var Contact $contact */
                $contact = $this->getManager()->createEntity();
                $contact->addAccount($account);
            } else {
                throw new NotFoundHttpException(sprintf('Account with ID %s is not found', $accountId));
            }
        }

        return $this->update($contact);
    }

    /**
     * Update user form
     * @Route("/update/{id}", name="orocrm_contact_update", requirements={"id"="\d+"})
     *
     * @Template
     * @Acl(
     *      id="orocrm_contact_update",
     *      type="entity",
     *      permission="EDIT",
     *      class="OroCRMContactBundle:Contact"
     * )
     */
    public function updateAction(Contact $entity)
    {
        return $this->update($entity);
    }

    /**
     * @Route(
     *      "/{_format}",
     *      name="orocrm_contact_index",
     *      requirements={"_format"="html|json"},
     *      defaults={"_format" = "html"}
     * )
     *
     * @Template
     * @AclAncestor("orocrm_contact_view")
     */
    public function indexAction()
    {
        return [];
    }

    /**
     * @Route(
     *      "/{contactId}/email-create",
     *      name="orocrm_contact_email_create",
     *      requirements={"contactId"="\d+"}
     * )
     * @AclAncestor("oro_email_create")
     * @ParamConverter("contact", options={"id" = "contactId"})
     */
    public function createEmailAction(Contact $contact)
    {
        $query = $this->getRequest()->query->all();
        if ($contact->getPrimaryEmail()) {
            $query['to'] = $contact->getPrimaryEmail()->getEmail();
        }
        $query['gridName'] = 'contacts-email-grid';

        return $this->forward(
            'OroEmailBundle:Email:create',
            array(),
            $query
        );
    }

    /**
     * @return ApiEntityManager
     */
    protected function getManager()
    {
        return $this->get('orocrm_contact.contact.manager');
    }

    protected function update(Contact $entity = null)
    {
        if (!$entity) {
            $entity = $this->getManager()->createEntity();
        }

        if ($this->get('orocrm_contact.form.handler.contact')->process($entity)) {
            $this->get('session')->getFlashBag()->add(
                'success',
                $this->get('translator')->trans('orocrm.contact.controller.contact.saved.message')
            );

            return $this->get('oro_ui.router')->redirectAfterSave(
                ['route' => 'orocrm_contact_update', 'parameters' => ['id' => $entity->getId()]],
                ['route' => 'orocrm_contact_view', 'parameters' => ['id' => $entity->getId()]],
                $entity
            );
        }

        return array(
            'entity' => $entity,
            'form'   => $this->get('orocrm_contact.form.contact')->createView(),
        );
    }

    /**
     * @Route("/widget/email/{contactId}", name="orocrm_contact_widget_email", requirements={"contactId"="\d+"})
     * @ParamConverter("contact", options={"id"="contactId"})
     * @Template("OroCRMContactBundle:Contact:email.html.twig")
     * @AclAncestor("oro_email_view")
     */
    public function widgetEmailAction(Contact $contact)
    {
        return array('entity' => $contact);
    }
}
