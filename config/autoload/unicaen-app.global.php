<?php

return [
    'unicaen-app' => [
        // Informations concernant l'application.
        'app_infos' => [
            'nom'     => "SyGAL",
            'desc'    => "SYstème de Gestion et d'Accompagnement doctoraL",
            //'version' => cf. 'version.global.php'
            //'date'    => cf. 'version.global.php'
            'contact' => [
                'mail' => "guillaume.rozier@unilim.fr",
                //'tel' => "01 02 03 04 05",
            ],
            'mentionsLegales'        => "https://www.unilim.fr/acces-direct/mentions-legales/",
            'informatiqueEtLibertes' => "https://www.unilim.fr/acces-direct/informatique-et-libertes/",
        ],

        // Période d'exécution de la requête de rafraîchissement de la session utilisateur, en millisecondes.
        // 0 <=> aucune requête exécutée
        'session_refresh_period' => 0,

        // Paramètres de fonctionnement LDAP.
        'ldap' => [
            'dn' => [
                'UTILISATEURS_BASE_DN'                  => 'ou=people,dc=unilim,dc=fr',
                'UTILISATEURS_DESACTIVES_BASE_DN'       => 'ou=deactivated,dc=unilim,dc=fr',
                'GROUPS_BASE_DN'                        => 'ou=groups,dc=unilim,dc=fr',
                'STRUCTURES_BASE_DN'                    => 'ou=structures,dc=unilim,dc=fr',
            ],
            'filters' => [
                //'LOGIN_FILTER'                          => '(uid=%s)',
                'LOGIN_FILTER'                          => '(supannAliasLogin=%s)',
                'UTILISATEUR_STD_FILTER'                => '(|(uid=p*)(&(uid=e*)(eduPersonAffiliation=student)))',
                'CN_FILTER'                             => '(cn=%s)',
                'NAME_FILTER'                           => '(cn=%s*)',
                'UID_FILTER'                            => '(uid=%s)',
                'NO_INDIVIDU_FILTER'                    => '(supannEmpId=%08s)',
                'AFFECTATION_FILTER'                    => '(&(uid=*)(eduPersonOrgUnitDN=%s))',
                'AFFECTATION_CSTRUCT_FILTER'            => '(&(uid=*)(|(ucbnSousStructure=%s;*)(supannAffectation=%s;*)))',
                //'LOGIN_OR_NAME_FILTER'                  => '(|(uid=%s)(cn=%s*))',
                'LOGIN_OR_NAME_FILTER'                  => '(|(supannAliasLogin=%s)(cn=%s*))',
                'MEMBERSHIP_FILTER'                     => '(memberOf=%s)',
                'AFFECTATION_ORG_UNIT_FILTER'           => '(eduPersonOrgUnitDN=%s)',
                'AFFECTATION_ORG_UNIT_PRIMARY_FILTER'   => '(eduPersonPrimaryOrgUnitDN=%s)',
                'ROLE_FILTER'                           => '(supannRoleEntite=[role={SUPANN}%s][type={SUPANN}%s][code=%s]*)',
                'PROF_STRUCTURE'                        => '(&(eduPersonAffiliation=teacher)(eduPersonOrgUnitDN=%s))',
                'FILTER_STRUCTURE_DN'		            => '(%s)',
                'FILTER_STRUCTURE_CODE_ENTITE'	        => '(supannCodeEntite=%s)',
                'FILTER_STRUCTURE_CODE_ENTITE_PARENT'   => '(supannCodeEntiteParent=%s)',
            ],
        ],
    ]
];
