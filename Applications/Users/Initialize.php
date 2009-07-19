<?php # Users Initialize [axiixc]

if ($_GET['arg'] > 0 or $_GET['arg'] == 'account')
{
   auRequireType(auBasic, auAdmin);
}