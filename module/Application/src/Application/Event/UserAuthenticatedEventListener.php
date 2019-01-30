<?php

namespace Application\Event;

use Application\Entity\Db\Utilisateur;
use Application\Entity\UserWrapperFactory;
use Application\Service\Etablissement\EtablissementServiceAwareTrait;
use Application\Service\Individu\IndividuServiceAwareTrait;
use Application\Service\Utilisateur\UtilisateurServiceAwareTrait;
use Application\SourceCodeStringHelper;
use UnicaenAuth\Event\Listener\AuthenticatedUserSavedAbstractListener;
use UnicaenAuth\Event\UserAuthenticatedEvent;
use UnicaenAuth\Service\UserContext as UserContextService;

class UserAuthenticatedEventListener extends AuthenticatedUserSavedAbstractListener
{
    use IndividuServiceAwareTrait;
    use EtablissementServiceAwareTrait;
    use UtilisateurServiceAwareTrait;

    /**
     * @var UserContextService
     */
    private $userContextService;

    /**
     * @param UserContextService $userContextService
     */
    public function setAuthUserContextService(UserContextService $userContextService)
    {
        $this->userContextService = $userContextService;
    }

    /**
     * Méthode appelée juste avant que l'entité utilisateur soit persistée.
     *
     * @param UserAuthenticatedEvent $e
     */
    public function onUserAuthenticatedPrePersist(UserAuthenticatedEvent $e)
    {
        $userWrapperFactory = new UserWrapperFactory();
        $userWrapper = $userWrapperFactory->createInstanceFromUserAuthenticatedEvent($e);

        /** @var Utilisateur $utilisateur */
        $utilisateur = $e->getDbUser();
        $utilisateur->setDisplayName($userWrapper->getDisplayName()); // màj NOM Prénom

        // Sélection du dernier rôle endossé.
        if ($role = $utilisateur->getLastRole()) {
            $this->userContextService->setNextSelectedIdentityRole($role);
        }
    }

    /**
     * Méthode appelée juste après que l'entité utilisateur soit persistée.
     *
     * Un Individu est créé/màj à partir de l'utilisateur qui vient de s'authentifier.
     *
     * @param UserAuthenticatedEvent $e
     */
    public function onUserAuthenticatedPostPersist(UserAuthenticatedEvent $e)
    {
        $userWrapperFactory = new UserWrapperFactory();
        $userWrapper = $userWrapperFactory->createInstanceFromUserAuthenticatedEvent($e);

        if ($userWrapper->getIndividu() !== null) {
            $individu = $userWrapper->getIndividu();
        } else {
            $domaineEtab = $userWrapper->getDomainFromEppn();
            $etablissement = $this->getEtablissementService()->getRepository()->findOneByDomaine($domaineEtab);

            // recherche de l'Individu correspondant à l'utilisateur
            $sourceCodeHelper = new SourceCodeStringHelper();
            $sourceCode = $sourceCodeHelper->addPrefixEtablissementTo($userWrapper->getSupannId(), $etablissement);
            $individu = $this->individuService->getRepository()->findOneBySourceCode($sourceCode);

            // création de l'Individu si inexistant
            if (null === $individu) {
                $createur = $this->utilisateurService->getRepository()->fetchAppPseudoUser();
                $individu = $this->individuService->createIndividuFromUserWrapperAndEtab($userWrapper, $etablissement, $createur);
            }
        }

        // renseigne le lien utilisateur-->individu
        /** @var Utilisateur $utilisateur */
        $utilisateur = $e->getDbUser();
        $this->utilisateurService->setIndividuForUtilisateur($individu, $utilisateur);
    }
}