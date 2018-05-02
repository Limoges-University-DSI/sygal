
create table S_ACTEUR as
  select
    r.source_code as role_source_code,
    a.ID,
    a.SOURCE_ID,
    a.SOURCE_CODE,
    a.HISTO_CREATION,
    a.HISTO_CREATEUR_ID,
    a.HISTO_MODIFICATION,
    a.HISTO_MODIFICATEUR_ID,
    a.HISTO_DESTRUCTION,
    a.HISTO_DESTRUCTEUR_ID,
    a.INDIVIDU_ID,
    a.ROLE_ID,
    a.THESE_ID,
    a.LIB_ROLE_COMPL,
    a.QUALITE,
    a.ETABLISSEMENT
  from sodoct.ACTEUR@doctprod a
    join sodoct.USER_ROLE@doctprod r on r.id = a.role_id;

truncate table acteur;
drop sequence ACTEUR_ID_SEQ;
create sequence ACTEUR_ID_SEQ;

-- ACTEUR

insert into ACTEUR (
  ID,
  ETABLISSEMENT,
  HISTO_CREATEUR_ID,
  HISTO_CREATION,
  HISTO_DESTRUCTEUR_ID,
  HISTO_DESTRUCTION,
  HISTO_MODIFICATEUR_ID,
  HISTO_MODIFICATION,
  INDIVIDU_ID,
  LIB_ROLE_COMPL,
  QUALITE,
  ROLE_ID,
  SOURCE_CODE,
  SOURCE_ID,
  THESE_ID
)
  select
    ID,
    ETABLISSEMENT,
    HISTO_CREATEUR_ID,
    HISTO_CREATION,
    HISTO_DESTRUCTEUR_ID,
    HISTO_DESTRUCTION,
    HISTO_MODIFICATEUR_ID,
    HISTO_MODIFICATION,
    INDIVIDU_ID, -- NB: on a repris texto les id SODOCT des individus acteurs
    LIB_ROLE_COMPL,
    QUALITE,
    (select id from ROLE where SOURCE_CODE = 'UCN::' || s.ROLE_SOURCE_CODE) as ROLE_ID,
    'UCN::' || SOURCE_CODE,
    (select id from source where code = 'UCN::apogee') as SOURCE_ID,
    THESE_ID -- NB: on a repris texto les id SODOCT des theses
  from S_ACTEUR s
;


DECLARE
  maxid NUMBER;
  nextval NUMBER;
BEGIN
  select max(id) into maxid from ACTEUR;
  loop
    select ACTEUR_ID_SEQ.nextval into nextval from dual;
    EXIT WHEN maxid < nextval;
  end loop;
END;
/


--

drop table S_ACTEUR;