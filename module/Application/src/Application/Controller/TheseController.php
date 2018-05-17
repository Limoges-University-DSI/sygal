<?php

namespace Application\Controller;

use Application\Entity\Db\Attestation;
use Application\Entity\Db\Diffusion;
use Application\Entity\Db\Etablissement;
use Application\Entity\Db\Fichier;
use Application\Entity\Db\Individu;
use Application\Entity\Db\MailConfirmation;
use Application\Entity\Db\MetadonneeThese;
use Application\Entity\Db\NatureFichier;
use Application\Entity\Db\RdvBu;
use Application\Entity\Db\RecapBu;
use Application\Entity\Db\Role;
use Application\Entity\Db\SourceInterface;
use Application\Entity\Db\These;
use Application\Entity\Db\TypeValidation;
use Application\Entity\Db\Variable;
use Application\Entity\Db\VersionFichier;
use Application\Entity\Db\WfEtape;
use Application\Filter\IdifyFilterAwareTrait;
use Application\Form\AttestationTheseForm;
use Application\Form\ConformiteFichierForm;
use Application\Form\DiffusionTheseForm;
use Application\Form\MetadonneeTheseForm;
use Application\Form\RdvBuTheseDoctorantForm;
use Application\Form\RdvBuTheseForm;
use Application\Form\RecapBuForm;
use Application\Service\Etablissement\EtablissementServiceAwareTrait;
use Application\Service\Fichier\Exception\ValidationImpossibleException;
use Application\Service\Fichier\FichierServiceAwareTrait;
use Application\Service\MailConfirmationServiceAwareTrait;
use Application\Service\Notification\NotificationServiceAwareTrait;
use Application\Service\Role\RoleServiceAwareTrait;
use Application\Service\These\Convention\ConventionPdfExporter;
use Application\Service\These\PageDeGarde\PageDeCouverturePdfExporter;
use Application\Service\These\TheseServiceAwareTrait;
use Application\Service\UniteRecherche\UniteRechercheServiceAwareTrait;
use Application\Service\Validation\ValidationServiceAwareTrait;
use Application\Service\Variable\VariableServiceAwareTrait;
use Application\Service\VersionFichier\VersionFichierServiceAwareTrait;
use Application\Service\Workflow\WorkflowServiceAwareTrait;
use Application\View\Helper\Sortable;
use Doctrine\ORM\Tools\Pagination\Paginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;
use Notification\Notification;
use Retraitement\Exception\TimedOutCommandException;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenApp\Service\MessageCollectorAwareTrait;
use UnicaenApp\Traits\MessageAwareInterface;
use Zend\Form\Element\Hidden;
use Zend\Http\Response;
use Zend\Stdlib\ParametersInterface;
use Zend\View\Model\ViewModel;

class TheseController extends AbstractController
{
    use VariableServiceAwareTrait;
    use TheseServiceAwareTrait;
    use RoleServiceAwareTrait;
    use FichierServiceAwareTrait;
    use ValidationServiceAwareTrait;
    use MessageCollectorAwareTrait;
    use VersionFichierServiceAwareTrait;
    use WorkflowServiceAwareTrait;
    use NotificationServiceAwareTrait;
    use IdifyFilterAwareTrait;
    use EtablissementServiceAwareTrait;
    use EntityManagerAwareTrait;
    use MailConfirmationServiceAwareTrait;
    use UniteRechercheServiceAwareTrait;

    private $timeoutRetraitement;

    /**
     * @return ViewModel|Response
     */
    public function indexAction()
    {
        /** L'utilisateur est un doctorant, redirection vers l'accueil. **/
        if ($this->userContextService->getSelectedRoleDoctorant()) {
            return $this->redirect()->toRoute('home');
        }

        /** Application des filtres et tris par défaut.
         * Si aucun état de thèse n'est spécifié alors état = These::ETAT_EN_COURS
         **/
        $needsRedirect  = false;
        $queryParams    = $this->params()->fromQuery();
        $etatThese      = $this->params()->fromQuery($name = 'etatThese');
        if ($etatThese === null) { // null <=> paramètre absent
            $queryParams = array_merge($queryParams, [$name => These::ETAT_EN_COURS]);
            $needsRedirect = true;
        }
        $etablissement  = $this->params()->fromQuery($name = 'etablissement');
        if ($etablissement === null) { // null <=> paramètre absent
            $queryParams = array_merge($queryParams, [$name => ""]);
            $needsRedirect = true;
        }
        $sort = $this->params()->fromQuery('sort');
        if ($sort === null) { // null <=> paramètre absent
            $queryParams = array_merge($queryParams, ['sort' => 't.datePremiereInscription', 'direction' => Sortable::ASC]);
            $needsRedirect = true;
        }
        if ($needsRedirect) {
            return $this->redirect()->toRoute(null, [], ['query' => $queryParams], true);
        }

        /** Configuration du paginator **/
        $qb = $this->createQueryBuilder();
        $maxi = $this->params()->fromQuery('maxi', 50);
        $page = $this->params()->fromQuery('page', 1);
        $paginator = new \Zend\Paginator\Paginator(new DoctrinePaginator(new Paginator($qb, true)));
        $paginator
            ->setPageRange(30)
            ->setItemCountPerPage((int)$maxi)
            ->setCurrentPageNumber((int)$page);

        $text = $this->params()->fromQuery('text');

        /**
         * @var Etablissement[] $etablissements
         * $etablissements stocke la liste des établissements qui seront utilisés pour le filtrage
         * les critères sont les suivants:
         * - être un établissement crée par sygal (et ne pas liste les établissements de co-tutelles)
         * - ne pas être des établissements provenant de substitutions
         * - ne pas être la COMUE ... suite à l'interrogation obtenue en réunion
         */
        $etablissements = $this->etablissementService->getEtablissementsBySource(SourceInterface::CODE_SYGAL);
        $etablissements = array_filter($etablissements, function (Etablissement $etablissement) { return count($etablissement->getStructure()->getStructuresSubstituees())==0; });
        $etablissements = array_filter($etablissements, function (Etablissement $etablissement) { return $etablissement->getSigle() != "NU";});

        return new ViewModel([
            'theses' => $paginator,
            'text'   => $text,
            'roleDirecteurThese' => $this->roleService->getRepository()->findOneBy(['sourceCode' => Role::CODE_DIRECTEUR_THESE]),
            'etablissements' => $etablissements,
            'filtreEtablissement' => $etablissement,
            'etatThese' => $etatThese,
        ]);
    }

    public function rechercherAction()
    {
        $prg = $this->postRedirectGet();
        if ($prg instanceof \Zend\Http\PhpEnvironment\Response) {
            return $prg;
        }

        $queryParams = $this->params()->fromQuery();

        if (is_array($prg)) {
            if (isset($queryParams['page'])) {
                unset($queryParams['page']);
            }
            $queryParams['text'] = $prg['text'];
        }

        return $this->redirect()->toRoute('these', [], ['query' => $queryParams]);
    }


    private function createQueryBuilder() {
        $etatThese      = $this->params()->fromQuery($name = 'etatThese');
        $etabCode       = $this->params()->fromQuery($name = 'etablissement');
        $sort = $this->params()->fromQuery('sort');
        $text = $this->params()->fromQuery('text');
        $dir  = $this->params()->fromQuery('direction', Sortable::ASC);

        $qb = $this->theseService->getRepository()->createQueryBuilder('t')
            ->addSelect('di')->leftJoin('th.individu', 'di')
            ->addSelect('a')->leftJoin('t.acteurs', 'a')
            ->addSelect('i')->leftJoin('a.individu', 'i')
            ->addSelect('r')->leftJoin('a.role', 'r')
            ->andWhere('1 = pasHistorise(t)');

        if ($etatThese) {
            $qb->andWhere('t.etatThese = :etat')->setParameter('etat', $etatThese);
        }
        if ($etabCode) {
            // todo: utiliser le relation th.etablissement, voyons !!
            $etablissement = $this->etablissementService->getRepository()->findOneBy(["code" =>$etabCode]);
            $qb->andWhere('t.etablissement = :etablissement')->setParameter('etablissement', $etablissement);
        }
        $sortProps = $sort ? explode('+', $sort) : [];
        foreach ($sortProps as $sortProp) {
            if ($sortProp === 't.titre') {
                // trim et suppression des guillemets
                $sortProp = "TRIM(REPLACE($sortProp, CHR(34), ''))"; // CHR(34) <=> "
            }
            $qb->addOrderBy($sortProp, $dir);
        }

        /**
         * Filtres découlant du rôle de l'utilisateur.
         */
        $this->theseService->decorateQbFromUserContext($qb, $this->userContextService);

        /**
         * Prise en compte du champ de recherche textuelle.
         */
        if (strlen($text) > 1) {
            $results = $this->theseService->rechercherThese($text);
            $sourceCodes = array_unique(array_keys($results));
            if ($sourceCodes) {
                $qb
                    ->andWhere($qb->expr()->in('t.sourceCode', ':sourceCodes'))
                    ->setParameter('sourceCodes', $sourceCodes);
            }
            else {
                $qb->andWhere("0 = 1"); // i.e. aucune thèse trouvée
            }
        }

        return $qb;
    }


    public function roadmapAction()
    {
        $these = $this->requestedThese();

        $view = new ViewModel([
            'these' => $these,
        ]);
        $view->setTemplate('application/these/roadmap');

        return $view;
    }

    public function detailIdentiteAction()
    {
        $these = $this->requestedThese();
        $etablissement = $these->getEtablissement();



        $showCorrecAttendue =
            $these->getCorrectionAutorisee() &&
            count($this->validationService->getValidationsAttenduesPourCorrectionThese($these)) > 0;

        /**
         * @var Individu $individu
         * @var MailConfirmation $enCours
         * @var MailConfirmation $confirmee
         */
        $individu = $these->getDoctorant()->getIndividu();
        $enCours = $this->mailConfirmationService->getDemandeEnCoursByIndividu($individu);
        $confirmee = $this->mailConfirmationService->getDemandeConfirmeeByIndividu($individu);

        $mailContact = null;
        $etatMailContact = null;

        switch(true) {
            case($enCours !== null) :
                $mailContact = $enCours->getEmail();
                $etatMailContact = MailConfirmation::ENVOYER;
                break;
            case($confirmee !== null) :
                $mailContact = $confirmee->getEmail();
                $etatMailContact = MailConfirmation::CONFIRMER;
                break;
        }

        $unite = $these->getUniteRecherche();
        $rattachements = null;
        if ($unite !== null) $rattachements = $this->uniteRechercheService->findEtablissementRattachement($unite);

        //TODO JP remplacer dans modifierPersopassUrl();
        $urlModification = $this->url()->fromRoute('doctorant/modifier-persopass',['back' => 1, 'doctorant' => $these->getDoctorant()->getId()], [], true);

        $view = new ViewModel([
            'these'                     => $these,
            'etablissement'             => $etablissement,
            'estDoctorant'              => (bool)$this->userContextService->getSelectedRoleDoctorant(),
            'showCorrecAttendue'        => $showCorrecAttendue,
//            'modifierPersopassUrl'      => $this->urlDoctorant()->modifierPersopassUrl($these),
            'modifierPersopassUrl'      => $urlModification,
            'pvSoutenanceUrl'           => $this->urlThese()->depotFichiers($these, NatureFichier::CODE_PV_SOUTENANCE),
            'rapportSoutenanceUrl'      => $this->urlThese()->depotFichiers($these, NatureFichier::CODE_RAPPORT_SOUTENANCE),
            'preRapportSoutenanceUrl'   => $this->urlThese()->depotFichiers($these, NatureFichier::CODE_PRE_RAPPORT_SOUTENANCE),
            'demandeConfidentUrl'       => $this->urlThese()->depotFichiers($these, NatureFichier::CODE_DEMANDE_CONFIDENT),
            'prolongConfidentUrl'       => $this->urlThese()->depotFichiers($these, NatureFichier::CODE_PROLONG_CONFIDENT),
            'convMiseEnLigneUrl'        => $this->urlThese()->depotFichiers($these, NatureFichier::CODE_CONV_MISE_EN_LIGNE),
            'avenantConvMiseEnLigneUrl' => $this->urlThese()->depotFichiers($these, NatureFichier::CODE_AVENANT_CONV_MISE_EN_LIGNE),
            'nextStepUrl'               => $this->urlWorkflow()->nextStepBox($these, null, [
                WfEtape::PSEUDO_ETAPE_FINALE,
            ]),
            'mailContact'               => $mailContact,
            'etatMailContact'           => $etatMailContact,
            'rattachements'             => $rattachements,
        ]);
        $view->setTemplate('application/these/identite');

        return $view;
    }

    public function detailDescriptionAction()
    {
        $these = $this->requestedThese();

        $view = new ViewModel([
            'these'                  => $these,
            'modifierMetadonneesUrl' => $this->urlThese()->modifierMetadonneesUrl($these),
            'nextStepUrl'            => $this->urlWorkflow()->nextStepBox($these, null, [
                WfEtape::CODE_SIGNALEMENT_THESE,
                WfEtape::PSEUDO_ETAPE_FINALE,
            ]),
        ]);
        $view->setTemplate('application/these/description');

        return $view;
    }


    public function detailDepotAction()
    {
        $view = $this->detailDepotActionViewModel(false);
        $view->setTemplate('application/these/depot');

        return $view;
    }

    public function detailDepotVersionCorrigeeAction()
    {
        $view = $this->detailDepotActionViewModel(true);
        $view->setTemplate('application/these/depot-version-corrigee');

        return $view;
    }

    /**
     * @param bool $versionCorrigee
     * @return ViewModel
     */
    private function detailDepotActionViewModel($versionCorrigee = false)
    {
        $these = $this->requestedThese();

        $codeVersion = $versionCorrigee ?
            VersionFichier::CODE_ORIG_CORR :
            VersionFichier::CODE_ORIG;

        $view = new ViewModel([
            'these'            => $these,
            'theseUrl'         => $this->urlThese()->depotFichiers($these, NatureFichier::CODE_THESE_PDF, $codeVersion),
            'annexesUrl'       => $this->urlThese()->depotFichiers($these, NatureFichier::CODE_FICHIER_NON_PDF, $codeVersion),
            'attestationUrl'   => $this->urlThese()->attestationThese($these, $codeVersion),
            'diffusionUrl'     => $this->urlThese()->diffusionThese($these, $codeVersion),
            'nextStepUrl'      => $this->urlWorkflow()->nextStepBox($these, null, [
                WfEtape::CODE_DEPOT_VERSION_ORIGINALE,
                WfEtape::CODE_DEPOT_VERSION_ORIGINALE_CORRIGEE,
                WfEtape::CODE_ATTESTATIONS,
                WfEtape::CODE_AUTORISATION_DIFFUSION_THESE,
                WfEtape::PSEUDO_ETAPE_FINALE,
            ]),
        ]);

        return $view;
    }

    public function detailArchivageAction()
    {
        $view = $this->detailArchivageActionViewModel(false);

        return $view;
    }

    public function detailArchivageVersionCorrigeeAction()
    {
        $view = $this->detailArchivageActionViewModel(true);

        return $view;
    }

    /**
     * @param bool $versionCorrigee
     * @return Response|ViewModel
     */
    private function detailArchivageActionViewModel($versionCorrigee = false)
    {
        $these = $this->requestedThese();

        $version = $versionCorrigee ?
            VersionFichier::CODE_ORIG_CORR :
            VersionFichier::CODE_ORIG;

//        $theseFichiers = $these->getFichiersByNatureEtVersion(NatureFichier::CODE_THESE_PDF, $version, false);
        $theseFichiers = $this->fichierService->getRepository()->fetchFichiers($these, NatureFichier::CODE_THESE_PDF, $version, false);
        $fichierThese = current($theseFichiers);

        if (!$fichierThese) {
            return $this->redirect()->toUrl($this->urlThese()->depotThese($these));
        }

        if ($this->getRequest()->isPost()) {
            $action = $this->params()->fromPost('action', $this->params()->fromQuery('action'));
            if ('tester' === $action) {
                try {
                    $this->fichierService->validerFichier($fichierThese);
                }
                catch (ValidationImpossibleException $vie) {
                    // Le test d'archivabilité du fichier '%s' a rencontré un problème indépendant de notre volonté
                }
            }
            return $this->redirect()->refresh();
        }

        $theseFichiersItems = array_map(function (Fichier $fichier) use ($these) {
            return [
                'file'        => $fichier,
                'downloadUrl' => $this->urlFichierThese()->telechargerFichierThese($these, $fichier),
            ];
        }, $theseFichiers);

//        $theseFichiersRetraites = $these->getFichiersByNatureEtVersion(NatureFichier::CODE_THESE_PDF, $version, true);
        $theseFichiersRetraites = $this->fichierService->getRepository()->fetchFichiers($these, NatureFichier::CODE_THESE_PDF, $version, true);
        $fichierTheseRetraite = current($theseFichiersRetraites);

        $theseRetraiteeUrl = $this->urlThese()->depotFichiers($these, NatureFichier::CODE_THESE_PDF, $version, true);

        $view = new ViewModel([
            'these'                              => $these,
            'fichierThese'                       => $fichierThese,
            'fichierTheseRetraite'               => $fichierTheseRetraite,
            'theseFichiersItems'                 => $theseFichiersItems,
            'theseRetraiteeUrl'                  => $theseRetraiteeUrl,
            'testArchivabiliteTheseOriginaleUrl' => $this->urlThese()->testArchivabilite($these, $version),
            'archivabiliteTheseRetraiteeUrl'     => $this->urlThese()->archivabiliteThese($these, $version, true),
            'conformiteTheseRetraiteeUrl'        => $this->urlThese()->conformiteTheseRetraitee($these, $version),
            'nextStepUrl'                        => $this->urlWorkflow()->nextStepBox($these, null, [
                WfEtape::CODE_ARCHIVABILITE_VERSION_ORIGINALE,
                WfEtape::CODE_ARCHIVABILITE_VERSION_ORIGINALE_CORRIGEE,
                WfEtape::CODE_ARCHIVABILITE_VERSION_ARCHIVAGE,
                WfEtape::CODE_ARCHIVABILITE_VERSION_ARCHIVAGE_CORRIGEE,
                WfEtape::CODE_DEPOT_VERSION_ARCHIVAGE,
                WfEtape::CODE_DEPOT_VERSION_ARCHIVAGE_CORRIGEE,
                WfEtape::CODE_VERIFICATION_VERSION_ARCHIVAGE,
                WfEtape::CODE_VERIFICATION_VERSION_ARCHIVAGE_CORRIGEE,
                WfEtape::PSEUDO_ETAPE_FINALE,
            ]),
        ]);
        $view->setTemplate('application/these/archivage');

        return $view;
    }

    public function detailRdvBuAction()
    {
        $estDoctorant = (bool) $this->userContextService->getSelectedRoleDoctorant();
        $these = $this->requestedThese();

        $versionArchivable = $this->fichierService->getRepository()->getVersionArchivable($these);

        $view = new ViewModel([
            'these'        => $these,
            'estDoctorant' => $estDoctorant,
            'modifierUrl'  => $this->urlThese()->modifierRdvBuUrl($these),
            'validerUrl'   => $this->urlThese()->validerRdvBuUrl($these),
            'devaliderUrl' => $this->urlThese()->devaliderRdvBuUrl($these),
            'validation'   => $these->getValidation(TypeValidation::CODE_RDV_BU),
            'msgCollector' => $this->getServiceMessageCollector(),
            'nextStepUrl'  => $this->urlWorkflow()->nextStepBox($these, null, [
                WfEtape::CODE_RDV_BU_SAISIE_DOCTORANT,
                WfEtape::CODE_RDV_BU_VALIDATION_BU,
            ]),
            'versionArchivable' => $versionArchivable,
        ]);

        $view->setTemplate('application/these/rdv-bu' . ($estDoctorant ? '-doctorant' : null));

        return $view;
    }

    public function modifierRdvBuAction()
    {
        $these = $this->requestedThese();
        $estDoctorant = (bool) $this->userContextService->getSelectedRoleDoctorant();

        $rdvBu = $these->getRdvBu() ?: new RdvBu($these);
        $rdvBu->setVersionArchivableFournie($this->fichierService->getRepository()->existeVersionArchivable($these));

        /** @var RdvBuTheseForm|RdvBuTheseDoctorantForm $form */
        $form = $this->getServiceLocator()->get('formElementManager')->get($estDoctorant ? 'RdvBuTheseDoctorantForm' : 'RdvBuTheseForm');
        $form->bind($rdvBu);

        if ($this->getRequest()->isPost()) {
            $post = $this->getRequest()->getPost();
            $form->setData($post);
            if ($form->isValid()) {
                /** @var RdvBu $formRdvBu */
                $formRdvBu = $form->getData();
                $inserting = $formRdvBu->getId() === null;
                $this->theseService->updateRdvBu($these, $formRdvBu);

                $this->flashMessenger()->addSuccessMessage("Informations enregistrées avec succès.");
                $this->flashMessenger()->addSuccessMessage($this->theseService->getMessage('<br>', MessageAwareInterface::SUCCESS));
                $this->flashMessenger()->addInfoMessage($this->theseService->getMessage('<br>', MessageAwareInterface::INFO));

                // notification par mail à la BU quand le doctorant saisit les infos pour la 1ere fois
                if ($estDoctorant && $inserting) {

                    $subject = sprintf("%s Saisie des informations pour la prise de rendez-vous BU",
                        $these->getLibelleDiscipline());
                    $notif = new Notification();
                    $notif
                        ->setSubject($subject)
                        ->setTemplatePath('application/these/mail/notif-modif-rdv-bu-doctorant')
                        ->setTemplateVariables([
                            'these'    => $these,
                            'updating' => !$inserting,
                        ]);
                    $this->notificationService->triggerNotificationBU($notif, $these);

//                    $notificationLog = $this->notificationService->getMessage('<br>', 'info');
//                    $this->flashMessenger()->addInfoMessage($notificationLog);
                }

                if (! $this->getRequest()->isXmlHttpRequest()) {
                    return $this->redirect()->toRoute('these/rdv-bu', [], [], true);
                }
            }
        }

        $form->setAttribute('action', $this->urlThese()->modifierRdvBuUrl($these));

        $vm = new ViewModel([
            'these' => $these,
            'form'  => $form,
            'title' => "Rendez-vous BU"
        ]);

        $vm->setTemplate('application/these/modifier-rdv-bu' . ($estDoctorant ? '-doctorant' : null));

        return $vm;
    }

    public function validationTheseCorrigeeAction()
    {
        $these = $this->requestedThese();

        $view = new ViewModel([
            'these'                           => $these,
            'validationDepotTheseCorrigeeUrl' => $this->urlThese()->validationDepotTheseCorrigeeUrl($these),
            'validationCorrectionTheseUrl'    => $this->urlThese()->validationCorrectionTheseUrl($these),
            'nextStepUrl'                     => $this->urlWorkflow()->nextStepBox($these, null, [
                WfEtape::CODE_DEPOT_VERSION_CORRIGEE_VALIDATION_DOCTORANT,
                WfEtape::CODE_DEPOT_VERSION_CORRIGEE_VALIDATION_DIRECTEUR,
            ], [
                'message' => "Il vous reste encore à fournir à la BU un exemplaire imprimé de la version corrigée pour valider le dépôt.",
            ]),
        ]);

        return $view;
    }

    public function validerFichierAction()
    {
        $these = $this->requestedThese();

        $view = new ViewModel([
            'these' => $these,
            'testerFichierUrl' => $this->urlThese()->modifierMetadonneesUrl($these),
        ]);
        $view->setTemplate('application/these/archivage');

        return $view;
    }

    public function theseAction()
    {
        $these = $this->requestedThese();
        $estCorrige = (bool) $this->params()->fromQuery('corrige', false);
        $estExpurge = (bool) $this->params()->fromQuery('expurge', false);
        $inclureValidite = (bool) $this->params()->fromQuery('inclureValidite', false);
        $validerAuto = (bool) $this->params()->fromQuery('validerAuto', false);
        $version = $this->fichierService->fetchVersionFichier($this->params()->fromQuery('version'));

        $nature = $this->fichierService->fetchNatureFichier(NatureFichier::CODE_THESE_PDF);

        $titre = $estExpurge ?
            sprintf("Version %s expurgée pour la diffusion", $estCorrige ? "corrigée" : "") :
            sprintf("Thèse %s au format PDF", $estCorrige ? "corrigée" : "");

        $form = $this->uploader()->getForm();
        $form->setAttribute('id', uniqid('form-'));
        $form->setUploadMaxFilesize(FichierTheseController::UPLOAD_MAX_FILESIZE);
//        $form->addElement((new Hidden('annexe'))->setValue(0));
        $form->addElement((new Hidden('nature'))->setValue($this->idify($nature)));
        $form->addElement((new Hidden('version'))->setValue($this->idify($version)));
        if ($validerAuto) {
            $form->addElement((new Hidden('validerAuto'))->setValue(1));
        }

        $view = new ViewModel([
            'titre'          => $titre,
            'these'          => $these,
            'info'           => $estExpurge ? "<strong>NB</strong>: rassembler la thèse sur un seul fichier au format PDF." : null,
            'uploadUrl'      => $this->urlFichierThese()->televerserFichierThese($these),
            'theseListUrl'   => $this->urlFichierThese()->listerFichiers($these, $nature, $version, false, ['inclureValidite' => $inclureValidite]),
            'nature'         => $nature,
            'versionFichier' => $version,
            'etabComue'      => $this->etablissementService->getRepository()->libelle(Etablissement::CODE_COMUE),
        ]);
        $view->setTemplate('application/these/depot/these');

        return $view;
    }

    /**
     * - Affichage de la thèse retraitée.
     * - Retraitement automatique de la thèse
     *
     * NB: En fonction de la propriété 'timeoutRetraitement', un timeout peut être appliqué au lancement du
     * script de retraitement. Si ce timout est atteint, l'exécution du script est interrompue
     * et une exception TimedOutCommandException est levée.
     *
     * @return ViewModel|Response
     */
    public function theseRetraiteeAction()
    {
        $these = $this->requestedThese();
        $nature = $this->fichierService->fetchNatureFichier(NatureFichier::CODE_THESE_PDF);
        $version = $this->fichierService->fetchVersionFichier($this->params()->fromQuery('version'));

        $versionOriginale = $this->fichierService->fetchVersionFichier(
            $version->estVersionCorrigee() ? VersionFichier::CODE_ORIG_CORR : VersionFichier::CODE_ORIG
        );
        $versionArchivage = $this->fichierService->fetchVersionFichier(
            $version->estVersionCorrigee() ? VersionFichier::CODE_ARCHI_CORR : VersionFichier::CODE_ARCHI
        );

        /** @var Fichier $fichierVersionOriginale */
//        $fichierVersionOriginale = $these->getFichiersByNatureEtVersion($nature, $versionOriginale)->first();
        $fichierVersionOriginale = current($this->fichierService->getRepository()->fetchFichiers($these, $nature, $versionOriginale, false));
        /** @var Fichier $fichierVersionArchivage */
//        $fichierVersionArchivage = $these->getFichiersByNatureEtVersion($nature, $versionArchivage, true)->first() ?: null;
        $fichierVersionArchivage = current($this->fichierService->getRepository()->fetchFichiers($these, $nature, $versionArchivage,true)) ?: null;

        $form = $this->uploader()->getForm();
        $form->setAttribute('id', uniqid('form-'));
        $form->setUploadMaxFilesize(FichierTheseController::UPLOAD_MAX_FILESIZE);
        $form->addElement((new Hidden('validerAuto'))->setValue(1));
        $form->addElement((new Hidden('retraitement'))->setValue(Fichier::RETRAITEMENT_MANU));
        $form->addElement((new Hidden('nature'))->setValue($this->idify($nature)));
        $form->addElement((new Hidden('version'))->setValue($this->idify($versionArchivage)));

        if ($this->getRequest()->isPost()) {
            if ('creerVersionRetraitee' === $this->params()->fromQuery('action')) {
                try {
                    // Un timeout peut être appliqué au lancement du  script de retraitement.
                    // Si ce timout est atteint, l'exécution du script est interrompue
                    // et une exception TimedOutCommandException est levée.
                    $timeout = $this->timeoutRetraitement;
                    $fichierVersionArchivage = $this->fichierService->creerFichierRetraite($fichierVersionOriginale, $timeout);
                    try {
                        $this->fichierService->validerFichier($fichierVersionArchivage);
                    } catch (ValidationImpossibleException $vie) {
                        // Le test d'archivabilité du fichier '%s' a rencontré un problème indépendant de notre volonté
                    }
                } catch (TimedOutCommandException $toce) {
                    // relancer le retraitement en tâche de fond
                    $this->fichierService->creerFichierRetraiteAsync($fichierVersionOriginale);
                } catch (RuntimeException $re) {
                    // erreur prévue
                }
            }
            return $this->redirect()->toRoute(null, [], ['query'=>$this->params()->fromQuery()], true);
        }

        $theseRetraiteeAutoListUrl = $this->urlFichierThese()->listerFichiers(
            $these, $nature, $versionArchivage, Fichier::RETRAITEMENT_AUTO, ['inclureValidite' => true]);
        $theseRetraiteeManuListUrl = $this->urlFichierThese()->listerFichiers(
            $these, $nature, $versionArchivage, Fichier::RETRAITEMENT_MANU, ['inclureValidite' => true]);

        $view = new ViewModel([
            'these'                     => $these,
            'info'                      => null,
            'uploadUrl'                 => $this->urlFichierThese()->televerserFichierThese($these),
            'theseRetraiteeAutoListUrl' => $theseRetraiteeAutoListUrl,
            'theseRetraiteeManuListUrl' => $theseRetraiteeManuListUrl,
//            'creerVersionRetraiteeUrl'  => $this->url()->fromRoute(null, [], ['query' => ['action' => 'creerVersionRetraitee']], true),
            'creerVersionRetraiteeUrl'  => $this->urlThese()->creerVersionRetraitee($these, $versionArchivage),
            'fichierVOouVOC'            => $fichierVersionOriginale,
            'fichierVAouVAC'            => $fichierVersionArchivage,
            'nature'                    => $nature,
            'versionFichier'            => $versionArchivage,
        ]);
        $view->setTemplate('application/these/archivage/these-retraitee');

        return $view;
    }

    public function annexesAction()
    {
        $these = $this->requestedThese();
        $estCorrige = (bool) $this->params()->fromQuery('corrige', false);
        $estExpurge = (bool) $this->params()->fromQuery('expurge', false);
        $version = $this->fichierService->fetchVersionFichier($this->params()->fromQuery('version'));
        $nature = $this->fichierService->fetchNatureFichier(NatureFichier::CODE_FICHIER_NON_PDF);

        //$hasFichierThese = $these->getFichiersBy(false, false, false)->count() > 0;
        //$hasFichiersAnnexesThese = $these->getFichiersBy(true, false, false)->count() > 0;
        $hasFichierThese = ! empty($this->fichierService->getRepository()->fetchFichiers($these, NatureFichier::CODE_THESE_PDF, $version, false));
        $hasFichiersAnnexesThese = ! empty($this->fichierService->getRepository()->fetchFichiers($these, NatureFichier::CODE_FICHIER_NON_PDF, $version, false));

        $titre = $estExpurge ?
            sprintf("Fichiers %s expurgés hors PDF", $estCorrige ? "corrigés" : "") :
            sprintf("Fichiers %s hors PDF", $estCorrige ? "corrigés" : "");

        $form = $this->uploader()->getForm();
        $form->setAttribute('id', uniqid('form-'));
        $form->setUploadMaxFilesize(FichierTheseController::UPLOAD_MAX_FILESIZE);
        $form->addElement((new Hidden('annexe'))->setValue(1));
        $form->addElement((new Hidden('nature'))->setValue($this->idify($nature)));
        $form->addElement((new Hidden('version'))->setValue($this->idify($version)));

        $view = new ViewModel([
            'titre'          => $titre,
            'these'          => $these,
            'uploadUrl'      => $this->urlFichierThese()->televerserFichierThese($these),
            'annexesListUrl' => $this->urlFichierThese()->listerFichiers($these, $nature, $version),
            'nature'         => $nature,
            'versionFichier' => $version,
            'hasFichierThese' => $hasFichierThese,
            'hasFichiersAnnexesThese' => $hasFichiersAnnexesThese,
        ]);
        $view->setTemplate('application/these/depot/annexes');

        return $view;
    }

    /**
     * @return ViewModel
     */
    public function depotPvSoutenanceAction()
    {
        $these = $this->requestedThese();
        $dateSoutenanceDepassee = $these->getDateSoutenance() && $these->getDateSoutenance() < new \DateTime();

        $view = $this->createViewForFichierAction(NatureFichier::CODE_PV_SOUTENANCE);
        $view->setVariable('isVisible', $dateSoutenanceDepassee);
        $view->setTemplate('application/these/depot/fichier-divers');

        return $view;
    }

    /**
     * @return ViewModel
     */
    public function depotRapportSoutenanceAction()
    {
        $these = $this->requestedThese();
        $dateSoutenanceDepassee = $these->getDateSoutenance() && $these->getDateSoutenance() < new \DateTime();

        $view = $this->createViewForFichierAction(NatureFichier::CODE_RAPPORT_SOUTENANCE);
        $view->setVariable('isVisible', $dateSoutenanceDepassee);
        $view->setTemplate('application/these/depot/fichier-divers');

        return $view;
    }

    /**
     * @return ViewModel
     */
    public function depotPreRapportSoutenanceAction()
    {
        $these = $this->requestedThese();
        $dateSoutenanceDepassee = $these->getDateSoutenance() && $these->getDateSoutenance() < new \DateTime();

        $view = $this->createViewForFichierAction(NatureFichier::CODE_PRE_RAPPORT_SOUTENANCE);
        $view->setVariable('isVisible', $dateSoutenanceDepassee);
        $view->setVariable('maxUploadableFilesCount', 3);
        $view->setTemplate('application/these/depot/fichier-divers');

        return $view;
    }

    /**
     * @return ViewModel
     */
    public function depotDemandeConfidentAction()
    {
        $view = $this->createViewForFichierAction(NatureFichier::CODE_DEMANDE_CONFIDENT);
        $view->setTemplate('application/these/depot/fichier-divers');

        return $view;
    }

    /**
     * @return ViewModel
     */
    public function depotProlongConfidentAction()
    {
        $view = $this->createViewForFichierAction(NatureFichier::CODE_PROLONG_CONFIDENT);
        $view->setTemplate('application/these/depot/fichier-divers');

        return $view;
    }

    /**
     * @return ViewModel
     */
    public function depotConvMiseEnLigneAction()
    {
        $view = $this->createViewForFichierAction(NatureFichier::CODE_CONV_MISE_EN_LIGNE);
        $view->setTemplate('application/these/depot/fichier-divers');

        return $view;
    }

    /**
     * @return ViewModel
     */
    public function depotAvenantConvMiseEnLigneAction()
    {
        $view = $this->createViewForFichierAction(NatureFichier::CODE_AVENANT_CONV_MISE_EN_LIGNE);
        $view->setTemplate('application/these/depot/fichier-divers');

        return $view;
    }

    /**
     * @param string $codeNatureFichier
     * @return ViewModel
     */
    private function createViewForFichierAction($codeNatureFichier)
    {
        $these = $this->requestedThese();
        $nature = $this->fichierService->fetchNatureFichier($codeNatureFichier);
        $version = $this->fichierService->fetchVersionFichier(VersionFichier::CODE_ORIG);

        if (!$nature) {
            throw new RuntimeException("Nature de fichier introuvable: " . $codeNatureFichier);
        }

        $form = $this->uploader()->getForm();
        $form->setAttribute('id', uniqid('form-'));
        $form->setUploadMaxFilesize('10M');
        $form->addElement((new Hidden('nature'))->setValue($this->idify($nature)));
        $form->addElement((new Hidden('version'))->setValue($this->idify($version)));
        $form->get('files')->setLabel("")->setAttribute('multiple', false)/*->setAttribute('accept', '.pdf')*/;

        $view = new ViewModel([
            'these'           => $these,
            'uploadUrl'       => $this->urlFichierThese()->televerserFichierThese($these),
            'fichiersListUrl' => $this->urlFichierThese()->listerFichiers($these, $nature),
            'nature'          => $nature,
            'version'         => $version,
        ]);

        return $view;
    }

    public function testArchivabiliteAction()
    {
        $these = $this->requestedThese();
        $version = $this->fichierService->fetchVersionFichier($this->params()->fromQuery('version'));

//        $theseFichiers = $these->getFichiersByNatureEtVersion(NatureFichier::CODE_THESE_PDF, $version);
        $theseFichiers = $this->fichierService->getRepository()->fetchFichiers($these, NatureFichier::CODE_THESE_PDF, $version, false);
        /** @var Fichier $fichierThese */
        $fichierThese = current($theseFichiers);

        if ($this->getRequest()->isPost()) {

            //TODO move this to the suppr action
            // retrait de la validation précédente si le fichier est identique afin de pouvoir retester l'archivabilité après effacement
            $qb = $this->validationService->getRepository()->createQueryBuilder('v')
                ->where("t.id = :theseid")
                ->andWhere("tv.code = :type");
            $qb->setParameter(":theseid", $these->getId());
            $qb->setParameter(":type", TypeValidation::CODE_DEPOT_THESE_CORRIGEE);
            $res = $qb->getQuery()->getResult();

            if(isset($res) && count($res) > 0) {
                //echo "something is here !"."<br/>";
                $validation = $res[0];
                $this->validationService->getEntityManager()->remove($validation);
                $this->validationService->getEntityManager()->flush();
            }

            $action = $this->params()->fromPost('action', $this->params()->fromQuery('action'));
            if ('tester' === $action) {
                try {
                    $validite = $this->fichierService->validerFichier($fichierThese);

                    // création automatique d'une validation du dépôt de la version corrigée (par le doctorant)
                    if ($validite->getEstValide() && $version->estVersionCorrigee()) {
                        $this->validationService->validateDepotTheseCorrigee($these);

                        // envoi de mail aux directeurs de thèse
                        $this->notificationService->triggerValidationDepotTheseCorrigee($these);
                    }
                } catch (ValidationImpossibleException $vie) {
                    // Le test d'archivabilité du fichier '%s' a rencontré un problème indépendant de notre volonté
                }
            }

            return $this->redirect()->toUrl($this->urlThese()->archivageThese($these, $version->getCode()));
        }

        $view = new ViewModel([
            'these'                => $these,
            'fichierThese'         => $fichierThese,
            'testArchivabiliteUrl' => $this->urlThese()->testArchivabilite($these, $version->getCode()),
        ]);
        $view->setTemplate('application/these/archivage/test-archivabilite');

        return $view;
    }

    public function archivabiliteTheseAction()
    {
        $these = $this->requestedThese();
        $version = $this->fichierService->fetchVersionFichier($this->params()->fromQuery('version'));
        $retraite = true;

        $codeVersionRetraitee = $version->estVersionCorrigee() ?
            VersionFichier::CODE_ARCHI_CORR :
            VersionFichier::CODE_ARCHI;

//        $theseFichiersRetraite = $these->getFichiersByNatureEtVersion(NatureFichier::CODE_THESE_PDF, $codeVersionRetraitee, true);
        $theseFichiersRetraite = $this->fichierService->getRepository()->fetchFichiers($these, NatureFichier::CODE_THESE_PDF, $codeVersionRetraitee, true);
        $fichierTheseRetraite = current($theseFichiersRetraite);

        $variableEmailAssist = $this->variableService->getRepository()->findByCodeAndThese(Variable::CODE_EMAIL_ASSISTANCE, $these);

        $view = new ViewModel([
            'these'    => $these,
            'fichier'  => $fichierTheseRetraite,
            'retraite' => $retraite,
            'contact'  => $variableEmailAssist->getValeur(),
        ]);
        $view->setTemplate('application/these/archivage/archivabilite-these');

        return $view;
    }

    public function conformiteTheseRetraiteeAction()
    {
        $these = $this->requestedThese();
        $version = $this->fichierService->fetchVersionFichier($this->params()->fromQuery('version'));

        $versionArchivage = $this->fichierService->fetchVersionFichier(
            $version->estVersionCorrigee() ? VersionFichier::CODE_ARCHI_CORR : VersionFichier::CODE_ARCHI
        );

//        $fichier = $these->getFichiersByNatureEtVersion(NatureFichier::CODE_THESE_PDF, $versionArchivage, true)->first() ?: null;
        $fichier = current($this->fichierService->getRepository()->fetchFichiers($these, NatureFichier::CODE_THESE_PDF, $versionArchivage, true)) ?: null;

        $variableEmailAssist = $this->variableService->getRepository()->findByCodeAndThese(Variable::CODE_EMAIL_ASSISTANCE, $these);

        $view = new ViewModel([
            'these'                     => $these,
            'fichierTheseRetraite'      => $fichier,
            'validerFichierRetraiteUrl' => $this->urlThese()->certifierConformiteTheseRetraiteUrl($these, $versionArchivage),
            'contact'                   => $variableEmailAssist->getValeur(),
        ]);
        $view->setTemplate('application/these/archivage/conformite-these-retraitee');

        return $view;
    }

    public function attestationAction()
    {
        $these = $this->requestedThese();
        $attestation = $these->getAttestation();
        $version = $this->fichierService->fetchVersionFichier($this->params()->fromQuery('version'));
        $hasFichierThese = ! empty($this->fichierService->getRepository()->fetchFichiers($these, NatureFichier::CODE_THESE_PDF, $version, false));

        $versionInitialeAtteignable = $this->workflowService->findOneByEtape($these, WfEtape::CODE_ATTESTATIONS)->getAtteignable();
        $versionCorrigeeAtteignable = $this->workflowService->findOneByEtape($these, WfEtape::CODE_ATTESTATIONS_VERSION_CORRIGEE)->getAtteignable();
        $visible =
            $version->estVersionCorrigee() && $versionCorrigeeAtteignable ||
            !$version->estVersionCorrigee() && $versionInitialeAtteignable && !$versionCorrigeeAtteignable;

        if (! $visible) {
            return false;
        }

        $form = $this->getAttestationTheseForm();




        $view = new ViewModel([
            'these'                  => $these,
            'version'                => $version,
            'attestation'            => $attestation,
            'form'                   => $form,
            'modifierAttestationUrl' => $this->urlThese()->modifierAttestationUrl($these),
            'hasFichierThese'        => $hasFichierThese,
        ]);
        $view->setTemplate('application/these/attestation');

        return $view;
    }

    public function modifierAttestationAction()
    {
        $these = $this->requestedThese();
        $form = $this->getAttestationTheseForm();

        if ($this->getRequest()->isPost()) {
            /** @var ParametersInterface $post */
            $post = $this->getRequest()->getPost();
            $form->setData($post);
            $isValid = $form->isValid();
            if ($isValid) {
                /** @var Attestation $attestation */
                $attestation = $form->getData();
                $this->theseService->updateAttestation($these, $attestation);
                $this->flashMessenger()->addSuccessMessage("Réponses au questionnaire enregistrées avec succès.");

                if (! $this->getRequest()->isXmlHttpRequest()) {
                    return $this->redirect()->toRoute('these/depot', [], [], true);
                }
            }
        }

        $form->setAttribute('action', $this->urlThese()->modifierAttestationUrl($these));

        return new ViewModel([
            'these' => $these,
            'form'  => $form,
            'title' => "Attestations",
        ]);
    }

    /**
     * @return AttestationTheseForm
     */
    private function getAttestationTheseForm()
    {
        $these = $this->requestedThese();

        /** @var AttestationTheseForm $form */
        $form = $this->getServiceLocator()->get('formElementManager')->get('AttestationTheseForm');

        $attestation = $these->getAttestation();

        if ($attestation === null) {
            // si l'on est dans le cadre du dépôt de la version corrigée, on rappelle les infos historisées
            if ($this->existeVersionCorrigee()) {
                $attestations = $these->getAttestations($historise = true);
                $attestationPrec = $attestations->last() ?: null; // la plus récente

                /** @var Attestation $attestation */
                $attestation = clone $attestationPrec;
            } else {
                $attestation = new Attestation();
                $attestation->setThese($these);
            }
        }

        $form->bind($attestation);

        return $form;
    }

    /**
     * @var bool
     */
    protected $existeVersionCorrigee = null;

    /**
     * Si le fichier de la thèse originale est une version corrigée, on est dans le cadre d'un dépôt d'une version
     * corrigée et cette fonction retourne true.
     *
     * @param These|null $these
     * @return bool
     */
    private function existeVersionCorrigee(These $these = null)
    {
        if ($these !== null) {
//            return $these->getFichiersByVersion(VersionFichier::CODE_ORIG_CORR, false)->count() > 0;
            return (!empty($this->fichierService->getRepository()->fetchFichiers($these, NatureFichier::CODE_THESE_PDF , VersionFichier::CODE_ORIG_CORR)));
        }
        if ($this->existeVersionCorrigee !== null) {
            return $this->existeVersionCorrigee;
        }
        if ($these === null) {
            $these = $this->requestedThese();
        }

       // $this->existeVersionCorrigee = $these->getFichiersByVersion(VersionFichier::CODE_ORIG_CORR, false)->count() > 0;
        $this->existeVersionCorrigee = ! empty($this->fichierService->getRepository()->fetchFichiers($these, NatureFichier::CODE_THESE_PDF , VersionFichier::CODE_ORIG_CORR));

        return $this->existeVersionCorrigee;
    }

    public function diffusionAction()
    {
        $these = $this->requestedThese();
        $version = $this->fichierService->fetchVersionFichier($this->params()->fromQuery('version'));
        $hasFichierThese = ! empty($this->fichierService->getRepository()->fetchFichiers($these, NatureFichier::CODE_THESE_PDF, $version, false));

        $versionInitialeAtteignable = $this->workflowService->findOneByEtape($these, WfEtape::CODE_AUTORISATION_DIFFUSION_THESE)->getAtteignable();
        $versionCorrigeeAtteignable = $this->workflowService->findOneByEtape($these, WfEtape::CODE_AUTORISATION_DIFFUSION_THESE_VERSION_CORRIGEE)->getAtteignable();
        $visible =
            $version->estVersionCorrigee() && $versionCorrigeeAtteignable ||
            !$version->estVersionCorrigee() && $versionInitialeAtteignable && !$versionCorrigeeAtteignable;

        if (! $visible) {
            return false;
        }

        /** @var DiffusionTheseForm $form */
        $form = $this->getServiceLocator()->get('formElementManager')->get('DiffusionTheseForm');

        $versionExpurgee = $version->estVersionCorrigee() ? VersionFichier::CODE_DIFF_CORR : VersionFichier::CODE_DIFF;
        $theseFichiersExpurges = $this->fichierService->getRepository()->fetchFichiers($these, NatureFichier::CODE_THESE_PDF, $versionExpurgee, false);
        $annexesFichiersExpurges = $this->fichierService->getRepository()->fetchFichiers($these, NatureFichier::CODE_FICHIER_NON_PDF, $versionExpurgee, false);

        if ($diffusion = $these->getDiffusion()) {
            $form->bind($diffusion);
        }

        $theseFichiersExpurgesItems = array_map(function (Fichier $fichier) use ($these) {
            return [
                'file'          => $fichier,
                'apercevoirUrl' => $this->urlFichierThese()->apercevoirFichierThese($these, $fichier),
                'downloadUrl'   => $this->urlFichierThese()->telechargerFichierThese($these, $fichier),
            ];
        }, $theseFichiersExpurges);
        $annexesFichiersExpurgesItems = array_map(function (Fichier $fichier) use ($these) {
            return [
                'file'          => $fichier,
                'apercevoirUrl' => $this->urlFichierThese()->apercevoirFichierThese($these, $fichier),
                'downloadUrl'   => $this->urlFichierThese()->telechargerFichierThese($these, $fichier),
            ];
        }, $annexesFichiersExpurges);

        $view = new ViewModel([
            'these'                        => $these,
            'version'                      => $version,
            'form'                         => $form,
            'theseFichiersExpurgesItems'   => $theseFichiersExpurgesItems,
            'annexesFichiersExpurgesItems' => $annexesFichiersExpurgesItems,
            'modifierDiffusionUrl'         => $this->urlThese()->modifierDiffusionUrl($these),
            'exporterConventionMelUrl'     => $this->urlThese()->exporterConventionMiseEnLigneUrl($these),
            'hasFichierThese'              => $hasFichierThese,
        ]);
        $view->setTemplate('application/these/diffusion');

        return $view;
    }

    public function modifierDiffusionAction()
    {
        $these = $this->requestedThese();

        // si le fichier de la thèse originale est une version corrigée, la version de diffusion est aussi en version corrigée
        //$existeVersionOrigCorrig = $these->getFichiersByVersion(VersionFichier::CODE_ORIG_CORR, false)->count() > 0;
        $existeVersionOrigCorrig = ! empty($this->fichierService->getRepository()->fetchFichiers($these, NatureFichier::CODE_THESE_PDF , VersionFichier::CODE_ORIG_CORR));
        $version = $existeVersionOrigCorrig ? VersionFichier::CODE_DIFF_CORR : VersionFichier::CODE_DIFF;

        $form = $this->getDiffusionForm($version);

        if ($this->getRequest()->isPost()) {
            /** @var ParametersInterface $post */
            $post = $this->getRequest()->getPost();
            $form->setData($post);
            $isValid = $form->isValid();
            if ($isValid) {
                $diffusion = $form->getData();
                $this->theseService->updateDiffusion($these, $diffusion);
                $this->flashMessenger()->addSuccessMessage("Réponses au questionnaire enregistrées avec succès.");

                // suppression des fichiers expurgés éventuellement déposés en l'absence de pb de droit d'auteur
                $besoinVersionExpurgee = ! $diffusion->getDroitAuteurOk();
//                $fichiersExpurgesDeposes = $these->getFichiersBy(null, true, false, $version);
                $fichiersExpurgesDeposes = $this->fichierService->getRepository()->fetchFichiers($these, null , $version, false);
                if (! $besoinVersionExpurgee && !empty($fichiersExpurgesDeposes)) {
                    $this->fichierService->deleteFichiers($fichiersExpurgesDeposes);
                    $this->flashMessenger()->addSuccessMessage("Les fichiers expurgés fournis devenus inutiles ont été supprimés.");
                }

                if (! $this->getRequest()->isXmlHttpRequest()) {
                    return $this->redirect()->toRoute('these/depot', [], [], true);
                }
            }
        }

        $form->setAttribute('action', $this->urlThese()->modifierDiffusionUrl($these));

        return new ViewModel([
            'these'      => $these,
            'form'       => $form,
            'title'      => "Autorisation de diffusion",
            'theseUrl'   => $this->urlThese()->depotFichiers($these, NatureFichier::CODE_THESE_PDF, $version),
//            'annexesUrl' => $this->urlThese()->fichiersAnnexes($these, NatureFichier::CODE_FICHIER_NON_PDF, $version),
            'annexesUrl' => $this->urlThese()->depotFichiers($these, NatureFichier::CODE_FICHIER_NON_PDF, $version),
        ]);
    }

    /**
     * @param string $version
     * @return DiffusionTheseForm
     */
    private function getDiffusionForm($version)
    {
        $these = $this->requestedThese();

        /** @var DiffusionTheseForm $form */
        $form = $this->getServiceLocator()->get('formElementManager')->get('DiffusionTheseForm');
        $form->setVersionFichier($this->versionFichierService->getRepository()->findOneByCode($version));

        $diffusion = $these->getDiffusion();

        if ($diffusion === null) {
            // si l'on est dans le cadre du dépôt de la version corrigée, on rappelle les infos historisées
            if ($this->existeVersionCorrigee()) {
                $diffusions = $these->getDiffusions($historise = true);
                $diffusionPrec = $diffusions->last() ?: null; // la plus récente

                /** @var Diffusion $diffusion */
                $diffusion = clone $diffusionPrec;
            } else {
                $diffusion = new Diffusion();
                $diffusion->setThese($these);
            }
        }

        $form->bind($diffusion);

        return $form;
    }

    public function exporterConventionMiseEnLigneAction()
    {
        $these = $this->requestedThese();

        /** @var DiffusionTheseForm $form */
        $form = $this->getServiceLocator()->get('formElementManager')->get('DiffusionTheseForm');

        $codes = [
            Variable::CODE_ETB_LIB,
            Variable::CODE_ETB_ART_ETB_LIB,
            Variable::CODE_ETB_LIB_TIT_RESP,
            Variable::CODE_ETB_LIB_NOM_RESP,
        ];
        $dateObs = $these->getDateSoutenance() ?: $these->getDatePrevisionSoutenance();
        $variableRepo = $this->variableService->getRepository();
        $vars = $variableRepo->findByCodeAndEtab($codes, $these->getEtablissement(), $dateObs);
        $etab = $vars[Variable::CODE_ETB_LIB]->getValeur();
        $letab = lcfirst($vars[Variable::CODE_ETB_ART_ETB_LIB]->getValeur()) . $etab;
        $libEtablissementA = "à " . $letab;
        $libEtablissementLe = $letab;
        $libEtablissementDe = "de " . $letab;
        $libPresidentLe = $vars[Variable::CODE_ETB_LIB_TIT_RESP]->getValeur();
        $nomPresid = $vars[Variable::CODE_ETB_LIB_NOM_RESP]->getValeur();

        $renderer = $this->getServiceLocator()->get('view_renderer'); /* @var $renderer \Zend\View\Renderer\PhpRenderer */
        $exporter = new ConventionPdfExporter($renderer, 'A4');
        $exporter->setVars([
            'these'              => $these,
            'form'               => $form,
            'libEtablissement'   => $etab,
            'libEtablissementA'  => $libEtablissementA,
            'libEtablissementLe' => $libEtablissementLe,
            'libEtablissementDe' => $libEtablissementDe,
            'libPresidentLe'     => $libPresidentLe,
            'nomPresid'          => $nomPresid,
        ]);
        $exporter->export('export.pdf');
        exit;
    }

    public function modifierDescriptionAction()
    {
        $these = $this->requestedThese();

        $form = $this->getDescriptionForm();

        if ($this->getRequest()->isPost()) {
            /** @var ParametersInterface $post */
            $post = $this->getRequest()->getPost();
            $form->setData($post);
            if ($form->isValid()) {
                /** @var MetadonneeThese $metadonnee */
                $metadonnee = $form->getData();
                $this->theseService->updateMetadonnees($these, $metadonnee);

                $this->flashMessenger()->addSuccessMessage("Informations enregistrées avec succès.");

                if (! $this->getRequest()->isXmlHttpRequest()) {
                    return $this->redirect()->toRoute('these/description', [], [], true);
                }
            }
        }

        $form->setAttribute('action', $this->urlThese()->modifierMetadonneesUrl($these));

        return new ViewModel([
            'these' => $these,
            'form' => $form,
            'title' => "Signalement",
        ]);
    }

    /**
     * @return MetadonneeTheseForm
     */
    private function getDescriptionForm()
    {
        $these = $this->requestedThese();

        /** @var MetadonneeTheseForm $form */
        $form = $this->getServiceLocator()->get('formElementManager')->get('MetadonneeTheseForm');

        $description = $these->getMetadonnee();

        if ($description === null) {
            $description = new MetadonneeThese();
            $description->setTitre($these->getTitre());
        }

        $form->bind($description);

        return $form;
    }

    public function modifierCertifConformiteAction()
    {
        $these = $this->requestedThese();
        $versionArchivage = $this->fichierService->fetchVersionFichier($this->params()->fromQuery('version'));

        //$fichierTheseRetraite = $these->getFichiersByNatureEtVersion(NatureFichier::CODE_THESE_PDF, $versionArchivage, true)->first();
        $fichierTheseRetraite = current($this->fichierService->getRepository()->fetchFichiers($these, NatureFichier::CODE_THESE_PDF, $versionArchivage, true));

        $form = new ConformiteFichierForm('conformite');

        if ($this->getRequest()->isPost()) {
            /** @var ParametersInterface $post */
            $post = $this->getRequest()->getPost();
            $form->setData($post);
            if ($form->isValid()) {
                $conforme = $post->get('conforme');
                $this->theseService->updateConformiteTheseRetraitee($these, $conforme);
                if ($conforme && $versionArchivage->estVersionCorrigee()) {
                    $this->validationService->validateDepotTheseCorrigee($these);

                    // notification des directeurs de thèse
                    $this->notificationService->triggerValidationDepotTheseCorrigee($these);
                }
            }
        }
        else {
            $conforme = $fichierTheseRetraite->getEstConforme();
            $form->get('conforme')->setValue($conforme !== null ? (string) $conforme : null);
        }

        $form->setAttribute('action', $this->urlThese()->certifierConformiteTheseRetraiteUrl($these, $versionArchivage));

        return new ViewModel([
            'these' => $these,
            'form' => $form,
            'title' => "Conformité de " . $versionArchivage->toString(),
        ]);
    }

    public function depotPapierFinalAction() {

        $these = $this->requestedThese();

        $request = $this->getRequest();
        if ($request->isPost()) {
            $this->validationService->validateVersionPapierCorrigee($these);
            $this->flashMessenger()->addSuccessMessage("Validation enregistrée avec succès.");
            return $this->redirect()->toRoute('these/version-papier', [], [], true);
        }

        $validations = $this->validationService->getRepository()->findValidationByCodeAndThese(
            TypeValidation::CODE_VERSION_PAPIER_CORRIGEE,
            $these
        );

        return new ViewModel(array(
            'these' => $these,
            'validation' => empty($validations) ? null : $validations[0],
        ));

    }

    /**
     * Génération de la page de couverture.
     *
     * todo: vérifier dans le contrôleur l'existence des logos et notifier en cas d'absence de logo.
     */
    public function generateAction()
    {
        $these = $this->requestedThese();
        $renderer = $this->getServiceLocator()->get('view_renderer'); /* @var $renderer \Zend\View\Renderer\PhpRenderer */
        $exporter = new PageDeCouverturePdfExporter($renderer, 'A4');
        $exporter->setVars([
            'these' => $these,
        ]);
        $exporter->export('export.pdf');
        exit;
    }

    /**
     * Spécifie le timout à appliquer au lancement du script de retraitement.
     *
     * @param string $timeoutRetraitement Ex: '30s', '1m', cf. "man timout".
     * @return self
     */
    public function setTimeoutRetraitement($timeoutRetraitement)
    {
        $this->timeoutRetraitement = $timeoutRetraitement;

        return $this;
    }


    /**
     * @return ViewModel
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function recapBuAction() {

        $these = $this->requestedThese();
        /** @var RecapBu $recapBu */
        $recapBu = $this->entityManager->getRepository(RecapBu::class)->findOneBy(["these" => $these]);

        // RecapBu n'existe pas :: il faut vérifier l'existence de Diffusion et de RdvBU et crée en fonction
        if ($recapBu === null) {
            $diffusion = $this->entityManager->getRepository(Diffusion::class)->findOneBy(["these" => $these]);
            if ($diffusion === null) {
                $diffusion = new Diffusion();
                $diffusion->setThese($these);
                $diffusion->setDroitAuteurOk(false);
                $diffusion->setAutorisMel(0);
                $diffusion->setCertifCharteDiff(false);
                $diffusion->setConfidentielle(false);
                $this->entityManager->persist($diffusion);
                $this->entityManager->flush($diffusion);
            }
            $rdvBu = $this->entityManager->getRepository(RdvBu::class)->findOneBy(["these" => $these]);
            if ($rdvBu === null) {
                $rdvBu = new RdvBu();
                $rdvBu->setThese($these);
                $this->entityManager->persist($rdvBu);
                $this->entityManager->flush($rdvBu);
            }
            $recapBu = new RecapBu();
            $recapBu->setThese($these);
            $recapBu->setDiffusion($diffusion);
            $recapBu->setRdvBu($rdvBu);
            $this->entityManager->persist($recapBu);
            $this->entityManager->flush($recapBu);
        }

        /** @var RecapBuForm $form */
        $form = $this->getServiceLocator()->get('formElementManager')->get('RecapBuForm');
        $form->bind($recapBu);

        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost();
            $form->setData($this->getRequest()->getPost()); // appel de Hydrator::hydrate

            if ($form->isValid()) {
                $this->entityManager->flush($recapBu->getRdvBu());
                $this->entityManager->flush($recapBu->getDiffusion());
                $this->entityManager->flush($recapBu);
            }
        }

        return new ViewModel([
            'these' => $these,
            'form' => $form,
        ]);
    }
}