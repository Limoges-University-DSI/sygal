<?php

namespace Application\Entity\Db;

use Doctrine\Common\Collections\Collection;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;
use UnicaenApp\Exception\LogicException;
use UnicaenImport\Entity\Db\Interfaces\SourceAwareInterface;
use UnicaenImport\Entity\Db\Traits\SourceAwareTrait;
use Zend\Permissions\Acl\Resource\ResourceInterface;

/**
 * Structure
 */
class Structure implements StructureInterface, HistoriqueAwareInterface, SourceAwareInterface, ResourceInterface
{
    use SourceAwareTrait;
    use HistoriqueAwareTrait;

    /**
     * @var string $id
     * @var string $sigle
     * @var string $libelle
     * @var string $cheminLogo
     */
    private     $id;
    protected   $sigle;
    protected   $libelle;
    protected   $cheminLogo;

    /**
     * @var string
     */
    protected $sourceCode;
    /**
     * @var string
     */
    protected $code;

    /**
     * @var TypeStructure
     */
    protected $typeStructure;

    /**
     * @var Collection
     */
    protected $etablissement;

    /**
     * @var Collection
     */
    protected $ecoleDoctorale;

    /**
     * @var Collection
     */
    protected $uniteRecherche;

    /**
     * @var Role[] $roles
     */
    protected $roles;

    /**
     * @var Structure[]
     */
    private $structuresSubstituees;

    /** @var Structure */
    private $structureSubstituante;

    /**
     * Instancie un Etablissement, une EcodeDoctorale ou une UniteRecherche à partir des données spécifiées.
     * NB: L'entité Structure de rattachement est également instanciée.
     *
     * @param StructureConcreteInterface $data
     * @param TypeStructure            $type
     * @param Source                   $source
     * @return Etablissement|EcoleDoctorale|UniteRecherche
     */
    public static function constructFromDataObject(StructureConcreteInterface $data, TypeStructure $type, Source $source)
    {
        // structure de rattachement
        $structureRattach = new Structure();
        $structureRattach->setTypeStructure($type);
        $structureRattach->setSource($source);
        $structureRattach->setCheminLogo($data->getCheminLogo());
        $structureRattach->setLibelle($data->getLibelle());
        $structureRattach->setSigle($data->getSigle());
        $structureRattach->setSourceCode($data->getSourceCode());
        $structureRattach->setCode($data->getCode());

        // structure concrète
        switch (true) {
            case $data instanceof Etablissement:
                $structure = new Etablissement();
                $structure->setDomaine($data->getDomaine());
                break;
            case $data instanceof EcoleDoctorale:
                $structure = new EcoleDoctorale();
                break;
            case $data instanceof UniteRecherche:
                $structure = new UniteRecherche();
                break;
            default:
                throw new LogicException("Type d'entité Structure spécifiée inattendu : " . get_class($data));
                break;
        }
        $structure->setSource($source);
        $structure->setSourceCode($data->getSourceCode());
        $structure->setStructure($structureRattach);

        return $structure;
    }

    /**
     * Indique si cette structure se substitue à d'autres structures.
     *
     * @return bool
     */
    public function estStructureSubstituante()
    {
        return count($this->getStructuresSubstituees()) > 0;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set sourceCode
     *
     * @param string $sourceCode
     * @return self
     */
    public function setSourceCode($sourceCode)
    {
        $this->sourceCode = $sourceCode;

        return $this;
    }

    /**
     * Get sourceCode
     *
     * @return string
     */
    public function getSourceCode()
    {
        return $this->sourceCode;
    }

    /**
     * Set code
     *
     * @param string $code
     * @return self
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get Code
     *
     * @return string
     */
    public function getCode()
    {
//        if ($this->code !== null) return $this->code;
//        if ($this->sourceCode !== null) {
//            $code = $this->getSourceCodeStringHelper()->removePrefixFrom($this->sourceCode);
//            return $code;
//        }
        // (!) Les lignes précédentes ont été mises en commentaire car l'entité Structure *possède* un attribut 'code'
        //     donc faire ici autre chose que de retourner l'attribut, c'est du sabotage, voire du terrorisme (spécial dédicace) !!
        //     Preuve que ce n'est pas une bonne idée : impossible de dire dans quels cas c'était nécessaire de faire
        //     ce jonglage avec 'code' et 'sourceCode'.

        return $this->code;
    }

    /**
     * @return string
     */
    public function getSigle()
    {
        return $this->sigle;
    }

    /**
     * @param string $sigle
     */
    public function setSigle($sigle)
    {
        $this->sigle = $sigle;
    }

    /**
     * @return string
     */
    public function getLibelle()
    {
        return $this->libelle;
    }

    /**
     * @param string $libelle
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;
    }

    /**
     * @return string
     */
    public function getCheminLogo()
    {
        return $this->cheminLogo;
    }

    /**
     * @param string $cheminLogo
     */
    public function setCheminLogo($cheminLogo)
    {
        $this->cheminLogo = $cheminLogo;
    }

    /**
     * @return Role[]
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * @return Role[]
     */
    public function getStructureDependantRoles()
    {
        $roles = [];
        foreach($this->roles as $role) {
            if ($role->isStructureDependant() && !$role->isTheseDependant()) $roles[] = $role;
        }
        return $roles;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getLibelle();
    }

    /**
     * @return TypeStructure
     */
    public function getTypeStructure()
    {
        return $this->typeStructure;
    }

    /**
     * @param TypeStructure $typeStructure
     * @return self
     */
    public function setTypeStructure(TypeStructure $typeStructure)
    {
        $this->typeStructure = $typeStructure;

        return $this;
    }

    /**
     * @return Etablissement|null
     */
    public function getEtablissement()
    {
        return $this->etablissement->first();
    }

    /**
     * @return EcoleDoctorale|null
     */
    public function getEcoleDoctorale()
    {
        return $this->ecoleDoctorale->first();
    }

    /**
     * @return UniteRecherche|null
     */
    public function getUniteRecherche()
    {
        return $this->uniteRecherche->first();
    }

    /**
     * @return Structure[]
     */
    public function getStructuresSubstituees()
    {
        return $this->structuresSubstituees;
    }

    /**
     * @return Structure
     */
    public function getStructureSubstituante()
    {
        return $this->structureSubstituante;
    }

    /**
     * @param Structure $structureSubstituante
     * @return Structure
     */
    public function setStructureSubstituante($structureSubstituante)
    {
        $this->structureSubstituante = $structureSubstituante;
        return $this;
    }

    /**
     * @return string
     */
    public function getResourceId()
    {
        return 'structure';
    }
}