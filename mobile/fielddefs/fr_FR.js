var RES_CREATE='Créer',RES_LIST='Liste',RES_ADD_TO_HOME='Enregistrer comme favori',RES_REM_FROM_HOME='Retirer de Mes Favoris',RES_RECORD_ERROR='Echec de l&#039;affichage de cet enregistrement. Il peut avoir été supprimé ou vous n&#039;êtes peut-être pas autorisé à le consulter.',RES_LAST_VIEWED='Vu récemment', RES_DELETE_CONFIRMATION='Etes-vous certain de vouloir supprimer cet enregistrement ?', RES_DEL_LABEL='Supprimer', RES_NEXT_LABEL='Suivante', RES_PREVIOUS_LABEL='Précédente';
var RES_HOME_LABEL='Accueil',RES_SYNC='Synchroniser',RES_SAVEDSEARCH='Recherches enregistrées',RES_SAVESEARCH='Enregistrer la recherche sous:';
var sugar_app_list_strings = jQuery.parseJSON('{"moduleList":{"Accounts":"Comptes","Contacts":"Contacts","Opportunities":"Opportunit\u00e9s","Leads":"Leads","Calls":"Appels","Meetings":"Rendez-vous","Tasks":"T\u00e2ches","Notes":"Notes","Project":"Projets","ProjectTask":"T\u00e2ches de projet","Cases":"Tickets","Users":"Utilisateurs","SavedSearches":"Recherches enregistr\u00e9es"},"moduleListSingular":{"Accounts":"Compte","Contacts":"Contact","Opportunities":"Opportunit\u00e9","Leads":"Lead","Calls":"Appel","Meetings":"Rendez-vous","Tasks":"T\u00e2che","Notes":"Note","Project":"Projet","ProjectTask":"T\u00e2che d&#039;un projet","Cases":"Ticket","Users":"Utilisateur"},"parent_type_display":{"Accounts":"Compte","Contacts":"Contact","Tasks":"T\u00e2che","Opportunities":"Opportunit\u00e9","Products":"Produit","Quotes":"Devis","Bugs":"Gestion des bugs","Cases":"Ticket","Leads":"Lead","Project":"Projet","ProjectTask":"T\u00e2che d&#039;un projet","Prospects":"Cible"},"date_search":{"=":"Egal","not_equal":"Pas le","greater_than":"Apr\u00e8s","less_than":"Avant","last_7_days":"Les 7 derniers jours","next_7_days":"Les 7 prochains jours","last_30_days":"Les derniers 30 jours","next_30_days":"Les 30 prochains jours","last_month":"Le mois dernier","this_month":"Ce mois-ci","next_month":"Mois suivant","last_year":"L&#039;ann\u00e9e derni\u00e8re","this_year":"Cette ann\u00e9e","next_year":"Ann\u00e9e suivante","between":"entre","today":"Aujourd&#039;hui"},"account_type_dom":{"":"","Analyst":"Analyste","Competitor":"Concurrent","Customer":"Client","Integrator":"Int\u00e9grateur","Investor":"Investisseur","Partner":"Partenaire","Press":"Presse","Prospect":"Prospect","Reseller":"Revendeur","Other":"Autre"},"industry_dom":{"":"","Apparel":"Textile","Banking":"Banque","Biotechnology":"Bio-technologie","Chemicals":"Chimie","Communications":"Communication","Construction":"Construction","Consulting":"Conseil","Education":"Enseignement","Electronics":"Electronique","Energy":"Energie","Engineering":"Ing\u00e9nierie","Entertainment":"Loisirs","Environmental":"Environnement","Finance":"Finance","Government":"Gouvernement","Healthcare":"Sant\u00e9","Hospitality":"Centre de soins","Insurance":"Assurance","Machinery":"Outillage","Manufacturing":"Fabrication","Media":"M\u00e9dias","Not For Profit":"Association","Recreation":"Divertissements","Retail":"D\u00e9taillant","Shipping":"Livraison","Technology":"Technologie","Telecommunications":"T\u00e9l\u00e9communications","Transportation":"Transport","Utilities":"Services aux entreprises","Other":"Autre"},"salutation_dom":{"":"","Mr.":"Monsieur","Ms.":"Mlle","Mrs.":"Madame","Dr.":"Docteur","Prof.":"Professeur"},"lead_source_dom":{"":"","Cold Call":"Appel classique","Existing Customer":"Client existant","Self Generated":"G\u00e9n\u00e9r\u00e9 par mes soins","Employee":"Collaborateur","Partner":"Partenaire","Public Relations":"Relations publiques","Direct Mail":"Courrier envoy\u00e9","Conference":"Conf\u00e9rence","Trade Show":"Salon","Web Site":"Site Web","Word of mouth":"Bouche \u00e0 oreille","Email":"Email","Campaign":"Campagne","Other":"Autre"},"opportunity_relationship_type_dom":{"":"","Primary Decision Maker":"D\u00e9cisionnaire final","Business Decision Maker":"D\u00e9cisionnaire important","Business Evaluator":"Contr\u00f4leur","Technical Decision Maker":"D\u00e9cisionnaire technique","Technical Evaluator":"Contr\u00f4leur technique","Executive Sponsor":"Sponsor important","Influencer":"Influenceur","Other":"Autre"},"dom_meeting_accept_status":{"accept":"Accept\u00e9","decline":"D\u00e9clin\u00e9","tentative":"R\u00e9flexion","none":"Aucun"},"opportunity_type_dom":{"":"","Existing Business":"Client existant","New Business":"Nouvelle affaire"},"sales_stage_dom":{"Prospecting":"Prospection","Qualification":"Qualification","Needs Analysis":"Analyse besoins","Value Proposition":"Chiffrage","Id. Decision Makers":"Ident. d\u00e9cideurs","Perception Analysis":"Evaluation","Proposal\/Price Quote":"Proposition\/Devis","Negotiation\/Review":"N\u00e9go.\/Corrections","Closed Won":"Gagn\u00e9","Closed Lost":"Perdu"},"lead_status_dom":{"":"","New":"Nouveau","Assigned":"Assign\u00e9","In Process":"en cours de proc\u00e9dure","Converted":"Converti","Recycled":"R\u00e9utilis\u00e9","Dead":"Abandonn\u00e9"},"call_status_dom":{"Planned":"Planifi\u00e9e","Held":"Tenue","Not Held":"Non tenue"},"call_direction_dom":{"Inbound":"Entrant","Outbound":"Sortant"},"reminder_time_options":{"60":"1 minute avant","300":"5 minutes avant","600":"10 minutes avant","900":"15 minutes avant","1800":"30 minutes avant","3600":"1 heure avant","7200":"2 heures avant","10800":"3 heures avant","18000":"5 heures avant","86400":"1 jour avant"},"repeat_type_dom":{"":"Aucun","Daily":"Quotidiennement","Weekly":"De mani\u00e8re hebdomadaire","Monthly":"Mensuellement","Yearly":"Annuellement"},"meeting_status_dom":{"Planned":"Planifi\u00e9e","Held":"Tenue","Not Held":"Non tenue"},"eapm_list":{"Sugar":"Sugar","WebEx":"WebEx","GoToMeeting":"GoToMeeting","IBMSmartCloud":"IBM SmartCloud","Google":"Google","Box":"Box.net","Facebook":"Facebook","Twitter":"Twitter"},"duration_dom":{"":"Aucun","900":"15 minutes","1800":"30 minutes","2700":"45 minutes","3600":"1 heure","5400":"1,5 heures","7200":"2 heures","10800":"3 heures","21600":"6 heures","86400":"1 jour","172800":"2 jours","259200":"3 jours","604800":"1 semaine"},"task_status_dom":{"Not Started":"Non d\u00e9marr\u00e9","In Progress":"En cours","Completed":"Termin\u00e9e","Pending Input":"Attentes d&#039;informations","Deferred":"D\u00e9cal\u00e9e"},"task_priority_dom":{"High":"Haute","Medium":"Normale","Low":"Basse"},"project_status_dom":{"Draft":"Brouillon","In Review":"En attente","Published":"Publi\u00e9"},"projects_priority_options":{"high":"Haute","medium":"Normale","low":"Basse"},"project_task_status_options":{"Not Started":"Non d\u00e9marr\u00e9","In Progress":"En cours","Completed":"Termin\u00e9e","Pending Input":"Attentes d&#039;informations","Deferred":"D\u00e9cal\u00e9e"},"project_task_priority_options":{"High":"Haute","Medium":"Normale","Low":"Basse"},"case_type_dom":{"Administration":"Administration","Product":"Produit","User":"Utilisateur"},"case_status_dom":{"New":"Nouveau","Assigned":"Assign\u00e9","Closed":"Ferm\u00e9","Pending Input":"Attentes d&#039;informations","Rejected":"Rejet\u00e9","Duplicate":"Dupliquer"},"case_priority_dom":{"P1":"Haute","P2":"Normale","P3":"Basse"}}');var sugar_app_strings = jQuery.parseJSON('{"LBL_CREATE_BUTTON_LABEL":"Cr\u00e9er","LBL_EDIT_BUTTON":"Editer","LBL_LIST":"Liste","LBL_SEARCH_BUTTON_LABEL":"Rechercher","LBL_CURRENT_USER_FILTER":"Seulement mes \u00e9l\u00e9ments:","LBL_BACK":"Retour","LBL_SAVE_BUTTON_LABEL":"Enregistrer","LBL_CANCEL_BUTTON_LABEL":"Annuler","LBL_MARK_AS_FAVORITES":"Enregistrer comme favori","LBL_REMOVE_FROM_FAVORITES":"Retirer de Mes Favoris","NTC_DELETE_CONFIRMATION":"Etes-vous certain de vouloir supprimer cet enregistrement ?","LBL_DELETE_BUTTON_LABEL":"Supprimer","ERROR_NO_RECORD":"Echec de l&#039;affichage de cet enregistrement. Il peut avoir \u00e9t\u00e9 supprim\u00e9 ou vous n&#039;\u00eates peut-\u00eatre pas autoris\u00e9 \u00e0 le consulter.","LBL_LAST_VIEWED":"Vu r\u00e9cemment","LNK_LIST_NEXT":"Suivante","LNK_LIST_PREVIOUS":"Pr\u00e9c\u00e9dente","LBL_LIST_USER_NAME":"Nom d&#039;utilisateur","NTC_LOGIN_MESSAGE":"Veuillez saisir votre nom d&#039;utilisateur et votre mot de passe.","LBL_LOGOUT":"D\u00e9connexion","ERR_INVALID_EMAIL_ADDRESS":"Ceci n&#039;est pas une adresse email correcte","LBL_ASSIGNED_TO":"Assign\u00e9 \u00e0:","LBL_CLEAR_BUTTON_LABEL":"Effacer","LBL_DURATION_DAYS":"jours","LBL_CLOSE_AND_CREATE_BUTTON_TITLE":"Fermer et Creer Nouveau","LBL_CLOSE_BUTTON_TITLE":"Fermer"}');