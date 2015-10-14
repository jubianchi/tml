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
    argument_list() ::T_ARROW:: body() ::T_SEMI_COLON::+

#argument_list:
    argument() | arguments()

arguments:
    argument() | ::T_LEFT_PAREN:: argument() ( ::T_COMMA:: argument() )* ::T_RIGHT_PAREN::

argument:
    <T_VAR>

#body:
    <T_CODE> | ::T_LEFT_CURLY:: code() ::T_RIGHT_CURLY::

code:
    <T_CODE> ::T_SEMI_COLON::* ( <T_LEFT_CURLY> code() <T_RIGHT_CURLY> )*
