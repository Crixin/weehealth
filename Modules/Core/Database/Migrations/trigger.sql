CREATE OR REPLACE FUNCTION public.inserelog_(IN tabela character varying,IN coluna character varying,IN chave character varying,IN operacao character varying,IN valor_velho character varying,IN valor_novo character varying)
    RETURNS void
    LANGUAGE 'plpgsql'
    VOLATILE
    
    COST 100
AS $BODY$
begin
    if (valor_velho <> valor_novo) OR (valor_velho IS NULL AND valor_novo IS NOT NULL) then
      Insert into core_log (usuario, tabela, coluna, chave, operacao, valor_velho, valor_novo, created_at, updated_at)
      Values (
              Upper(Current_User),
              tabela,
              coluna,
              chave,
              operacao,
              valor_velho,
              valor_novo,
              Now(),
              Now()
            );
    end if;
  end;
$BODY$;

/*funcao geracao inicial trigger*/
CREATE OR REPLACE FUNCTION public.geracao_inicial_triggers(IN nomeschema character varying DEFAULT 'public'::character varying)
    RETURNS text
    LANGUAGE 'plpgsql'
    VOLATILE
    
    COST 100
AS $BODY$
declare
  CRLF      CONSTANT Varchar := Chr(13) || Chr(10);
  sFunction Text;
  r         Record;
begin
  sFunction := 'CREATE OR REPLACE FUNCTION ' || NomeSchema || '.criar_triggers_lote(NomeSchema Varchar default ''public'') RETURNS Text as ' || CRLF ||
               '$' || '$' || CRLF ||
               'declare' || CRLF ||
               '  CRLF    CONSTANT Varchar := Chr(13) || Chr(10);' || CRLF ||
               '  sLista  Text;' || CRLF ||
               '  sAux    Text;' || CRLF ||
               'begin' || CRLF ||
               '  -- Lista de triggers a serem executados' || CRLF ||
               '  sLista := '''';' || CRLF;

  for r in (Select t.table_name
              From information_schema.tables as t
             Where t.table_type = 'BASE TABLE'
               and t.table_schema = quote_ident(lower(NomeSchema)) and t.table_name <> 'core_log'
             Order By t.table_name
           )
  loop
    sFunction := Concat(sFunction, CRLF, '  Select ', NomeSchema, '.Cria_Function_Trigger_Log(''', r.table_name, ''', NomeSchema) Into sAux;',
                                   CRLF, '  sLista := Concat(sLista, CRLF, CRLF, sAux);', CRLF);
  end loop;

  sFunction := Concat(sFunction, CRLF, '  RETURN sLista;', CRLF, 'end;', CRLF, '$', '$', CRLF, 'LANGUAGE ''plpgsql'';');

  RETURN sFunction;
end;
$BODY$;

/*funcao trigger log*/
CREATE OR REPLACE FUNCTION public.cria_function_trigger_log(IN nometabela character varying,IN nomeschema character varying DEFAULT 'public'::character varying)
    RETURNS text
    LANGUAGE 'plpgsql'
    VOLATILE
    
    COST 100
AS $BODY$
DECLARE CRLF CONSTANT Varchar := Chr(13) || Chr(10);

sTrigger Text;

sUpdate Text;

sInsert Text;

sChave Varchar(50);

sOperacao Varchar(50);

sColName Text;

r Record;

BEGIN BEGIN
SELECT c.column_name::Varchar INTO sChave
FROM information_schema.table_constraints t
JOIN information_schema.constraint_column_usage ccu USING (CONSTRAINT_SCHEMA,
                                                           CONSTRAINT_NAME)
JOIN information_schema.columns c ON c.table_schema = t.constraint_schema
AND t.table_name = c.table_name
AND ccu.column_name = c.column_name
WHERE constraint_type = 'PRIMARY KEY'
  AND t.constraint_schema = quote_ident(lower(NomeSchema))
  AND lower(t.table_name) = quote_ident(lower(NomeTabela));

EXCEPTION WHEN NO_DATA_FOUND THEN RAISE
EXCEPTION 'Primary key não encontrada ou a tabela informada não existe: %',
          NomeTabela;

END;

-- FUNCTION
 sTrigger := '';

sTrigger := Concat(sTrigger, 'CREATE OR REPLACE FUNCTION ', NomeSchema, '.log_', lower(NomeTabela), '() RETURNS trigger AS', CRLF, '$', '$', CRLF, -- Se usar os dois na mesma string, dá erro
 'declare', CRLF, '  sOperacao Varchar(100);', CRLF, '  sChave    Varchar(100);', CRLF, '  sVazio    CONSTANT Varchar := '''';', CRLF, 'begin', CRLF, CRLF, -- Se colocar :: a saída é ::::
 '  if TG_OP = ''UPDATE'' AND new.deleted_at is not null then', CRLF, '    sOperacao := ''DELETE'';', CRLF, '    sChave := old.', sChave, ':', ':varchar;');

sUpdate := '';

sUpdate := Concat(sUpdate, '  elsif TG_OP = ''UPDATE'' AND new.deleted_at is null then', CRLF, '    sOperacao := ''UPDATE'';', CRLF, '    sChave := old.', sChave, ':', ':varchar;');

sInsert := '';

sInsert := Concat(sInsert, '  elsif TG_OP = ''INSERT'' then', CRLF, '    sOperacao := ''INSERT'';', CRLF, '    sChave := new.', sChave, ':', ':varchar;');

FOR r in
  (SELECT c.ordinal_position,
          c.column_name,
          c.data_type
   FROM information_schema.tables AS t
   JOIN information_schema.columns AS c ON t.table_schema = c.table_schema
   AND t.table_name = c.table_name
   WHERE t.table_type = 'BASE TABLE'
     AND t.table_schema = quote_ident(lower(NomeSchema))
     AND lower(t.table_name) = quote_ident(lower(NomeTabela))
   ORDER BY c.ordinal_position) LOOP IF lower(r.data_type) in ('text',
                                                               'character varying',
                                                               'character') THEN sColName := r.column_name;

ELSE sColName := r.column_name || '::varchar';

END IF;

sTrigger := Concat(sTrigger, CRLF, '    PERFORM InsereLog(''', NomeTabela, ''', ', RPad('''' || lower(r.column_name) || ''',', Greatest(30, Length(r.column_name) + 3)), ' sChave, sOperacao, old.', RPad(sColName || ',', Greatest(30, Length(sColName) + 3)), ' new.', sColName, ');');

sUpdate := Concat(sUpdate, CRLF, '    PERFORM InsereLog(''', NomeTabela, ''', ', RPad('''' || lower(r.column_name) || ''',', Greatest(30, Length(r.column_name) + 3)), ' sChave, sOperacao, old.', RPad(sColName || ',', Greatest(30, Length(sColName) + 3)), ' new.', sColName, ');');

sInsert := Concat(sInsert, CRLF, '    PERFORM InsereLog(''', NomeTabela, ''', ', RPad('''' || lower(r.column_name) || ''',', Greatest(30, Length(r.column_name) + 3)), ' sChave, sOperacao, sVazio,', ' new.', sColName, ');');

END LOOP;

sTrigger := Concat(sTrigger, CRLF, sUpdate, CRLF, sInsert, CRLF,'  end if;');

sTrigger := Concat(sTrigger, CRLF, '  RETURN old;', CRLF, 'end;', CRLF, '$', '$', CRLF, 'LANGUAGE ''plpgsql'';');

-- TRIGGER
 sTrigger := Concat(sTrigger, CRLF, CRLF, 'DROP TRIGGER IF EXISTS A_',upper(NomeTabela), CRLF, '  ON ', lower(NomeSchema), '.',lower(NomeTabela),';',CRLF, CRLF,'CREATE TRIGGER A_', upper(NomeTabela), CRLF, '  AFTER INSERT OR DELETE OR UPDATE', CRLF, '  ON ', lower(NomeSchema), '.', lower(NomeTabela), CRLF, 'FOR EACH ROW', CRLF, '  EXECUTE PROCEDURE ', lower(NomeSchema), '.log_', lower(NomeTabela), '();');

RETURN sTrigger;

END;
$BODY$;