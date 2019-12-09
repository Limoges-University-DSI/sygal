DECLARE
  di_id number := 000; -- individu id (doctorant)
  d_id number; -- doctorant id
  t_id number := THESE_ID_SEQ.nextval; -- these id
  t_title VARCHAR2(2048 CHAR) := 'Titre de la thèse'; -- these title
  a_id number := ACTEUR_ID_SEQ.nextval; -- acteur id
  ai_id number := 000; -- individu id (acteur)

BEGIN
-- NOUVEAU DOCTORANT
  BEGIN
    SELECT ID INTO d_id FROM DOCTORANT WHERE INDIVIDU_ID = di_id;
    EXCEPTION WHEN NO_DATA_FOUND THEN
      d_id := DOCTORANT_ID_SEQ.nextval;
      INSERT INTO DOCTORANT
      (
        ID,
        ETABLISSEMENT_ID,
        INDIVIDU_ID,
        SOURCE_CODE,
        SOURCE_ID,
        HISTO_CREATEUR_ID,
        HISTO_CREATION,
        HISTO_MODIFICATEUR_ID,
        HISTO_MODIFICATION
      )
      VALUES
      (
        d_id,
        21,
        di_id,
        CONCAT('TEST::', di_id),
        1,
        1,
        sysdate,
        1,
        sysdate
      );
  END;
-- NOUVELLE THESE
  INSERT INTO THESE
  (
    ID,
    ETABLISSEMENT_ID,
    DOCTORANT_ID,
    -- ECOLE_DOCT_ID,
    -- UNITE_RECH_ID,
    BESOIN_EXPURGE,
    -- COD_UNIT_RECH,
    -- CORREC_AUTORISEE,
    -- DATE_AUTORIS_SOUTENANCE,
    -- DATE_FIN_CONFID,
    -- DATE_PREM_INSC,
    -- DATE_PREV_SOUTENANCE,
    -- DATE_SOUTENANCE,
    ETAT_THESE,
    -- LIB_DISC,
    -- LIB_ETAB_COTUT,
    -- LIB_PAYS_COTUT,
    -- LIB_UNIT_RECH,
    -- RESULTAT,
    -- SOUTENANCE_AUTORIS,
    -- TEM_AVENANT_COTUT,
    TITRE,
    SOURCE_CODE,
    SOURCE_ID,
    HISTO_CREATEUR_ID,
    HISTO_CREATION,
    HISTO_MODIFICATEUR_ID,
    HISTO_MODIFICATION
    -- HISTO_DESTRUCTEUR_ID,
    -- HISTO_DESTRUCTION,
    -- CORREC_AUTORISEE_FORCEE,
    -- ANNEE_UNIV_1ERE_INSC
  )
  VALUES
  (
    t_id,
    21,
    d_id,
    -- ECOLE_DOCT_ID,
    -- UNITE_RECH_ID,
    0,
    -- COD_UNIT_RECH,
    -- CORREC_AUTORISEE,
    -- DATE_AUTORIS_SOUTENANCE,
    -- DATE_FIN_CONFID,
    -- DATE_PREM_INSC,
    -- DATE_PREV_SOUTENANCE,
    -- DATE_SOUTENANCE,
    'E',
    -- LIB_DISC,
    -- LIB_ETAB_COTUT,
    -- LIB_PAYS_COTUT,
    -- LIB_UNIT_RECH,
    -- RESULTAT,
    -- SOUTENANCE_AUTORIS,
    -- TEM_AVENANT_COTUT,
    t_title,
    CONCAT('TEST::', d_id),
    1,
    1,
    sysdate,
    1,
    sysdate
    -- HISTO_DESTRUCTEUR_ID,
    -- HISTO_DESTRUCTION,
    -- CORREC_AUTORISEE_FORCEE,
    -- ANNEE_UNIV_1ERE_INSC
  );

-- AJOUT ACTEUR : DIRECTEUR
  INSERT INTO ACTEUR
  (
    ID,
    INDIVIDU_ID,
    THESE_ID,
    ROLE_ID,
    -- QUALITE,
    -- LIB_ROLE_COMPL,
    SOURCE_CODE,
    SOURCE_ID,
    HISTO_CREATEUR_ID,
    HISTO_CREATION,
    HISTO_MODIFICATEUR_ID,
    HISTO_MODIFICATION
    -- HISTO_DESTRUCTEUR_ID,
    -- HISTO_DESTRUCTION,
    -- ACTEUR_ETABLISSEMENT_ID
  )
  VALUES
  (
    a_id,
    ai_id,
    t_id,
    43,
    -- QUALITE,
    -- LIB_ROLE_COMPL,
    'TEST::abcd_0123',
    1,
    1,
    sysdate,
    1,
    sysdate
    -- HISTO_DESTRUCTEUR_ID,
    -- HISTO_DESTRUCTION,
    -- ACTEUR_ETABLISSEMENT_ID
  );

  -- COMMIT;
END;
/