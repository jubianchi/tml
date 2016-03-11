%import ../src/tml.pp

#tml:
      expr()
    | assign()
    | str()

#expr:
    <T_NUMBER> ( operator() expr() | division() )?

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
