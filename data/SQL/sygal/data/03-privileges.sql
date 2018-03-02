
--------------------------- PRIVILEGES -----------------------

INSERT INTO CATEGORIE_PRIVILEGE (ID, CODE, LIBELLE, ORDRE) VALUES (21, 'ecole-doctorale', 'École doctorale', 100);
INSERT INTO CATEGORIE_PRIVILEGE (ID, CODE, LIBELLE, ORDRE) VALUES (23, 'faq', 'FAQ', 10);
INSERT INTO CATEGORIE_PRIVILEGE (ID, CODE, LIBELLE, ORDRE) VALUES (1, 'droit', 'Gestion des droits', 1);
INSERT INTO CATEGORIE_PRIVILEGE (ID, CODE, LIBELLE, ORDRE) VALUES (2, 'import', 'Import', 10);
INSERT INTO CATEGORIE_PRIVILEGE (ID, CODE, LIBELLE, ORDRE) VALUES (3, 'these', 'Thèse', 20);
INSERT INTO CATEGORIE_PRIVILEGE (ID, CODE, LIBELLE, ORDRE) VALUES (4, 'doctorant', 'Doctorant', 30);
INSERT INTO CATEGORIE_PRIVILEGE (ID, CODE, LIBELLE, ORDRE) VALUES (5, 'utilisateur', 'Utilisateur', 5);
INSERT INTO CATEGORIE_PRIVILEGE (ID, CODE, LIBELLE, ORDRE) VALUES (22, 'unite-recherche', 'Unité de Recherche', 200);
INSERT INTO CATEGORIE_PRIVILEGE (ID, CODE, LIBELLE, ORDRE) VALUES (24, 'validation', 'Validations', 25);
INSERT INTO CATEGORIE_PRIVILEGE (ID, CODE, LIBELLE, ORDRE) VALUES (44, 'fichier-divers', 'Fichier divers', 40);

INSERT INTO PRIVILEGE (ID, CATEGORIE_ID, CODE, LIBELLE, ORDRE) VALUES (81, 3, 'telechargement-fichier', 'Téléchargement de fichier déposé', 3060);
INSERT INTO PRIVILEGE (ID, CATEGORIE_ID, CODE, LIBELLE, ORDRE) VALUES (82, 3, 'consultation-fiche', 'Consultation de la fiche d''identité de la thèse', 3025);
INSERT INTO PRIVILEGE (ID, CATEGORIE_ID, CODE, LIBELLE, ORDRE) VALUES (83, 3, 'consultation-depot', 'Consultation du dépôt de la thèse', 3026);
INSERT INTO PRIVILEGE (ID, CATEGORIE_ID, CODE, LIBELLE, ORDRE) VALUES (84, 3, 'consultation-description', 'Consultation de la description de la thèse', 3027);
INSERT INTO PRIVILEGE (ID, CATEGORIE_ID, CODE, LIBELLE, ORDRE) VALUES (85, 3, 'consultation-archivage', 'Consultation de l''archivage de la thèse', 3028);
INSERT INTO PRIVILEGE (ID, CATEGORIE_ID, CODE, LIBELLE, ORDRE) VALUES (86, 3, 'consultation-rdv-bu', 'Consultation du rendez-vous BU', 3029);
INSERT INTO PRIVILEGE (ID, CATEGORIE_ID, CODE, LIBELLE, ORDRE) VALUES (87, 3, 'creation-zip', 'Création de l''archive ZIP', 3200);
INSERT INTO PRIVILEGE (ID, CATEGORIE_ID, CODE, LIBELLE, ORDRE) VALUES (88, 24, 'rdv-bu', 'Validation suite au rendez-vous à la BU', 3035);
INSERT INTO PRIVILEGE (ID, CATEGORIE_ID, CODE, LIBELLE, ORDRE) VALUES (91, 21, 'consultation', 'Consultation d''école doctorale', 100);
INSERT INTO PRIVILEGE (ID, CATEGORIE_ID, CODE, LIBELLE, ORDRE) VALUES (92, 21, 'modification', 'Modification d''école doctorale', 110);
INSERT INTO PRIVILEGE (ID, CATEGORIE_ID, CODE, LIBELLE, ORDRE) VALUES (96, 23, 'modification', 'Modification de la FAQ', 10);
INSERT INTO PRIVILEGE (ID, CATEGORIE_ID, CODE, LIBELLE, ORDRE) VALUES (1, 1, 'role-visualisation', 'Rôles - Visualisation', 1);
INSERT INTO PRIVILEGE (ID, CATEGORIE_ID, CODE, LIBELLE, ORDRE) VALUES (2, 1, 'role-edition', 'Rôles - Édition', 2);
INSERT INTO PRIVILEGE (ID, CATEGORIE_ID, CODE, LIBELLE, ORDRE) VALUES (3, 1, 'privilege-visualisation', 'Privilèges - Visualisation', 3);
INSERT INTO PRIVILEGE (ID, CATEGORIE_ID, CODE, LIBELLE, ORDRE) VALUES (4, 1, 'privilege-edition', 'Privilèges - Édition', 4);
INSERT INTO PRIVILEGE (ID, CATEGORIE_ID, CODE, LIBELLE, ORDRE) VALUES (6, 2, 'ecarts', 'Écarts entre les données de l''application et ses sources', 1);
INSERT INTO PRIVILEGE (ID, CATEGORIE_ID, CODE, LIBELLE, ORDRE) VALUES (9, 2, 'vues-procedures', 'Mise à jour des vues différentielles et des procédures de mise à jour', 4);
INSERT INTO PRIVILEGE (ID, CATEGORIE_ID, CODE, LIBELLE, ORDRE) VALUES (8, 2, 'tbl', 'Tableau de bord principal', 3);
INSERT INTO PRIVILEGE (ID, CATEGORIE_ID, CODE, LIBELLE, ORDRE) VALUES (7, 2, 'maj', 'Mise à jour des données à partir de leurs sources', 2);
INSERT INTO PRIVILEGE (ID, CATEGORIE_ID, CODE, LIBELLE, ORDRE) VALUES (11, 4, 'modification-persopass', 'Modification du persopass', 10);
INSERT INTO PRIVILEGE (ID, CATEGORIE_ID, CODE, LIBELLE, ORDRE) VALUES (14, 5, 'attribution-role', 'Attribution de rôle aux utilisateurs', 20);
INSERT INTO PRIVILEGE (ID, CATEGORIE_ID, CODE, LIBELLE, ORDRE) VALUES (41, 3, 'saisie-description', 'Saisie de la description', 3040);
INSERT INTO PRIVILEGE (ID, CATEGORIE_ID, CODE, LIBELLE, ORDRE) VALUES (42, 3, 'saisie-autorisation-diffusion', 'Saisie du formulaire d''autorisation de diffusion', 3090);
INSERT INTO PRIVILEGE (ID, CATEGORIE_ID, CODE, LIBELLE, ORDRE) VALUES (43, 3, 'depot-version-initiale', 'Dépôt de la version initiale de la thèse', 3050);
INSERT INTO PRIVILEGE (ID, CATEGORIE_ID, CODE, LIBELLE, ORDRE) VALUES (44, 3, 'edition-convention-mel', 'Edition de la convention de mise en ligne', 3070);
INSERT INTO PRIVILEGE (ID, CATEGORIE_ID, CODE, LIBELLE, ORDRE) VALUES (45, 3, 'saisie-mot-cle-rameau', 'Saisie des mots-clés RAMEAU', 3030);
INSERT INTO PRIVILEGE (ID, CATEGORIE_ID, CODE, LIBELLE, ORDRE) VALUES (46, 5, 'consultation', 'Consultation des utilisateurs', 10);
INSERT INTO PRIVILEGE (ID, CATEGORIE_ID, CODE, LIBELLE, ORDRE) VALUES (47, 3, 'recherche', 'Recherche de thèses', 3010);
INSERT INTO PRIVILEGE (ID, CATEGORIE_ID, CODE, LIBELLE, ORDRE) VALUES (61, 3, 'saisie-conformite-archivage', 'Juger de la conformité de la thèse pour archivage', 3080);
INSERT INTO PRIVILEGE (ID, CATEGORIE_ID, CODE, LIBELLE, ORDRE) VALUES (90, 24, 'rdv-bu-suppression', 'Suppression de la validation concernant le rendez-vous à la BU', 3036);
INSERT INTO PRIVILEGE (ID, CATEGORIE_ID, CODE, LIBELLE, ORDRE) VALUES (93, 22, 'consultation', 'Consultation d''Unité de Recherche', 100);
INSERT INTO PRIVILEGE (ID, CATEGORIE_ID, CODE, LIBELLE, ORDRE) VALUES (94, 22, 'modification', 'Modification d''Unité de Recherche', 110);
INSERT INTO PRIVILEGE (ID, CATEGORIE_ID, CODE, LIBELLE, ORDRE) VALUES (95, 5, 'modification', 'Modification d''utilisateur', 110);
INSERT INTO PRIVILEGE (ID, CATEGORIE_ID, CODE, LIBELLE, ORDRE) VALUES (137, 3, 'depot-version-corrigee', 'Dépôt de la version corrigée de la thèse', 3055);
INSERT INTO PRIVILEGE (ID, CATEGORIE_ID, CODE, LIBELLE, ORDRE) VALUES (127, 24, 'depot-these-corrigee', 'Validation du dépôt de la thèse corrigée', 4000);
INSERT INTO PRIVILEGE (ID, CATEGORIE_ID, CODE, LIBELLE, ORDRE) VALUES (128, 24, 'depot-these-corrigee-suppression', 'Suppression de la validation du dépôt de la thèse corrigée', 4120);
INSERT INTO PRIVILEGE (ID, CATEGORIE_ID, CODE, LIBELLE, ORDRE) VALUES (157, 44, 'televerser', 'Téléverser un fichier comme le PV ou le rapport de soutenance, la demande de confidentialité, etc.', 100);
INSERT INTO PRIVILEGE (ID, CATEGORIE_ID, CODE, LIBELLE, ORDRE) VALUES (160, 44, 'consulter', 'Télécharger/consulter un fichier comme le PV ou le rapport de soutenance, la demande de confidentialité, etc.', 150);
INSERT INTO PRIVILEGE (ID, CATEGORIE_ID, CODE, LIBELLE, ORDRE) VALUES (177, 3, 'export-csv', 'Export des thèses au format CSV', 3020);
INSERT INTO PRIVILEGE (ID, CATEGORIE_ID, CODE, LIBELLE, ORDRE) VALUES (197, 3, 'saisie-rdv-bu', 'Modification des informations rendez-vous BU', 3029);
INSERT INTO PRIVILEGE (ID, CATEGORIE_ID, CODE, LIBELLE, ORDRE) VALUES (129, 24, 'correction-these', 'Validation des corrections de la thèse', 4100);
INSERT INTO PRIVILEGE (ID, CATEGORIE_ID, CODE, LIBELLE, ORDRE) VALUES (130, 24, 'correction-these-suppression', 'Suppression de la validation des corrections de la thèse', 4120);
INSERT INTO PRIVILEGE (ID, CATEGORIE_ID, CODE, LIBELLE, ORDRE) VALUES (198, 3, 'saisie-attestations', 'Modification des attestations', 3030);
insert into PRIVILEGE(ID, CATEGORIE_ID, CODE, LIBELLE, ORDRE)
  select privilege_id_seq.nextval, cp.id, 'version-papier-corrigee', 'Validation du dépôt de la version papier corrigée', 3040
  from CATEGORIE_PRIVILEGE cp where cp.CODE = 'validation';
insert into PRIVILEGE(ID, CATEGORIE_ID, CODE, LIBELLE, ORDRE)
  select privilege_id_seq.nextval, cp.id, 'consultation-version-papier-corrigee', 'Consultation du dépôt de la version papier corrigée', 3030
  from CATEGORIE_PRIVILEGE cp where cp.CODE = 'these';


--delete from ROLE_PRIVILEGE;
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Administrateur' and p.code = 'attribution-role' and cp.code = 'utilisateur';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Administrateur' and p.code = 'consultation' and cp.code = 'ecole-doctorale';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Administrateur' and p.code = 'consultation' and cp.code = 'utilisateur';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Administrateur' and p.code = 'consultation' and cp.code = 'unite-recherche';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Administrateur' and p.code = 'consultation-archivage' and cp.code = 'these';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Administrateur' and p.code = 'consultation-depot' and cp.code = 'these';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Administrateur' and p.code = 'consultation-description' and cp.code = 'these';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Administrateur' and p.code = 'consultation-fiche' and cp.code = 'these';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Administrateur' and p.code = 'consultation-rdv-bu' and cp.code = 'these';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Administrateur' and p.code = 'consulter' and cp.code = 'fichier-divers';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Administrateur' and p.code = 'correction-these' and cp.code = 'validation';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Administrateur' and p.code = 'correction-these-suppression' and cp.code = 'validation';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Administrateur' and p.code = 'creation-zip' and cp.code = 'these';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Administrateur' and p.code = 'depot-these-corrigee' and cp.code = 'validation';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Administrateur' and p.code = 'depot-these-corrigee-suppression' and cp.code = 'validation';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Administrateur' and p.code = 'depot-version-corrigee' and cp.code = 'these';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Administrateur' and p.code = 'depot-version-initiale' and cp.code = 'these';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Administrateur' and p.code = 'ecarts' and cp.code = 'import';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Administrateur' and p.code = 'edition-convention-mel' and cp.code = 'these';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Administrateur' and p.code = 'export-csv' and cp.code = 'these';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Administrateur' and p.code = 'modification' and cp.code = 'utilisateur';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Administrateur' and p.code = 'modification' and cp.code = 'unite-recherche';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Administrateur' and p.code = 'modification' and cp.code = 'ecole-doctorale';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Administrateur' and p.code = 'modification' and cp.code = 'faq';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Administrateur' and p.code = 'modification-persopass' and cp.code = 'doctorant';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Administrateur' and p.code = 'privilege-visualisation' and cp.code = 'droit';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Administrateur' and p.code = 'rdv-bu' and cp.code = 'validation';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Administrateur' and p.code = 'rdv-bu-suppression' and cp.code = 'validation';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Administrateur' and p.code = 'recherche' and cp.code = 'these';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Administrateur' and p.code = 'role-visualisation' and cp.code = 'droit';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Administrateur' and p.code = 'saisie-attestations' and cp.code = 'these';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Administrateur' and p.code = 'saisie-autorisation-diffusion' and cp.code = 'these';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Administrateur' and p.code = 'saisie-conformite-archivage' and cp.code = 'these';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Administrateur' and p.code = 'saisie-description' and cp.code = 'these';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Administrateur' and p.code = 'saisie-mot-cle-rameau' and cp.code = 'these';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Administrateur' and p.code = 'saisie-rdv-bu' and cp.code = 'these';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Administrateur' and p.code = 'telechargement-fichier' and cp.code = 'these';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Administrateur' and p.code = 'televerser' and cp.code = 'fichier-divers';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Administrateur technique' and p.code = 'attribution-role' and cp.code = 'utilisateur';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Administrateur technique' and p.code = 'consultation' and cp.code = 'unite-recherche';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Administrateur technique' and p.code = 'consultation' and cp.code = 'ecole-doctorale';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Administrateur technique' and p.code = 'consultation' and cp.code = 'utilisateur';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Administrateur technique' and p.code = 'consultation-archivage' and cp.code = 'these';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Administrateur technique' and p.code = 'consultation-depot' and cp.code = 'these';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Administrateur technique' and p.code = 'consultation-description' and cp.code = 'these';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Administrateur technique' and p.code = 'consultation-fiche' and cp.code = 'these';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Administrateur technique' and p.code = 'consultation-rdv-bu' and cp.code = 'these';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Administrateur technique' and p.code = 'consulter' and cp.code = 'fichier-divers';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Administrateur technique' and p.code = 'correction-these' and cp.code = 'validation';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Administrateur technique' and p.code = 'correction-these-suppression' and cp.code = 'validation';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Administrateur technique' and p.code = 'creation-zip' and cp.code = 'these';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Administrateur technique' and p.code = 'depot-these-corrigee' and cp.code = 'validation';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Administrateur technique' and p.code = 'depot-these-corrigee-suppression' and cp.code = 'validation';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Administrateur technique' and p.code = 'depot-version-corrigee' and cp.code = 'these';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Administrateur technique' and p.code = 'depot-version-initiale' and cp.code = 'these';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Administrateur technique' and p.code = 'ecarts' and cp.code = 'import';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Administrateur technique' and p.code = 'edition-convention-mel' and cp.code = 'these';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Administrateur technique' and p.code = 'export-csv' and cp.code = 'these';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Administrateur technique' and p.code = 'maj' and cp.code = 'import';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Administrateur technique' and p.code = 'modification' and cp.code = 'faq';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Administrateur technique' and p.code = 'modification' and cp.code = 'utilisateur';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Administrateur technique' and p.code = 'modification' and cp.code = 'unite-recherche';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Administrateur technique' and p.code = 'modification' and cp.code = 'ecole-doctorale';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Administrateur technique' and p.code = 'modification-persopass' and cp.code = 'doctorant';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Administrateur technique' and p.code = 'privilege-edition' and cp.code = 'droit';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Administrateur technique' and p.code = 'privilege-visualisation' and cp.code = 'droit';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Administrateur technique' and p.code = 'rdv-bu' and cp.code = 'validation';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Administrateur technique' and p.code = 'rdv-bu-suppression' and cp.code = 'validation';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Administrateur technique' and p.code = 'recherche' and cp.code = 'these';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Administrateur technique' and p.code = 'role-edition' and cp.code = 'droit';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Administrateur technique' and p.code = 'role-visualisation' and cp.code = 'droit';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Administrateur technique' and p.code = 'saisie-attestations' and cp.code = 'these';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Administrateur technique' and p.code = 'saisie-autorisation-diffusion' and cp.code = 'these';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Administrateur technique' and p.code = 'saisie-conformite-archivage' and cp.code = 'these';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Administrateur technique' and p.code = 'saisie-description' and cp.code = 'these';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Administrateur technique' and p.code = 'saisie-mot-cle-rameau' and cp.code = 'these';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Administrateur technique' and p.code = 'saisie-rdv-bu' and cp.code = 'these';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Administrateur technique' and p.code = 'tbl' and cp.code = 'import';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Administrateur technique' and p.code = 'telechargement-fichier' and cp.code = 'these';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Administrateur technique' and p.code = 'televerser' and cp.code = 'fichier-divers';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Administrateur technique' and p.code = 'vues-procedures' and cp.code = 'import';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Bibliothèque universitaire' and p.code = 'consultation-archivage' and cp.code = 'these';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Bibliothèque universitaire' and p.code = 'consultation-depot' and cp.code = 'these';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Bibliothèque universitaire' and p.code = 'consultation-description' and cp.code = 'these';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Bibliothèque universitaire' and p.code = 'consultation-fiche' and cp.code = 'these';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Bibliothèque universitaire' and p.code = 'consultation-rdv-bu' and cp.code = 'these';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Bibliothèque universitaire' and p.code = 'consulter' and cp.code = 'fichier-divers';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Bibliothèque universitaire' and p.code = 'correction-these' and cp.code = 'validation';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Bibliothèque universitaire' and p.code = 'correction-these-suppression' and cp.code = 'validation';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Bibliothèque universitaire' and p.code = 'creation-zip' and cp.code = 'these';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Bibliothèque universitaire' and p.code = 'depot-these-corrigee' and cp.code = 'validation';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Bibliothèque universitaire' and p.code = 'depot-these-corrigee-suppression' and cp.code = 'validation';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Bibliothèque universitaire' and p.code = 'depot-version-corrigee' and cp.code = 'these';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Bibliothèque universitaire' and p.code = 'depot-version-initiale' and cp.code = 'these';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Bibliothèque universitaire' and p.code = 'edition-convention-mel' and cp.code = 'these';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Bibliothèque universitaire' and p.code = 'export-csv' and cp.code = 'these';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Bibliothèque universitaire' and p.code = 'modification' and cp.code = 'faq';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Bibliothèque universitaire' and p.code = 'rdv-bu' and cp.code = 'validation';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Bibliothèque universitaire' and p.code = 'rdv-bu-suppression' and cp.code = 'validation';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Bibliothèque universitaire' and p.code = 'recherche' and cp.code = 'these';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Bibliothèque universitaire' and p.code = 'saisie-attestations' and cp.code = 'these';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Bibliothèque universitaire' and p.code = 'saisie-autorisation-diffusion' and cp.code = 'these';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Bibliothèque universitaire' and p.code = 'saisie-conformite-archivage' and cp.code = 'these';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Bibliothèque universitaire' and p.code = 'saisie-description' and cp.code = 'these';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Bibliothèque universitaire' and p.code = 'saisie-mot-cle-rameau' and cp.code = 'these';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Bibliothèque universitaire' and p.code = 'saisie-rdv-bu' and cp.code = 'these';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Bibliothèque universitaire' and p.code = 'telechargement-fichier' and cp.code = 'these';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Bibliothèque universitaire' and p.code = 'televerser' and cp.code = 'fichier-divers';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Bureau des doctorats' and p.code = 'consultation-archivage' and cp.code = 'these';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Bureau des doctorats' and p.code = 'consultation-depot' and cp.code = 'these';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Bureau des doctorats' and p.code = 'consultation-description' and cp.code = 'these';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Bureau des doctorats' and p.code = 'consultation-fiche' and cp.code = 'these';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Bureau des doctorats' and p.code = 'consultation-rdv-bu' and cp.code = 'these';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Bureau des doctorats' and p.code = 'consulter' and cp.code = 'fichier-divers';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Bureau des doctorats' and p.code = 'correction-these' and cp.code = 'validation';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Bureau des doctorats' and p.code = 'correction-these-suppression' and cp.code = 'validation';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Bureau des doctorats' and p.code = 'creation-zip' and cp.code = 'these';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Bureau des doctorats' and p.code = 'depot-these-corrigee' and cp.code = 'validation';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Bureau des doctorats' and p.code = 'depot-these-corrigee-suppression' and cp.code = 'validation';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Bureau des doctorats' and p.code = 'depot-version-corrigee' and cp.code = 'these';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Bureau des doctorats' and p.code = 'depot-version-initiale' and cp.code = 'these';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Bureau des doctorats' and p.code = 'edition-convention-mel' and cp.code = 'these';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Bureau des doctorats' and p.code = 'export-csv' and cp.code = 'these';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Bureau des doctorats' and p.code = 'modification' and cp.code = 'faq';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Bureau des doctorats' and p.code = 'recherche' and cp.code = 'these';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Bureau des doctorats' and p.code = 'saisie-attestations' and cp.code = 'these';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Bureau des doctorats' and p.code = 'saisie-autorisation-diffusion' and cp.code = 'these';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Bureau des doctorats' and p.code = 'saisie-conformite-archivage' and cp.code = 'these';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Bureau des doctorats' and p.code = 'saisie-description' and cp.code = 'these';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Bureau des doctorats' and p.code = 'saisie-rdv-bu' and cp.code = 'these';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Bureau des doctorats' and p.code = 'telechargement-fichier' and cp.code = 'these';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Bureau des doctorats' and p.code = 'televerser' and cp.code = 'fichier-divers';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Directeur de thèse' and p.code = 'consultation-archivage' and cp.code = 'these';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Directeur de thèse' and p.code = 'consultation-depot' and cp.code = 'these';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Directeur de thèse' and p.code = 'consultation-description' and cp.code = 'these';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Directeur de thèse' and p.code = 'consultation-fiche' and cp.code = 'these';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Directeur de thèse' and p.code = 'consultation-rdv-bu' and cp.code = 'these';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Directeur de thèse' and p.code = 'consulter' and cp.code = 'fichier-divers';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Directeur de thèse' and p.code = 'correction-these' and cp.code = 'validation';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Directeur de thèse' and p.code = 'recherche' and cp.code = 'these';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Directeur de thèse' and p.code = 'telechargement-fichier' and cp.code = 'these';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Doctorant' and p.code = 'consultation-archivage' and cp.code = 'these';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Doctorant' and p.code = 'consultation-depot' and cp.code = 'these';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Doctorant' and p.code = 'consultation-description' and cp.code = 'these';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Doctorant' and p.code = 'consultation-fiche' and cp.code = 'these';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Doctorant' and p.code = 'consultation-rdv-bu' and cp.code = 'these';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Doctorant' and p.code = 'consulter' and cp.code = 'fichier-divers';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Doctorant' and p.code = 'depot-these-corrigee' and cp.code = 'validation';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Doctorant' and p.code = 'depot-version-corrigee' and cp.code = 'these';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Doctorant' and p.code = 'depot-version-initiale' and cp.code = 'these';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Doctorant' and p.code = 'edition-convention-mel' and cp.code = 'these';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Doctorant' and p.code = 'modification-persopass' and cp.code = 'doctorant';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Doctorant' and p.code = 'saisie-attestations' and cp.code = 'these';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Doctorant' and p.code = 'saisie-autorisation-diffusion' and cp.code = 'these';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Doctorant' and p.code = 'saisie-conformite-archivage' and cp.code = 'these';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Doctorant' and p.code = 'saisie-description' and cp.code = 'these';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Doctorant' and p.code = 'saisie-rdv-bu' and cp.code = 'these';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Doctorant' and p.code = 'telechargement-fichier' and cp.code = 'these';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'École doctorale' and p.code = 'consultation-archivage' and cp.code = 'these';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'École doctorale' and p.code = 'consultation-depot' and cp.code = 'these';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'École doctorale' and p.code = 'consultation-description' and cp.code = 'these';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'École doctorale' and p.code = 'consultation-fiche' and cp.code = 'these';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'École doctorale' and p.code = 'consultation-rdv-bu' and cp.code = 'these';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'École doctorale' and p.code = 'consulter' and cp.code = 'fichier-divers';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'École doctorale' and p.code = 'edition-convention-mel' and cp.code = 'these';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'École doctorale' and p.code = 'export-csv' and cp.code = 'these';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'École doctorale' and p.code = 'recherche' and cp.code = 'these';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Membre du jury' and p.code = 'consulter' and cp.code = 'fichier-divers';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Observateur' and p.code = 'consultation-fiche' and cp.code = 'these';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Observateur' and p.code = 'recherche' and cp.code = 'these';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Président du jury' and p.code = 'consulter' and cp.code = 'fichier-divers';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Rapporteur du jury' and p.code = 'consulter' and cp.code = 'fichier-divers';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Unité de recherche' and p.code = 'consultation-archivage' and cp.code = 'these';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Unité de recherche' and p.code = 'consultation-depot' and cp.code = 'these';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Unité de recherche' and p.code = 'consultation-description' and cp.code = 'these';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Unité de recherche' and p.code = 'consultation-fiche' and cp.code = 'these';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Unité de recherche' and p.code = 'consultation-rdv-bu' and cp.code = 'these';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Unité de recherche' and p.code = 'consulter' and cp.code = 'fichier-divers';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Unité de recherche' and p.code = 'edition-convention-mel' and cp.code = 'these';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Unité de recherche' and p.code = 'export-csv' and cp.code = 'these';
insert into role_privilege(role_id, privilege_id) select r.id, p.id from role r, privilege p, categorie_privilege cp where p.categorie_id = cp.id and r.libelle = 'Unité de recherche' and p.code = 'recherche' and cp.code = 'these';


-- 1	    Rôles - Visualisation
-- 2	    Rôles - Édition
-- 3	    Privilèges - Visualisation
-- 4	    Privilèges - Édition

-- 46	    Consultation des utilisateurs
-- 14	    Attribution de rôle aux utilisateurs
-- 95	    Modification d'utilisateur
-- 6	    Écarts entre les données de l'application et ses sources
-- 7	    Mise à jour des données à partir de leurs sources
-- 8	    Tableau de bord principal
-- 9	    Mise à jour des vues différentielles et des procédures de mise à jour

-- 96	    Modification de la FAQ

-- 47	    Recherche de thèses
-- 177	    Export des thèses au format CSV
-- 82	    Consultation de la fiche d'identité de la thèse
-- 83	    Consultation du dépôt de la thèse
-- 84	    Consultation de la description de la thèse
-- 85	    Consultation de l'archivage de la thèse
-- 197	    Modification des informations rendez-vous BU
-- 86	    Consultation du rendez-vous BU
-- 45	    Saisie des mots-clés RAMEAU
-- 198	    Modification des attestations
-- 5	    Consultation du dépôt de la version papier corrigée
-- 41	    Saisie de la description
-- 43	    Dépôt de la version initiale de la thèse
-- 137	    Dépôt de la version corrigée de la thèse
-- 81	    Téléchargement de fichier déposé
-- 44	    Edition de la convention de mise en ligne
-- 61	    Juger de la conformité de la thèse pour archivage
-- 42	    Saisie du formulaire d'autorisation de diffusion
-- 87	    Création de l'archive ZIP
-- 88	    Validation suite au rendez-vous à la BU
-- 90	    Suppression de la validation concernant le rendez-vous à la BU
-- 127	    Validation du dépôt de la thèse corrigée
-- 129	    Validation des corrections de la thèse
-- 128	    Suppression de la validation du dépôt de la thèse corrigée
-- 130	    Suppression de la validation des corrections de la thèse
-- 217	    Validation de la remise de la version papier corrigée
-- 11	    Modification du persopass
-- 157	    Téléverser un fichier comme le PV ou le rapport de soutenance, la demande de confidentialité, etc.
-- 160	    Télécharger/consulter un fichier comme le PV ou le rapport de soutenance, la demande de confidentialité, etc.

-- 91	    Consultation d'école doctorale
-- 92	    Modification d'école doctorale

-- 93	    Consultation d'Unité de Recherche
-- 94	    Modification d'Unité de Recherche

insert into ROLE_PRIVILEGE(ROLE_ID, PRIVILEGE_ID)
  select r.id, p.id
  from role r, PRIVILEGE p
  where p.id in ( 47, 177, 82, 83, 84, 85, 86, 5, 81, 91, 92	)
        and r.CODE = 'ED'
;

insert into ROLE_PRIVILEGE(ROLE_ID, PRIVILEGE_ID)
  select r.id, p.id
  from role r, PRIVILEGE p
  where p.id in ( 47, 177, 82, 83, 84, 85, 86, 5, 81, 93, 94	)
        and r.CODE = 'UR'
;

--delete from ROLE_PRIVILEGE where ROLE_ID in (306,307,308,309,310);
insert into ROLE_PRIVILEGE(ROLE_ID, PRIVILEGE_ID)
  select r.id, p.id
  from role r, PRIVILEGE p
  where p.id in ( 1, 3, 46, 14, 95, 96, 47, 177, 82, 83, 84, 85, 197, 86, 45, 198, 5, 41, 43, 137, 81, 44, 61, 42, 87, 88, 90, 127, 129, 128, 130, 217, 11, 157, 160, 91, 92, 93, 94 )
        and r.CODE = 'ADMIN'
;

--delete from ROLE_PRIVILEGE where ROLE_ID in (select id from role where CODE = 'ADMIN_TECH');
insert into ROLE_PRIVILEGE(ROLE_ID, PRIVILEGE_ID)
  select r.id, p.id
  from role r, PRIVILEGE p
  where r.CODE = 'ADMIN_TECH'
;

--delete from ROLE_PRIVILEGE where ROLE_ID in (316,317,318,319,320);
insert into ROLE_PRIVILEGE(ROLE_ID, PRIVILEGE_ID)
  select r.id, p.id
  from role r, PRIVILEGE p
  where p.id in ( 1, 3, 47, 177, 82, 83, 84, 85, 197, 86, 45, 198, 5, 41, 43, 137, 81, 44, 61, 42, 87, 88, 90, 127, 129, 128, 130, 217, 11, 157, 160, 91, 93 )
        and r.CODE = 'BU'
;

--delete from ROLE_PRIVILEGE where ROLE_ID in (311,312,313,314,315);
insert into ROLE_PRIVILEGE(ROLE_ID, PRIVILEGE_ID)
  select r.id, p.id
  from role r, PRIVILEGE p
  where p.id in ( 1, 3, 47, 177, 82, 83, 84, 85, 86, 198, 5, 41, 43, 137, 81, 44, 61, 42, 87, 127, 129, 128, 130, 217, 11, 157, 160, 91, 93 )
        and r.CODE = 'BDD'
;

--delete from ROLE_PRIVILEGE where ROLE_ID in (select id from role where CODE = 'DOCTORANT');
insert into ROLE_PRIVILEGE(ROLE_ID, PRIVILEGE_ID)
  select r.id, p.id
  from role r, PRIVILEGE p
  where p.id in ( 47, 82, 83, 84, 85, 197, 86, 198, 5, 41, 43, 137, 81, 44, 61, 42, 127, 128, 11, 160 )
        and r.CODE = 'DOCTORANT'
;

--delete from ROLE_PRIVILEGE where ROLE_ID in (select id from role where CODE = 'D');
insert into ROLE_PRIVILEGE(ROLE_ID, PRIVILEGE_ID)
  select r.id, p.id
  from role r, PRIVILEGE p
  where p.id in ( 47, 82, 83, 84, 85, 197, 86, 81, 129, 160 )
        and r.CODE = 'D'
;




insert into ROLE_PRIVILEGE_MODELE(ROLE_CODE, PRIVILEGE_ID)
  select 'ED', p.id
  from PRIVILEGE p where p.id in ( 47, 177, 82, 83, 84, 85, 86, 5, 81, 91, 92	)
;

insert into ROLE_PRIVILEGE_MODELE(ROLE_CODE, PRIVILEGE_ID)
  select 'UR', p.id
  from PRIVILEGE p where p.id in ( 47, 177, 82, 83, 84, 85, 86, 5, 81, 93, 94 )
;

insert into ROLE_PRIVILEGE_MODELE(ROLE_CODE, PRIVILEGE_ID)
  select 'ADMIN', p.id
  from PRIVILEGE p where p.id in ( 1, 3, 46, 14, 95, 96, 47, 177, 82, 83, 84, 85, 197, 86, 45, 198, 5, 41, 43, 137, 81, 44, 61, 42, 87, 88, 90, 127, 129, 128, 130, 217, 11, 157, 160, 91, 92, 93, 94 )
;

insert into ROLE_PRIVILEGE_MODELE(ROLE_CODE, PRIVILEGE_ID)
  select 'ADMIN_TECH', p.id
  from PRIVILEGE p
;

insert into ROLE_PRIVILEGE_MODELE(ROLE_CODE, PRIVILEGE_ID)
  select 'BU', p.id
  from PRIVILEGE p where p.id in ( 1, 3, 47, 177, 82, 83, 84, 85, 197, 86, 45, 198, 5, 41, 43, 137, 81, 44, 61, 42, 87, 88, 90, 127, 129, 128, 130, 217, 11, 157, 160, 91, 93 )
;

insert into ROLE_PRIVILEGE_MODELE(ROLE_CODE, PRIVILEGE_ID)
  select 'BDD', p.id
  from PRIVILEGE p where p.id in ( 1, 3, 47, 177, 82, 83, 84, 85, 86, 198, 5, 41, 43, 137, 81, 44, 61, 42, 87, 127, 129, 128, 130, 217, 11, 157, 160, 91, 93 )
;

insert into ROLE_PRIVILEGE_MODELE(ROLE_CODE, PRIVILEGE_ID)
  select 'DOCTORANT', p.id
  from PRIVILEGE p where p.id in ( 47, 82, 83, 84, 85, 197, 86, 198, 5, 41, 43, 137, 81, 44, 61, 42, 127, 128, 11, 160 )
;

insert into ROLE_PRIVILEGE_MODELE(ROLE_CODE, PRIVILEGE_ID)
  select 'D', p.id
  from PRIVILEGE p where p.id in ( 47, 82, 83, 84, 85, 197, 86, 81, 129, 160 )
;
