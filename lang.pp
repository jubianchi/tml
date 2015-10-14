%skip       T_SPACE                 \s

%token      T_OPEN_TAG              <\?php
%token      T_CLOSE_TAG             \?>
%token      T_VAR                   \$[a-zA-Z][a-zA-Z0-9]?

%token      T_LEFT_PAREN            \(
%token      T_RIGHT_PAREN           \)

%token      T_LEFT_CURLY            {
%token      T_RIGHT_CURLY           }

%token      T_ARROW                 =>
%token      T_COMMA                 ,
%token      T_SEMI_COLON            ;

%token      T_CODE                  [^;{}]+

php:
    ::T_OPEN_TAG:: function()+ ::T_CLOSE_TAG::?

function:
    arguments() ::T_ARROW:: body() ::T_SEMI_COLON::+

#arguments:
    <T_VAR> | ::T_LEFT_PAREN:: <T_VAR> ( ::T_COMMA:: <T_VAR> )* ::T_RIGHT_PAREN::

#body:
    <T_CODE> | ::T_LEFT_CURLY:: code() ::T_RIGHT_CURLY::

code:
    <T_CODE> ::T_SEMI_COLON::* ( <T_LEFT_CURLY> code() <T_RIGHT_CURLY> )*
