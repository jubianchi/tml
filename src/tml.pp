%skip       T_SPACE                 \s

%token      T_OP_PLUS               plus|\+
%token      T_OP_MINUS              moins|\-
%token      T_OP_MULTI              (multiplié par|\*)
%token      T_OP_DIVIDE             (divisé par|/)
%token      T_OP_EQUAL              (égal|=)

%token      T_FN                    \.[a-zA-Z_][a-zA-Z0-9_]*
%token      T_VAR                   @[a-zA-Z_][a-zA-Z0-9_]*
%token      T_NUMBER                -?[1-9][0-9]?

%token      T_OPEN_PAREN            \(
%token      T_CLOSE_PAREN           \)
%token      T_COMMA                 ,

%token      T_CHAR                  .

#tml:
    ( fn() | expr() | assign() )+

#expr:
    ( <T_NUMBER> | rvar() ) ( operator() expr() )? | str()

#assign:
    lvar() ::T_OP_EQUAL:: rval()

#fn:
    <T_FN> ::T_OPEN_PAREN:: arguments() ::T_CLOSE_PAREN::

#str:
    <T_CHAR>+

#rvar:
    <T_VAR>

#lvar:
    <T_VAR>

arguments:
    rval() ( ::T_COMMA:: arguments() )?

operator:
    ( <T_OP_PLUS> | <T_OP_MINUS> | <T_OP_MULTI> | <T_OP_DIVIDE> )

rval:
      <T_NUMBER>
    | str()
    | fn()
    | expr()
    | rvar()
