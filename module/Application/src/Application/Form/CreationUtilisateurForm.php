<?php

namespace Application\Form;

use Application\Entity\Db\Individu;
use Application\Form\Validator\NewEmailValidator;
use Application\Form\Validator\PasswordValidator;
use Zend\Form\Element\Hidden;
use Zend\Form\Element\Password;
use Zend\Form\Element\Radio;
use Zend\Form\Element\Submit;
use Zend\Form\Element\Text;
use Zend\Form\Form;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Validator\Identical;

class CreationUtilisateurForm extends Form implements InputFilterProviderInterface
{
    public function init()
    {
        $this->add(
            (new Hidden('id'))
        );
        $this->add(
            (new Radio('civilite'))
                ->setValueOptions([
                    Individu::CIVILITE_M => Individu::CIVILITE_M,
                    Individu::CIVILITE_MME => Individu::CIVILITE_MME,
                ])
                ->setLabel("Civilité :")
        );
        $this->add(
            (new Text('nomUsuel'))
                ->setLabel("Nom usuel :")
        );

        $this->add(
            (new Text('nomPatronymique'))
            ->setLabel("Nom Patronymique :")
        );
        $this->add(
            (new Text('prenom'))
                ->setLabel("Prénom :")
        );
        $this->add(
            (new Text('email'))
                ->setLabel("Adresse électronique (identifiant de connexion) :")
        );
        $this->add(
            (new Password('password'))
                ->setLabel("Mot de passe :")
        );

        $this->add(
            (new Password('passwordbis'))
                ->setLabel("Confirmation du mot de passe :")
        );

        $this->add((new Submit('submit'))
            ->setValue("Enregistrer")
            ->setAttribute('class', 'btn btn-primary')
        );
    }

    /**
     * Should return an array specification compatible with
     * {@link Zend\InputFilter\Factory::createInputFilter()}.
     *
     * @return array
     */
    public function getInputFilterSpecification()
    {
        return [
            'civilite' => [
                'name' => 'civilite',
                'required' => true,
            ],
            'nomUsuel' => [
                'name' => 'nomUsuel',
                'required' => true,
            ],
            'nomPatronymique' => [
                'name' => 'nomPatronymique',
                'required' => false,
            ],
            'prenom' => [
                'name' => 'prenom',
                'required' => true,
            ],
            'email' => [
                'name' => 'email',
                'required' => true,
                'validators' => [
                    [
                        'name' => NewEmailValidator::class,
                    ],
                ],
            ],
            'password' => [
                'name' =>'password',
                'required' => true,
                'validators' => [
                    [
                        'name' => PasswordValidator::class,
                    ],
                ],
            ],
            'passwordbis' => [
                'name' =>'passwordbis',
                'required' => true,
                'validators' => [
                    new Identical('password'),
                ],
            ],
        ];
    }
}