<?PHP
//post data
if(isset($_POST['submit'])) {
    $username = $_POST['user'];
    $user5 = substr($username, 0, 5);
    $password = $_POST['pass'];
    //check if user is admin
    if ($user5 == "admin") {
        $host = "ldap://**.***.*.**";
        $user = "$username@*******.lan";
        //try bind
        $ldapconn = ldap_connect($host) 
            or die("Could not connect to LDAP server.");
        //set options
        ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($ldapconn, LDAP_OPT_REFERRALS, 0);
        //redirect and set session based on bind result
        if ($ldapconn) {
            $ldapbind = ldap_bind($ldapconn, $user, $password);
            if ($ldapbind) {
                session_start();
                $_SESSION['logged'] = TRUE;
                header('Location: ../home');
            } 
            else {
                session_start();
                $_SESSION['logged'] = FALSE;
                header('Location: /');
            }
        }
        @ldap_close($ldapconn);
    }
    else {
        session_start();
        $_SESSION['logged'] = FALSE;
        header('Location: /');
    }
    //log visitors and time
    $file = 'log.txt';
    $current = file_get_contents($file);
    $stamp = date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']);
    $current .= "$stamp: $username" . PHP_EOL;
    file_put_contents($file, $current);
}
