<?php
/**
 * This file is part of the GLIZY framework.
 * Copyright (c) 2005-2012 Daniele Ugoletti <daniele.ugoletti@glizy.com>
 *
 * For the full copyright and license information, please view the COPYRIGHT.txt
 * file that was distributed with this source code.
 */

$strings = array (
	'GLZ_DATE_FORMAT' 				=>	"d/m/Y",
	'GLZ_DATETIME_FORMAT' 			=>	"d/m/Y H:i:s",
	'GLZ_DATE_TOISO_REGEXP' 		=>	array("|^(\d{1,2})\/(\d{1,2})\/(\d{2,4})$|", "$3-$2-$1"),
	'GLZ_DATE_TOTIME_REGEXP' 		=>	array("|^(\d{1,2})\/(\d{1,2})\/(\d{2,4})$|", "$3-$2-$1 00:00:00"),
	'GLZ_DATETIME_TOTIME_REGEXP' 	=>	array("|^(\d{1,2})\/(\d{1,2})\/(\d{2,4})\s*(\d{1,2}):(\d{1,2}):(\d{1,2})$|", "$3-$2-$1 $4:$5:$6"),
	'GLZ_TIME_TO_DATE_REGEXP' 		=>	array("|^(\d{2,4})-(\d{1,2})-(\d{1,2})$|", "$3/$2/$1"),
	'GLZ_TIME_TO_DATETIME_REGEXP' 	=>	array("|^(\d{2,4})-(\d{1,2})-(\d{1,2})\s*(\d{1,2}):(\d{1,2}):(\d{1,2})$|", "$3/$2/$1 $4:$5:$6"),
	"GLZ_MONTHS" 					=>	array("Gennaio", "Febbraio", "Marzo", "Aprile", "Maggio", "Giugno", "Luglio", "Agosto", "Settembre", "Ottobre", "Novembre", "Dicembre" ),
	"GLZ_WARNING" => "Attenzione",
	"GLZ_ERROR" => "Errore",
	"GLZ_NEW_PAGE" => "Nuova pagina",
	"GLZ_CREATINING_NEW_PAGE" => "Creazione nuova pagina",
	"GLZ_INSERT_NEW_PAGE" => "Aggiunta nuova pagina",
	"GLZ_PAGE_TITLE" => "Titolo",
	"GLZ_PAGE_TITLE_LINK" => "Attributo Title per link nella navigazione",
	"GLZ_PAGE_TITLE_ALT" => "Alt per link nella navigazione",
	"GLZ_PAGE_SELECT" => "Selezionare la pagina",
	"GLZ_PAGE_SELECT_PARENT" => "Selezionare la pagina padre",
	"GLZ_PAGE_SELECT_TYPE" => "Selezionare il tipo della pagina",
	"GLZ_PAGE_PAGEID" => "Identificativo univoco della pagina",
	"GLZ_PAGE_LINKED_URL" => "URL collegato",
	"GLZ_PAGE_KEYWORDS" => "Parole chiave",
	"GLZ_PAGE_DESCRIPTION" => "Breve descrizione",
	"GLZ_CONFIRM" => "Conferma",
	"GLZ_CANCEL" => "Annulla",
	"GLZ_UPLOAD" => "Carica file",
	"GLZ_RESET" => "Azzera",
	"GLZ_PREVIEW" => "Visualizza pagina",
	"GLZ_SEARCH" => "Cerca",
	"GLZ_NEW_SEARCH" => "Nuova ricerca",
	"GLZ_PUBLISHED" => "Pubblicato, premere per cambiare lo stato",
	"GLZ_UNPUBLISHED" => "Non pubblicato, premere per cambiare lo stato",
	"GLZ_SEARCH_FILTERS" => "Filtri di ricerca",
	"GLZ_SEARCH_MEDIA" => "",
	"GLZ_SITEMAP_TITLE" => "Struttura del sito",
	"GLZ_SITEMAP_MSG_DELETE" => "Siete sicuri di voler cancellare la pagina?",
	"GLZ_SITEMAP_NEW" => "Aggiungi una nuova pagina di livello inferiore",
	"GLZ_SITEMAP_UP" => "Sposta la pagina in alto",
	"GLZ_SITEMAP_DOWN" => "Sposta la pagina in basso",
	"GLZ_SITEMAP_EDIT" => "Modifica i contenuti della pagina",
	"GLZ_SITEMAP_EDITDRAFT" => "Modifica la bozza della pagina",
	"GLZ_SITEMAP_DELETE" => "Rimuovi la pagina e tutte le sottopagine",
	"GLZ_SITEMAP_PUBLISH" => "Pubblica la pagina",
	"GLZ_SITEMAP_VISIBLE" => "Rende la pagina non visibile",
	"GLZ_SITEMAP_INVISIBLE" => "Rende la pagina visibile",
	"GLZ_SITEMAP_PREVIEW" => "Visualizza la pagina",
	"GLZ_SITEMAP_LOCK" => "Modifica lo stato della pagina: protetta/non protetta",
	"GLZ_MODIFY_PAGE_CONTENT" => "Modifica pagina",
	"GLZ_MODIFY_PAGE_DETAILS" => "Modifica proprietà della pagina",
	"GLZ_RECORD_ADD_BEFORE" => "Aggiungi un record prima di questo",
	"GLZ_RECORD_ADD" => "Aggiungi",
	"GLZ_RECORD_MOVE_UP" => "Sposta in alto",
	"GLZ_RECORD_MOVE_DOWN" => "Sposta in basso",
	"GLZ_RECORD_DELETE" => "Cancella",
	"GLZ_RECORD_DELETE_VERSION" => "Cancella versione",
	"GLZ_RECORD_EDIT" => "Modifica",
	"GLZ_RECORD_PUBLISH" => "Pubblica",
	"GLZ_RECORD_PREVIEW" => "Anteprima",
	"GLZ_RECORD_MSG_DELETE" => "Siete sicuri di voler cancellare il record selezionato?",
	"GLZ_RECORDS_MSG_DELETE" => "Siete sicuri di voler cancellare i record selezionati?",
	"GLZ_RECORD_NEW_TITLE" => "Inserimento record in '%s'",
	"GLZ_RECORD_EDIT_TITLE" => "Modifica record in '%s'",
	"GLZ_RECORD_EDITDEF_TITLE" => "Modifica record",
	"GLZ_SAVE" => "Salva",
	"GLZ_SAVE_DRAFT" => "Salva come bozza",
	"GLZ_SAVE_DRAFT_CLOSE" => "Salva come bozza e chiudi",
	"GLZ_SAVE_CLOSE" => "Salva e chiudi",
	"GLZ_CLOSE" => 'Chiudi',
	"GLZ_PAGE_SUBTITLE" => "Sottotitolo",
	"GLZ_JUMP_TO" => "Vai alla pagina:",
	"GLZ_CLOSE_PICKER" => "Chiudi",
	"GLZ_ADD_MEDIA" => "Aggiungi media",
	"GLZ_PAGE_NUM" => "Pagina",
	"GLZ_MEDIAM_IMAGELIST" => "Immagini disponibili",
	"GLZ_MEDIAM_MEDIALIST" => "Media disponibili",
	"GLZ_MEDIAM_EDIT" => "Modifica media",
	"GLZ_MEDIAM_PREVIEW" => "Anteprima",
	"GLZ_MEDIAM_TITLE" => "Titolo",
	"GLZ_MEDIAM_SHORTTITLE" => "Titolo breve",
	"GLZ_MEDIAM_NULL" => "Nessuna selezione",
	"GLZ_MEDIAM_CHOICE" => "Seleziona media",
	"GLZ_MEDIAM_NO_IMG" => "Nessuna immagine selezionata, premere per selezionarne una.",
	"GLZ_MEDIAM_CURRENT_IMG" => "Immagine corrente:",
	"GLZ_MEDIAM_IMG_CHANGED" => "Immagine cambiata",
	"GLZ_MEDIAM_CUSTOM_PATH" => "Percorso upload",
	"GLZ_MEDIAM_FILE_TYPE" => "Tipo di file",
	"GLZ_MEDIAM_SELECT_FILE" => "Selezionare il file",
	"GLZ_MEDIAM_UPLOAD_GENERIC_TITLE" => "Carica un Media",
	"GLZ_MEDIAM_UPLOAD_TITLE" => "Carica un file di tipo: ",
	"GLZ_MEDIAM_UPLOAD_DONE" => "Nuovo file caricato con successo",
	"GLZ_MEDIAM_DELETE_DONE" => "File rimosso dall'archivio",
	"GLZ_MEDIAM_NO_FILE" => "Nessun file selezionato, premere per selezionarne uno.",
	"GLZ_MEDIAM_EDITING" => "Modifica proprietà media",
	"GLZ_MEDIAM_MSG_DELETE" => "Siete sicuri di voler cancellare il media selezionato ?",
	"GLZ_MEDIAM_ERR_NO_FILE" => "Nessun file selezionato",
	"GLZ_MEDIAM_ERR_INI_SIZE" => "File di dimensione troppo grande",
	"GLZ_MEDIAM_ERR_PARTIAL" => "Upload non completo",
	"GLZ_MEDIAM_ERR_FORM_SIZE" => "File di dimensione troppo grande",
	"GLZ_MEDIAM_ERR_CREATE_DIR" => "Errore nella creazione della cartella: '%s'",
	"GLZ_MEDIAM_ERR_COPYING" => "Errore nella copia del file, possibile disco pieno.",
	"GLZ_MEDIAM_ERR_IMAGE_SIZE" => "Errore: l'immagine che è stata caricata eccede le dimensioni massime concesse (%s)",
	"GLZ_MEDIAM_ERR_NOGDLOADED" => "PHP gd libraries plugin non abilitato",
	"GLZ_MEDIA_TITLE" => "Titolo",
	"GLZ_MEDIA_FILENAME" => "Nome del file",
	"GLZ_MEDIA_CATEGORY" => "Categoria",
	"GLZ_MEDIA_SIZE" => "Dimensione",
	"GLZ_ADD_NEW_RECORD" => "Aggiungi nuovo record",
	"GLZ_SAVE_REVISION" => "Salva come nuova revisione",
	"GLZ_MODIFY_LIST" => "Elenco modifiche da approvare",
	"GLZ_CHANGE_LANGUAGE" => "Cambia lingua",
	"GLZ_PAGE_CONTENT" => "Contenuti della pagina",
	"GLZ_PAGE_PROP" => "Proprietà della pagina",
	"GLZ_MODIFY1" => "Modifica la versione pubblicata",
	"GLZ_MODIFY2" => "Modifica l'ultima revisione",
	"GLZ_LAST_REVSION" => "Ultima revisione",
	"GLZ_LOGIN_ERROR" => "Utente e/o password inesistenti. Riprovare.",
	"GLZ_LOGIN_NOACCESS" => "Accesso non consentito, è necessario effettuare l'autenticazione.",
	"LOGGER_INSUFFICIENT_GROUP_LEVEL" => "La pagina richiesta richiede un maggiore livello di accesso.",
	"LOGGER_INSUFFICIENT_USER_LEVEL" => "La pagina richiesta non &egrave; disponibile con l'accesso corrente. Contattate l'amministratore di sistema.",
	"GLZ_LOGIN_DISABLED" => "Il Suo account è stato disabilitato. Contattare l'amministratore di sistema.",
	"GLZ_LANGUAGE_LIST" => "Elenco lingue",
	"GLZ_LANGUAGE_EDIT" => "Modifica lingua",
	"GLZ_LANGUAGE" => "Lingua",
	"GLZ_LANGUAGE_CODE" => "Codice",
	"GLZ_LANGUAGE_DEFAULT" => "Lingua principale",
	"GLZ_LANGUAGE_ORDER" => "Ordine",
	"GLZ_LABEL" => "Etichetta",
	"GLZ_EDIT_LANGUAGE" => "Lingua inserimento dati",
	"GLZ_SEARCH_LABEL" => "Parola da ricercare:",
	"GLZ_SEARCH_BUTTON" => "Cerca",
	"GLZ_SEARCH_COMMENT" => "La ricerca viene effettuata su parole con più di 3 caratteri",
	"GLZ_SEARCH_RESULT" => "Risultato della ricerca",
	"GLZ_SEARCH_RESULT_TOTAL" => "Pagine trovate:",
	"LOGGED_MESSAGE" => "Benvenuto/a %s",
	"GLZ_ERR_EMPTY_APP_PATH" => "Il percorso dell'applicazione non può essere vuoto",
	"GLZ_ERR_NO_PAGETYPE_FOLDER" => "Non si può aprire il pageTipe application folder.",
	"GLZ_ERR_404" => "Pagina non trovata",
	"GLZ_USER_LIST_TITLE" => "Elenco utenti",
	"GLZ_USER_EDIT_TITLE" => "Modifica utente",
	"GLZ_USER_EDIT" => "Modifica utente",
	"GLZ_USER_FIRST" => "Nome",
	"GLZ_USER_LAST" => "Cognome",
	"GLZ_USER_EMAIL" => "Email",
	"GLZ_USER_ACTIVE" => "Attivo",
	"GLZ_USER_IS_ACTIVE" => "Utente Attivo",
	"GLZ_USER_LOGINID" => "Nome utente",
	"GLZ_USER_PASSWORD" => "Password",
	"GLZ_USER_GROUP" => "Gruppo",
	"GLZ_USER_ADD_NEW_RECORD" => "Aggiungi un nuovo utente",
	"GLZ_USERGROUP_ADD_NEW_RECORD" => "Aggiungi un nuovo gruppo",
	"GLZ_LOGIN_REMEMBER" => "Ricorda login",
	"GLZ_ENABLED" => "Abilitato",
	"GLZ_ENABLE" => "Abilita",
	"GLZ_DISABLED" => "Disabilitato",
	"GLZ_DISABLE" => "Disabilita",
	"GLZ_USERGROUP_LIST_TITLE" => "Elenco gruppi",
	"GLZ_USERGROUP_EDIT_TITLE" => "Modifica gruppo",
	"GLZ_USERGROUP_EDIT" => "Modifica gruppo",
	"GLZ_USERGROUP_NAME" => "Nome gruppo",
	"GLZ_USERGROUP_INTERNAL" => "Utenti interni",
	"GLZ_MODULE_SHOW_TITLE" => "Visualizza il titolo della pagina?",
	"GLZ_MODULE_LINKED" => "Modulo da collegare",
	"GLZ_TOTAL_RECORDS" => "Totale record:",
	"GLZ_PROTECTED_PAGE" => "Pagina riservata",
	"GLZ_LINKED_URL" => "URL collegato",
	"GLZ_CREATION_DATE" => "Data di creazione",
	"GLZ_DOWNLOAD_FILE" => "Scarica file",
	"GLZ_DOWNLOAD_FILE_LINK" => "Scarica file: #title#, #size#",
	"GLZ_ADDMEDIA" => "Aggiungi Media",
	"GLZ_NOIMAGE" => "Nessuna Immagine",
	"GLZ_NOMEDIA" => "Nessuna media",
	"GLZ_URL_LINK" => "Url link",
	"GLZ_PRINT_PDF" => "Stampa PDF",
	"GLZ_ENABLE_PAGE_COMMENTS" => "Abilita commenti",
	"GLZ_MESSAGE_DELETE_SUCCESS" => "Record cancellato con successo.",
	"GLZ_PREVIOUS" => "precedente",
  	"GLZ_NEXT" => "successiva",
  	"GLZ_COLSE"=> "chiudi",
  	"GLZ_SLIDESHOW_START" => "inizia slideshow",
  	"GLZ_SLIDESHOW_STOP" => "ferma slideshow",
);
org_glizy_locale_Locale::append($strings);