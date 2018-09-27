<?php

namespace Import\Controller;

use Application\Entity\Db\These;
use Application\Filter\EtablissementPrefixFilter;
use Application\Service\Etablissement\EtablissementServiceAwareTrait;
use Application\Service\These\TheseServiceAwareTrait;
use Import\Service\Traits\ImportServiceAwareTrait;
use UnicaenApp\Exception\LogicException;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Zend\Log\Filter\Priority;
use Zend\Log\Logger;
use Zend\Log\Writer\Stream;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class ImportController extends AbstractActionController
{
    use EntityManagerAwareTrait;
    use ImportServiceAwareTrait;
    use TheseServiceAwareTrait;
    use EtablissementServiceAwareTrait;

    public function indexAction()
    {
        $connection = $this->entityManager->getConnection();
        $result = $connection->executeQuery("SELECT REQ_END_DATE FROM API_LOG WHERE REQ_ETABLISSEMENT='UCN' AND REQ_TABLE='variable' ORDER BY REQ_END_DATE DESC");
        $last = $result->fetch()["REQ_END_DATE"];

        return new ViewModel([
            'last' => $last,
        ]);
    }

    public function infoLastUpdateAction()
    {
        $etablissement = $this->params()->fromRoute("etablissement");
        $table = $this->params()->fromRoute("table");

        $connection = $this->entityManager->getConnection();
        $result = $connection->executeQuery("SELECT REQ_END_DATE, REQ_RESPONSE FROM API_LOG WHERE REQ_ETABLISSEMENT='" . $etablissement . "' AND REQ_TABLE='" . $table . "' ORDER BY REQ_END_DATE DESC");
        $data = $result->fetch();

        $last_time = $data["REQ_END_DATE"];
        $message = $data["REQ_RESPONSE"];

        return new ViewModel([
            'query'     => $etablissement . ' | ' . $table,
            "last_time" => $last_time,
            "message"   => $message,
        ]);
    }

    public function helpAction()
    {
        return new ViewModel();
    }

    /**
     * Interroge le WS pour récupérer les données d'un seul établissement puis lance la synchronisation des données obtenues
     * avec les tables destinations.
     *
     * @return ViewModel
     */
    public function importAction()
    {
        $service = $this->params('service');
        $etablissement = $this->params('etablissement');
        $sourceCode = $this->params('source_code');

        $queryParams = $this->params()->fromQuery();

        $stream = fopen('php://memory','r+');
        $this->setLoggerStream($stream);

        $this->importService->import($service, $etablissement, $sourceCode, $queryParams);

        rewind($stream);
        $logs = stream_get_contents($stream);
        fclose($stream);

        return new ViewModel([
            'service'       => $service,
            'etablissement' => $etablissement,
            'source_code'   => $sourceCode,
            'logs'          => $logs,
        ]);
    }

    /**
     * Interroge le WS pour récupérer toutes les données d'un établissement puis lance la synchronisation
     * des données obtenues avec les tables destinations.
     *
     * @return ViewModel
     */
    public function importAllAction()
    {
        $etablissement = $this->params('etablissement');

        $stream = fopen('php://memory','r+');
        $this->setLoggerStream($stream);

        $this->importService->importAll($etablissement);

        rewind($stream);
        $logs = stream_get_contents($stream);
        fclose($stream);

        return new ViewModel([
            'service'       => 'Tous',
            'etablissement' => $etablissement,
            'source_code'   => '-',
            'logs'          => $logs,
        ]);
    }

    /**
     * @return ViewModel
     */
    public function updateTheseAction()
    {
        $codeEtablissement = $this->params('etablissement');
        $sourceCodeThese = $this->params('source_code');

        if (! $sourceCodeThese) {
            throw new LogicException("Le source code de la thèse est requis");
        }

        $f = new EtablissementPrefixFilter();
        $sourceCodeThese = $f->addPrefixTo($sourceCodeThese, $codeEtablissement);

        /** @var These $these */
        $these = $this->theseService->getRepository()->findOneBy(['sourceCode' => $sourceCodeThese]);
        if ($these === null) {
            throw new RuntimeException("Aucune thèse trouvée avec ce source code: " . $sourceCodeThese);
        }

        $stream = fopen('php://memory','r+');
        $this->setLoggerStream($stream);

        $this->importService->updateThese($these);

        rewind($stream);
        $logs = stream_get_contents($stream);
        fclose($stream);

        return new ViewModel([
            'service'       => "these + dépendances",
            'etablissement' => $codeEtablissement,
            'source_code'   => $sourceCodeThese,
            'logs'          => $logs,
        ]);
    }

    public function importConsoleAction()
    {
        $service = $this->params('service');
        $etablissement = $this->params('etablissement');
        $sourceCode = $this->params('source_code');

        $this->setLoggerStream('php://output');

        $this->importService->import($service, $etablissement, $sourceCode);

        echo "Importation des données du service '$service' de l'établissement '$etablissement' effectuée." . PHP_EOL;
    }

    public function importAllConsoleAction()
    {
        $etablissement = $this->params('etablissement');

        $this->setLoggerStream('php://output');

        $this->importService->importAll($etablissement);

        echo "Importation de toutes les données de l'établissement '$etablissement' effectuée." . PHP_EOL;
    }

    /**
     * @param string|resource $stream
     */
    private function setLoggerStream($stream)
    {
        $filter = new Priority(Logger::INFO);

        $writer = new Stream($stream);
        $writer->addFilter($filter);

        /** @var Logger $logger */
        $logger = $this->importService->getLogger();
        $logger->addWriter($writer);
    }
}
