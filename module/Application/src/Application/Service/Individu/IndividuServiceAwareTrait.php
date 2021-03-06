<?php

namespace Application\Service\Individu;

trait IndividuServiceAwareTrait
{
    /**
     * @var IndividuService
     */
    protected $individuService;

    /**
     * @return IndividuService
     */
    public function getIndividuService()
    {
        return $this->individuService;
    }

    /**
     * @param IndividuService $individuService
     */
    public function setIndividuService(IndividuService $individuService)
    {
        $this->individuService = $individuService;
    }
}