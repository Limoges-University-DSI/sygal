UPDATE OTH.CATEGORIE_PRIVILEGE SET CODE = 'indicateur', LIBELLE = 'Indicateur', ORDRE = 250 WHERE ID = 50;

UPDATE OTH.PRIVILEGE SET CATEGORIE_ID = 50, CODE = 'consultation', LIBELLE = 'Consultation des indicateurs', ORDRE = 10 WHERE ID = 270;
UPDATE OTH.PRIVILEGE SET CATEGORIE_ID = 50, CODE = 'exportation', LIBELLE = 'Exportation des indicateurs', ORDRE = 20 WHERE ID = 271;
UPDATE OTH.PRIVILEGE SET CATEGORIE_ID = 50, CODE = 'rafraichissement', LIBELLE = 'Rafraîchissement des indicateurs', ORDRE = 30 WHERE ID = 272;
INSERT INTO OTH.PRIVILEGE (ID, CATEGORIE_ID, CODE, LIBELLE, ORDRE) VALUES (273, 50, 'edition', 'Édition des indicateurs', 40);

UPDATE OTH.ROLE_PRIVILEGE SET  WHERE ROLE_ID = 302 AND PRIVILEGE_ID = 270;
UPDATE OTH.ROLE_PRIVILEGE SET  WHERE ROLE_ID = 306 AND PRIVILEGE_ID = 270;
UPDATE OTH.ROLE_PRIVILEGE SET  WHERE ROLE_ID = 307 AND PRIVILEGE_ID = 270;
UPDATE OTH.ROLE_PRIVILEGE SET  WHERE ROLE_ID = 308 AND PRIVILEGE_ID = 270;
UPDATE OTH.ROLE_PRIVILEGE SET  WHERE ROLE_ID = 309 AND PRIVILEGE_ID = 270;
UPDATE OTH.ROLE_PRIVILEGE SET  WHERE ROLE_ID = 310 AND PRIVILEGE_ID = 270;
UPDATE OTH.ROLE_PRIVILEGE SET  WHERE ROLE_ID = 311 AND PRIVILEGE_ID = 270;
UPDATE OTH.ROLE_PRIVILEGE SET  WHERE ROLE_ID = 312 AND PRIVILEGE_ID = 270;
UPDATE OTH.ROLE_PRIVILEGE SET  WHERE ROLE_ID = 313 AND PRIVILEGE_ID = 270;
UPDATE OTH.ROLE_PRIVILEGE SET  WHERE ROLE_ID = 314 AND PRIVILEGE_ID = 270;
UPDATE OTH.ROLE_PRIVILEGE SET  WHERE ROLE_ID = 315 AND PRIVILEGE_ID = 270;
UPDATE OTH.ROLE_PRIVILEGE SET  WHERE ROLE_ID = 316 AND PRIVILEGE_ID = 270;
UPDATE OTH.ROLE_PRIVILEGE SET  WHERE ROLE_ID = 317 AND PRIVILEGE_ID = 270;
UPDATE OTH.ROLE_PRIVILEGE SET  WHERE ROLE_ID = 318 AND PRIVILEGE_ID = 270;
UPDATE OTH.ROLE_PRIVILEGE SET  WHERE ROLE_ID = 319 AND PRIVILEGE_ID = 270;
UPDATE OTH.ROLE_PRIVILEGE SET  WHERE ROLE_ID = 320 AND PRIVILEGE_ID = 270;
UPDATE OTH.ROLE_PRIVILEGE SET  WHERE ROLE_ID = 567 AND PRIVILEGE_ID = 270;
UPDATE OTH.ROLE_PRIVILEGE SET  WHERE ROLE_ID = 302 AND PRIVILEGE_ID = 271;
UPDATE OTH.ROLE_PRIVILEGE SET  WHERE ROLE_ID = 306 AND PRIVILEGE_ID = 271;
UPDATE OTH.ROLE_PRIVILEGE SET  WHERE ROLE_ID = 307 AND PRIVILEGE_ID = 271;
UPDATE OTH.ROLE_PRIVILEGE SET  WHERE ROLE_ID = 308 AND PRIVILEGE_ID = 271;
UPDATE OTH.ROLE_PRIVILEGE SET  WHERE ROLE_ID = 309 AND PRIVILEGE_ID = 271;
UPDATE OTH.ROLE_PRIVILEGE SET  WHERE ROLE_ID = 310 AND PRIVILEGE_ID = 271;
UPDATE OTH.ROLE_PRIVILEGE SET  WHERE ROLE_ID = 311 AND PRIVILEGE_ID = 271;
UPDATE OTH.ROLE_PRIVILEGE SET  WHERE ROLE_ID = 312 AND PRIVILEGE_ID = 271;
UPDATE OTH.ROLE_PRIVILEGE SET  WHERE ROLE_ID = 313 AND PRIVILEGE_ID = 271;
UPDATE OTH.ROLE_PRIVILEGE SET  WHERE ROLE_ID = 314 AND PRIVILEGE_ID = 271;
UPDATE OTH.ROLE_PRIVILEGE SET  WHERE ROLE_ID = 315 AND PRIVILEGE_ID = 271;
UPDATE OTH.ROLE_PRIVILEGE SET  WHERE ROLE_ID = 316 AND PRIVILEGE_ID = 271;
UPDATE OTH.ROLE_PRIVILEGE SET  WHERE ROLE_ID = 317 AND PRIVILEGE_ID = 271;
UPDATE OTH.ROLE_PRIVILEGE SET  WHERE ROLE_ID = 318 AND PRIVILEGE_ID = 271;
UPDATE OTH.ROLE_PRIVILEGE SET  WHERE ROLE_ID = 319 AND PRIVILEGE_ID = 271;
UPDATE OTH.ROLE_PRIVILEGE SET  WHERE ROLE_ID = 320 AND PRIVILEGE_ID = 271;
UPDATE OTH.ROLE_PRIVILEGE SET  WHERE ROLE_ID = 567 AND PRIVILEGE_ID = 271;
UPDATE OTH.ROLE_PRIVILEGE SET  WHERE ROLE_ID = 302 AND PRIVILEGE_ID = 272;
UPDATE OTH.ROLE_PRIVILEGE SET  WHERE ROLE_ID = 306 AND PRIVILEGE_ID = 272;
UPDATE OTH.ROLE_PRIVILEGE SET  WHERE ROLE_ID = 307 AND PRIVILEGE_ID = 272;
UPDATE OTH.ROLE_PRIVILEGE SET  WHERE ROLE_ID = 308 AND PRIVILEGE_ID = 272;
UPDATE OTH.ROLE_PRIVILEGE SET  WHERE ROLE_ID = 309 AND PRIVILEGE_ID = 272;
UPDATE OTH.ROLE_PRIVILEGE SET  WHERE ROLE_ID = 310 AND PRIVILEGE_ID = 272;
UPDATE OTH.ROLE_PRIVILEGE SET  WHERE ROLE_ID = 311 AND PRIVILEGE_ID = 272;
UPDATE OTH.ROLE_PRIVILEGE SET  WHERE ROLE_ID = 312 AND PRIVILEGE_ID = 272;
UPDATE OTH.ROLE_PRIVILEGE SET  WHERE ROLE_ID = 313 AND PRIVILEGE_ID = 272;
UPDATE OTH.ROLE_PRIVILEGE SET  WHERE ROLE_ID = 314 AND PRIVILEGE_ID = 272;
UPDATE OTH.ROLE_PRIVILEGE SET  WHERE ROLE_ID = 315 AND PRIVILEGE_ID = 272;
UPDATE OTH.ROLE_PRIVILEGE SET  WHERE ROLE_ID = 320 AND PRIVILEGE_ID = 272;

create sequence INDICATEUR_ID_seq;
select INDICATEUR_ID_SEQ.nextval() from dual;

INSERT INTO ROLE_PRIVILEGE (ROLE_ID, PRIVILEGE_ID) VALUE(302,273);