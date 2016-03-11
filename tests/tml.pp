%skip              T_SPACE                 \s

%token             T_OP_PLUS               plus
%token             T_OP_MINUS              moins
%token             T_OP_MULTI              multiplié par
%token             T_OP_DIVIDE             divisé par
%token             T_OP_EQUAL              égal

%token             T_FN                    \.[a-zA-Z_][a-zA-Z0-9_]*
%token             T_VAR                   @[a-zA-Z_][a-zA-Z0-9_]*
%token             T_NUMBER                \-?[1-9][0-9]*

%token             T_QUOTE                 "                        -> quoted
%token      quoted:T_QUOTE                 "                        -> default
%token             T_OPEN_PAREN            \(
%token             T_CLOSE_PAREN           \)
%token             T_COMMA                 ,

%token      quoted:T_CHAR                  \\"|[^"]

#tml:
      expr()
    | assign()
    | str()

#expr:
    <T_NUMBER> ( operator() expr() | division() )?

#assign:
    lvar() ::T_OP_EQUAL:: rval()

#str:
    ::T_QUOTE:: <T_CHAR>* ::T_QUOTE::

#lvar:
    <T_VAR>

// Avoids division by zero
division:
    <T_NUMBER[0]> <T_OP_DIVIDE> <T_NUMBER[0]>

operator:
      <T_OP_PLUS>
    | <T_OP_MINUS>
    | <T_OP_MULTI>

rval:
      <T_NUMBER>
    | str()
    | expr()
