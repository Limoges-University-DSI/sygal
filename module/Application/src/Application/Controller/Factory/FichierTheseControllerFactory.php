<?php

namespace Application\Controller\Factory;

use Application\Controller\FichierTheseController;
use Application\EventRouterReplacer;
use Application\Service\Fichier\FichierService;
use Application\Service\Individu\IndividuService;
use Application\Service\Notification\NotifierService;
use Application\Service\These\TheseService;
use Application\Service\Utilisateur\UtilisateurService;
use Application\Service\Validation\ValidationService;
use Application\Service\VersionFichier\VersionFichierService;
use Zend\Mvc\Controller\ControllerManager;
use Zend\Mvc\Router\Http\TreeRouteStack;
use Zend\ServiceManager\ServiceLocatorInterface;

class FichierTheseControllerFactory
{
    /**
     * Create service
     *
     * @param ControllerManager $controllerManager
     * @return FichierTheseController
     */
    public function __invoke(ControllerManager $controllerManager)
    {
        $serviceLocator = $controllerManager->getServiceLocator();

        /** @var TreeRouteStack $httpRouter */
        $httpRouter = $serviceLocator->get('HttpRouter');
        $cliConfig = $this->getCliConfig($serviceLocator);

        /**
         * @var TheseService          $theseService
         * @var FichierService        $fichierService
         * @var VersionFichierService $versionFichierService
         * @var NotifierService       $notificationService
         * @var IndividuService       $individuService
         * @var ValidationService     $validationService
         * @var UtilisateurService    $utilisateurService
         */
        $theseService = $serviceLocator->get('TheseService');
        $fichierService = $serviceLocator->get('FichierService');
        $versionFichierService = $serviceLocator->get('VersionFichierService');
        $notificationService = $serviceLocator->get(NotifierService::class);
        $individuService = $serviceLocator->get('IndividuService');
        $validationService = $serviceLocator->get('ValidationService');
        $utilisateurService = $serviceLocator->get('UtilisateurService');
        $eventRouterReplacer = new EventRouterReplacer($httpRouter, $cliConfig);

        $controller = new FichierTheseController();
        $controller->setTheseService($theseService);
        $controller->setFichierService($fichierService);
        $controller->setVersionFichierService($versionFichierService);
        $controller->setNotifierService($notificationService);
        $controller->setIndividuService($individuService);
        $controller->setValidationService($validationService);
        $controller->setUtilisateurService($utilisateurService);
        $controller->setEventRouterReplacer($eventRouterReplacer);

        return $controller;
    }

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return array
     */
    private function getCliConfig(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Config');

        return [
            'domain' => isset($config['cli_config']['domain']) ? $config['cli_config']['domain'] : null,
            'scheme' => isset($config['cli_config']['scheme']) ? $config['cli_config']['scheme'] : null,
        ];
    }
}



