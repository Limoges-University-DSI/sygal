<?php

namespace Soutenance\Controller;


use Application\Controller\AbstractController;
use Application\Entity\Db\Acteur;
use Application\Entity\Db\IndividuRole;
use Application\Entity\Db\These;
use Application\Entity\Db\TypeValidation;
use Application\Service\Acteur\ActeurServiceAwareTrait;
use Application\Service\Individu\IndividuServiceAwareTrait;
use Application\Service\Notification\NotifierServiceAwareTrait;
use Application\Service\Role\RoleServiceAwareTrait;
use Application\Service\These\TheseServiceAwareTrait;
use Application\Service\Validation\ValidationServiceAwareTrait;
use BjyAuthorize\Exception\UnAuthorizedException;
use DateInterval;
use Exception;
use Soutenance\Entity\Membre;
use Soutenance\Entity\Proposition;
use Soutenance\Form\SoutenanceDateRenduRapport\SoutenanceDateRenduRapportForm;
use Soutenance\Provider\Privilege\SoutenancePrivileges;
use Soutenance\Service\Membre\MembreServiceAwareTrait;
use Soutenance\Service\Proposition\PropositionServiceAwareTrait;
use UnicaenApp\Exception\RuntimeException;
use Zend\Http\Request;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

// TODO mettre directement l'acteur dans la table membre simplifierai beaucoup de chose ...

class PresoutenanceController extends AbstractController
{
    use TheseServiceAwareTrait;
    use MembreServiceAwareTrait;
    use IndividuServiceAwareTrait;
    use NotifierServiceAwareTrait;
    use PropositionServiceAwareTrait;
    use ActeurServiceAwareTrait;
    use ValidationServiceAwareTrait;
    use RoleServiceAwareTrait;

    public function presoutenanceAction()
    {
        /** @var These $these */
        $idThese = $this->params()->fromRoute('these');
        $these = $this->getTheseService()->getRepository()->find($idThese);

        $isAllowed = $this->isAllowed($these, SoutenancePrivileges::SOUTENANCE_PRESOUTENANCE_VISUALISATION);
        if (!$isAllowed) {
            throw new UnAuthorizedException("Vous êtes non authorisé(e) à visualiser les informations de ces soutenances.");
        }

        /** @var Proposition $proposition */
        $proposition = $this->getPropositionService()->findByThese($these);
        $rapporteurs = $this->getPropositionService()->getRapporteurs($proposition);

        /** Si la proposition ne possède pas encore de date de rendu de rapport alors la valeur par défaut est donnée */
        $renduRapport = $proposition->getRenduRapport();
        if (!$renduRapport) {
            try {
                $renduRapport = $proposition->getDate();
                $renduRapport = $renduRapport->sub(new DateInterval('P21D'));
            } catch (Exception $e) {
                throw new RuntimeException("Un problème a été rencontré lors du calcul de la date de rendu des rapport.");
            }
            $proposition->setRenduRapport($renduRapport);
            $this->getPropositionService()->update($proposition);
        }

        $engagements = [];
        foreach ($rapporteurs as $rapporteur) {
            if ($rapporteur->getIndividu()) {
                $validations = $this->getValidationService()->getRepository()->findValidationByCodeAndIndividu(TypeValidation::CODE_ENGAGEMENT_IMPARTIALITE, $rapporteur->getIndividu());
                if ($validations) $engagements[$rapporteur->getIndividu()->getId()] = current($validations);
            }
        }

        return new ViewModel([
            'these' => $these,
            'proposition' => $proposition,
            'rapporteurs' => $rapporteurs,
            'engagements' => $engagements,
        ]);
    }


    public function dateRenduRapportAction()
    {
        /** @var These $these */
        $idThese = $this->params()->fromRoute('these');
        $these = $this->getTheseService()->getRepository()->find($idThese);

        $isAllowed = $this->isAllowed($these, SoutenancePrivileges::SOUTENANCE_DATE_RETOUR_MODIFICATION);
        if (!$isAllowed) {
            throw new UnAuthorizedException("Vous êtes non authorisé(e) à modifier la date de retour des rapports.");
        }

        /** @var Proposition $proposition */
        $proposition = $this->getPropositionService()->findByThese($these);

        /** @var SoutenanceDateRenduRapportForm $form */
        $form = $this->getServiceLocator()->get('FormElementManager')->get(SoutenanceDateRenduRapportForm::class);
        $form->setAttribute('action', $this->url()->fromRoute('soutenance/presoutenance/date-rendu-rapport', ['these' => $these->getId()], [], true));
        $form->bind($proposition);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getPropositionService()->update($proposition);
            }
        }

        return new ViewModel([
                'form' => $form,
                'title' => 'Modification de la date de rendu des rapports',
            ]
        );
    }

    /**
     * Ici on affecte au membre des acteurs qui remonte des SIs des établissements
     * Puis on affecte les rôles rapporteurs et membres
     * QUID :: Président ...
     */
    public function associerJuryAction()
    {
        /** @var These $these */
        $theseId = $this->params()->fromRoute('these');
        $these = $this->getTheseService()->getRepository()->find($theseId);

        /** @var Membre $membre */
        $idMembre = $this->params()->fromRoute('membre');
        $membre = $this->getMembreService()->find($idMembre);

        $acteurs = $this->getActeurService()->getRepository()->findActeurByThese($these);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $acteurId = $data['acteur'];
            /** @var Acteur $acteur */
            $acteur = $this->getActeurService()->getRepository()->find($acteurId);

            if (!$acteur) {
                throw new RuntimeException("Aucun acteur à associer !");
            } else {
                //mise à jour du membre de soutenance
                $membre->setIndividu($acteur->getIndividu());
                $this->getMembreService()->update($membre);
                //affectation du rôle
                $this->getRoleService()->addIndividuRole($acteur->getIndividu(),$acteur->getRole());
            }
        }

        return new ViewModel([
            'title' => "Association de ".$membre->getDenomination()." à un acteur SyGAL",
            'acteurs' => $acteurs,
            'membre' => $membre,
            'these' => $these,
        ]);
    }

    public function deassocierJuryAction() {
        /** @var These $these */
        $theseId = $this->params()->fromRoute('these');
        $these = $this->getTheseService()->getRepository()->find($theseId);

        /** @var Membre $membre */
        $idMembre = $this->params()->fromRoute('membre');
        $membre = $this->getMembreService()->find($idMembre);

        /** @var Acteur[] $acteurs */
        $acteurs = $this->getActeurService()->getRepository()->findActeurByThese($these);
        $acteur = null;
        foreach ($acteurs as $acteur_) {
            if ($acteur_->getIndividu() === $membre->getIndividu()) $acteur = $acteur_;
        }
        if (!$acteur) {
            throw new RuntimeException("Aucun acteur à deassocier !");
        } else {
            //retrait dans membre de soutenance
            $membre->setIndividu(null);
            $this->getMembreService()->update($membre);
            //retrait du role
            $this->getRoleService()->removeIndividuRole($acteur->getIndividu(), $acteur->getRole());

            $this->redirect()->toRoute('soutenance/presoutenance', ['these' => $these->getId()], [], true);
        }
    }
}