<?php

namespace Application\Controller\Factory;

use Application\Controller\MailConfirmationController;
use Application\Service\Individu\IndividuService;
use Application\Service\MailConfirmationService;
use Application\Service\Notification\NotifierService;
use Zend\Mvc\Controller\ControllerManager;

class MailConfirmationControllerFactory
{
    /**
     * Create service
     *
     * @param ControllerManager $controllerManager
     * @return MailConfirmationController
     */
    public function __invoke(ControllerManager $controllerManager)
    {
        $sl = $controllerManager->getServiceLocator();

        /**
         * @var MailConfirmationService $mailConfirmationService
         * @var NotifierService $notifierService
         * @var IndividuService $individuService
         */
        $mailConfirmationService = $sl->get('MailConfirmationService');
        $notifierService = $sl->get(NotifierService::class);
        $individuService = $sl->get('IndividuService');

        $controller = new MailConfirmationController();
        $controller->setMailConfirmationService($mailConfirmationService);
        $controller->setIndividuService($individuService);
        $controller->setNotifierService($notifierService);


        return $controller;
    }
}