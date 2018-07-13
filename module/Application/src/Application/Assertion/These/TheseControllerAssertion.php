<?php

namespace Application\Assertion\These;

use Application\Assertion\Interfaces\ControllerAssertionInterface;
use Application\Entity\Db\Acteur;
use Application\Entity\Db\Doctorant;
use Application\Entity\Db\Role;
use Application\Entity\Db\These;
use Application\Service\UserContextServiceAwareTrait;
use UnicaenApp\Exception\RuntimeException;

class TheseControllerAssertion implements ControllerAssertionInterface
{
    use UserContextServiceAwareTrait;

    const THESE_CONTROLLER = 'Application\Controller\These';
    const DOCTORANT_CONTROLLER = 'Application\Controller\Doctorant';

    /**
     * @var Doctorant
     */
    private $doctorant;

    /**
     * @var These
     */
    private $these;

    /**
     * @param Doctorant $doctorant
     * @return TheseControllerAssertion
     */
    public function setDoctorant($doctorant)
    {
        $this->doctorant = $doctorant;

        return $this;
    }

    /**
     * @param These $these
     * @return TheseControllerAssertion
     */
    public function setThese($these)
    {
        $this->these = $these;

        return $this;
    }

    /**
     * @param string $controller
     * @param null   $action
     * @param null   $privilege
     * @return bool
     */
    public function assert($controller, $action = null, $privilege = null)
    {
        switch (true) {
            case $this->selectedRoleIsDoctorant():
                return $this->assertAsDoctorant($controller, $action);
        }

        if (! $this->isStructureDuRoleRespectee()) {
            return false;
        }

//        switch (true) {
//            case $this->actionIs($controller, $action, self::THESE_CONTROLLER, 'valider-rdv-bu'):
//                // aucune validation ne doit exister
//                return $this->these && ! $this->these->getValidation(TypeValidation::CODE_RDV_BU);
//                break;
//        }

        return true;
    }

    protected function assertAsDoctorant($controller, $action = null)
    {
        if ($this->getIdentityDoctorant() === null) {
            throw new RuntimeException("Anomalie: le role doctorant est sélectionné mais aucune donnée d'identité doctorant n'est disponible");
        }

        switch (true) {
            case $this->actionIs($controller, $action, self::DOCTORANT_CONTROLLER, 'modifier-persopass'):
                return $this->doctorant && $this->doctorant->getId() === $this->getIdentityDoctorant()->getId();
                break;
        }

        return $this->these && $this->these->getDoctorant()->getId() === $this->getIdentityDoctorant()->getId();
    }

    /**
     * TODO : éviter cette duplication de code avec TheseEntityAssertion::isStructureDuRoleRespectee()
     * @see TheseEntityAssertion::isStructureDuRoleRespectee()
     *
     * @return bool
     */
    protected function isStructureDuRoleRespectee()
    {
        $role = $this->userContextService->getSelectedIdentityRole();

        if ($role->isTheseDependant()) {
            if ($role->isDoctorant()) {
                return $this->isUtilisateurEstAuteurDeLaThese();
            }
            elseif ($role->isDirecteurThese()) {
                if ($individu = $this->userContextService->getIdentityIndividu()) {
                    return $this->these->hasActeurWithRole($individu, Role::CODE_DIRECTEUR_THESE);
                }
                return false;
            }
        }

        elseif ($role->isStructureDependant()) {
            if ($role->isEtablissementDependant()) {
                // On ne voit que les thèses de son établissement.
                return $this->these->getEtablissement()->getStructure() === $role->getStructure();
            }
            elseif ($role->isEcoleDoctoraleDependant()) {
                // On ne voit que les thèses concernant son ED.
                return $this->these->getEcoleDoctorale()->getStructure() === $role->getStructure();
            }
            elseif ($role->isUniteRechercheDependant()) {
                // On ne voit que les thèses concernant son UR.
                return $this->these->getEcoleDoctorale()->getStructure() === $role->getStructure();
            }
        }

        return true;
    }

    /**
     * TODO : éviter cette duplication de code avec TheseEntityAssertion::isUtilisateurEstAuteurDeLaThese()
     * @see TheseEntityAssertion::isUtilisateurEstAuteurDeLaThese()
     *
     * @return bool
     */
    protected function isUtilisateurEstAuteurDeLaThese()
    {
        return $this->these && $this->these->getDoctorant()->getId() === $this->getIdentityDoctorant()->getId();
    }

    private function actionIs($controller, $action, $expectedController, $expectedAction) {
        return $controller === $expectedController && $action === $expectedAction;
    }

    private function actionBegins($controller, $action, $expectedController, $expectedAction) {
        return $controller === $expectedController && substr($action, 0, strlen($expectedAction)) === $expectedAction;
    }



//    /**
//     * @var UserContextService
//     */
//    protected $userContextService;
//
//    /**
//     * @param UserContextService $service
//     * @return $this
//     */
//    public function setUserContextService(UserContextService $service)
//    {
//        $this->userContextService = $service;
//        return $this;
//    }

    /**
     * @return bool
     */
    private function selectedRoleIsDoctorant()
    {
        return (bool) $this->userContextService->getSelectedRoleDoctorant();
    }
    /**
     * @var Doctorant
     */
    protected $identityDoctorant;

    /**
     * @return Doctorant
     */
    private function getIdentityDoctorant()
    {
        if (null === $this->identityDoctorant) {
            $this->identityDoctorant = $this->userContextService->getIdentityDoctorant();
        }

        return $this->identityDoctorant;
    }
}