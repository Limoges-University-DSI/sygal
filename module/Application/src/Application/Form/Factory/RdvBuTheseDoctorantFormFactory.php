<?php

namespace Application\Form\Factory;

use Application\Form\RdvBuTheseDoctorantForm;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;
use Zend\Form\FormElementManager;

class RdvBuTheseDoctorantFormFactory
{
    public function __invoke(FormElementManager $formElementManager)
    {
        $sl = $formElementManager->getServiceLocator();

        /** @var DoctrineObject $hydrator */
        $hydrator = $sl->get('HydratorManager')->get('DoctrineModule\Stdlib\Hydrator\DoctrineObject');

        $form = new RdvBuTheseDoctorantForm();
        $form->setHydrator($hydrator);
        
        return $form;
    }
}