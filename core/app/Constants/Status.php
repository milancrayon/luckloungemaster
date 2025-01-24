<?php

namespace App\Constants;

class Status {

    const ENABLE  = 1;
    const DISABLE = 0;

    const YES = 1;
    const NO  = 0;

    const VERIFIED   = 1;
    const UNVERIFIED = 0;

    CONST TICKET_OPEN   = 0;
    CONST TICKET_ANSWER = 1;
    CONST TICKET_REPLY  = 2;
    CONST TICKET_CLOSE  = 3;

    CONST PRIORITY_LOW    = 1;
    CONST PRIORITY_MEDIUM = 2;
    CONST PRIORITY_HIGH   = 3;

    const USER_ACTIVE = 1;
    const USER_BAN    = 0;

    const KYC_UNVERIFIED = 0;
    const KYC_PENDING    = 2;
    const KYC_VERIFIED   = 1;

    const GOOGLE_PAY = 5001;

    const CUR_BOTH = 1;
    const CUR_TEXT = 2;
    const CUR_SYM  = 3;

    const WIN  = 1;
    const LOSS = 0;

    const GAME_RUNNING  = 0;
    const GAME_FINISHED = 1;


    const BET_UNCONFIRMED = 0;
    const BET_WIN         = 1;
    const BET_PENDING     = 2;
    const BET_LOSE        = 3;
    const BET_REFUNDED    = 4;

    const FRACTION_ODDS = 1;
    const DECIMAL_ODDS  = 2;

    const QUESTION_LOCKED   = 1;
    const QUESTION_UNLOCKED = 0;

    const OPTION_LOCKED   = 1;
    const OPTION_UNLOCKED = 0;

    const WINNER = 1;
    const LOSER  = 0;

    const LOSE   = 1;
    const REFUND = 1; 

    const SINGLE_BET = 1;
    const MULTI_BET  = 2;

    const DECLARED   = 1;
    const UNDECLARED = 0;

}
