<? //@codeCoverageIgnoreStart
defined("FKN") or http_response_code(403).die('Forbidden!');

class eERROR
{
    //##########################################################################
    //### BASE ###
    //##########################################################################
    const NONE           =   0;
    const GENERIC        = 400;
    //##########################################################################
    //### DIRECT ERRORS ###
    //##########################################################################
    // Errors referring the immediate code where it happened.
    //##########################################################################
    const MANDATORY      = 428;
    const FORBIDDEN      = 403;
    const NOT_FOUND      = 404;
    const NOT_ACCEPTABLE = 406;

    const OUT_OF_RANGE   = 416;

    const EXPIRED        = 489;
    
    const INTERNAL       = 500;
    //##################################################################################################################
    //### STEPS OF THE LOGICAL FLOW ###
    //##################################################################################################################
    const SERVER        = 5009000; //FASE 1: Configs are loaded and the setup of behaviors and paths are set.
    const SETUP         = 5159000; //FASE 1: Configs are loaded and the setup of behaviors and paths are set.
    //##################################################################################################################
    const INPUT         = 5319000; //FASE 3: The main flow starts with inputs..
    //##################################################################################################################
    const RUN          = 5419000; //FASE 4: Main flow objective. Rules (Sanitizes and Validation), Processing, Transactions.
    const RULE          = 5419000; //FASE 4: Main flow objective. Rules (Sanitizes and Validation), Processing, Transactions.
    const PROCESS       = 5429000; //
    const TRANSACTION   = 5439000; //  Transactions, with external resources like databases, caches, http requests, etc.
    //##################################################################################################################
    const POST_RULE     = 5519000; //FASE 5: Some rules and actions that must be made after the main processing,
    const POST_PROCESS  = 5529000; //  like send and email informing changes, update references, optimize indexes, etc.
    //##################################################################################################################
    const AFTER         = 5619000; //FASE 6: Sometimes still need to do some actions after everything. Is this step...
    //##################################################################################################################
}

//@codeCoverageIgnoreEnd
