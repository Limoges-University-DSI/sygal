<?php

namespace Retraitement\Controller;

use Application\Entity\Db\Repository\UtilisateurRepository;
use Application\Entity\Db\Utilisateur;
use Application\EventRouterReplacer;
use Application\Service\Notification\NotifierService;
use Application\Service\Utilisateur\UtilisateurService;
use Doctrine\ORM\EntityManager;
use UnicaenApp\Exception\RuntimeException;
use Zend\Mvc\Controller\ControllerManager;
use Zend\Mvc\Router\Http\TreeRouteStack;
use Zend\ServiceManager\ServiceLocatorInterface;

class IndexControllerFactory
{
    /**
     * Create service
     *
     * @param ControllerManager $controllerManager
     * @return IndexController
     */
    public function __invoke(ControllerManager $controllerManager)
    {
        $sl = $controllerManager->getServiceLocator();

        /** @var TreeRouteStack $httpRouter */
        $httpRouter = $sl->get('HttpRouter');
        $cliConfig = $this->getCliConfig($sl);

        /** @var NotifierService $notifierService */
        $notifierService = $sl->get(NotifierService::class);

        $routerReplacer = new EventRouterReplacer($httpRouter, $cliConfig);

        $controller = new IndexController();
        $controller->setUtilisateurApplication($this->getUtilisateurApp($sl));
        $controller->setEventRouterReplacer($routerReplacer);
        $controller->setNotifierService($notifierService);

        return $controller;
    }

    /**
     * Retourne le pseudo-utilisateur correspondant à l'application.
     *
     * @param ServiceLocatorInterface $sl
     * @return Utilisateur
     */
    public function getUtilisateurApp(ServiceLocatorInterface $sl)
    {
        /** @var UtilisateurService $utilisateurService */
        $utilisateurService = $sl->get('UtilisateurService');

        $utilisateur = $utilisateurService->fetchAppPseudoUtilisateur();

        if (!$utilisateur) {
            throw new RuntimeException("Pseudo-utilisateur application introuvable");
        }

        return $utilisateur;
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