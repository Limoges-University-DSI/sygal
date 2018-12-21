-- STRUCTURE DE LA TABLE
create table INFORMATION
(
	ID NUMBER not null,
	TITRE VARCHAR2(256) not null,
	HISTO_CREATION DATE default SYSDATE not null,
	HISTO_CREATEUR_ID NUMBER not null
		constraint INFORMATION_HCFK
			references UTILISATEUR,
	HISTO_MODIFICATION DATE default SYSDATE not null,
	HISTO_MODIFICATEUR_ID NUMBER not null
		constraint INFORMATION_HMFK
			references UTILISATEUR,
	HISTO_DESTRUCTION DATE,
	HISTO_DESTRUCTEUR_ID NUMBER
		constraint INFORMATION_HDFK
			references UTILISATEUR,
	CONTENU CLOB not null,
	PRIORITE NUMBER default 0 not null,
	EST_VISIBLE NUMBER default 1 not null
)
/

create unique index INFORMATION_ID_UINDEX
	on INFORMATION (ID)
/

alter table INFORMATION
	add constraint INFORMATION_PK
		primary key (ID)
;

-- LES DONNÉES DOIVENT ÊTRE REPRISES EN BASES SINON C'EST LE BAZAR

-- PRIVILEGE
INSERT INTO SYGAL_TEST.PRIVILEGE (ID, CATEGORIE_ID, CODE, LIBELLE, ORDRE) VALUES (10, 23, 'modifier-information', 'Modifier les pages d''information ', 50);
INSERT INTO SYGAL_TEST.ROLE_PRIVILEGE (ROLE_ID, PRIVILEGE_ID) VALUE (1 , 10);