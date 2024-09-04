<?php
  $ADserver = "143.2.200.12";
  $domain   = "twnhs001.itcp.sharedom.net";
  $baseDN   = "dc=twnhs001,dc=itcp,dc=sharedom,dc=net";
            
  $user     = 'administrator';
  $pass     = 'Elainerick';  
  
  /* Format should like Jack@example.com.tw */
  $ldapDN   = $user . '@' . $domain;
  
  $ldapConn = ldap_connect( $ADserver ) or die("Connect fail");
  
  /* IMPORTANT */
  ldap_set_option($ldapConn, LDAP_OPT_PROTOCOL_VERSION, 3);
  ldap_set_option($ldapConn, LDAP_OPT_REFERRALS, 0);

  if ($ldapConn) 
  { 
    $ldapbind = ldap_bind($ldapConn, $ldapDN, $pass);   
    if ($ldapbind) 
    {
      $filter = "(sAMAccountName=$user)";
      $result = @ldap_search($ldapConn, $baseDN, $filter);
      if($result == false) 
      {
        /* empty search result */
		echo "empty search result";
      }
      else
      {
        $row       = ldap_get_entries( $ldapConn, $result );   
        echo $loginName = $row[0]['displayname'][0];     // display name
        echo $loginID   = $row[0]['samaccountname'][0];  // AD account
      }    
    } 
    else 
    {         
      die("User,Pass do not match");
    } 
  }
  ldap_close($ldapConn);  
?>