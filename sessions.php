<?php
    ini_set("session.cookie_lifetime", "0");
    ini_set("session.use_cookies", "On");
    ini_set("session.use_only_cookies", "On");
	ini_set("session.use_strict_mode", "On");
	ini_set("session.cookie_httponly", "On");
	ini_set("session.use_trans_sid", "Off");
	ini_set("session.cache_limiter", "nocache");
	ini_set("session.sid_length", "48");
	ini_set("session.sid_bits_per_character", "6");
    session_name($session_name);
    session_set_cookie_params(0, "/", $_SERVER['SERVER_NAME'], false, true);
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    if(!is_writable(session_save_path())) {
        echo("Session path (".session_save_path().") not writable by PHP.");
        die();
    }

    function checkSession()
    {
        if(isset($_SESSION))
        {
            if(
                isset($_SESSION['session_hash']) &&
                isset($_SESSION['token']) &&
                isset($_SESSION['expires']) &&
                isset($_SESSION['user']))
            {
                return true;
            }
        }
        return false;
    }

    function checkLogin($username, $session_name)
    {
        include("db.php");

        if(isset($_SESSION))
        {
            if(
                isset($_SESSION['session_hash']) &&
                isset($_SESSION['token']) &&
                isset($_SESSION['user']))
            {
                $uip = $_SERVER['REMOTE_ADDR'];
                $ua = $_SERVER['HTTP_USER_AGENT'];



                        $user = $_SESSION['user'];
                        $reg = preg_match("/^[a-z0-9A-Z]+$/", $user);
                        if($reg !== 1)
                        {
                            logout();
                            return false;
                        }

                        $session_db_id = $_SESSION['sess_db_id'];
                        if(!is_numeric($session_db_id))
                        {
                            logout();
                            return false;
                        }

                        //$stmt = $db->prepare("SELECT id, session_hash, token, ip, expires, user FROM sessions WHERE user=:user AND id=:db_id");
                        $stmt = $db->prepare("SELECT id, session_hash, token, user FROM sessions WHERE user=:user ORDER BY id DESC LIMIT 1");
                        $stmt->bindParam(":user", $user);
                        //$stmt->bindParam(":db_id", $session_db_id);

                        try
                        {
                            $stmt->execute();
                            $userData = $stmt->fetch();

                            if($userData !== false)
                            {
                                if(isset($userData['token']) && isset($userData['session_hash']) && isset($userData['id']))
                                {
                                    if(!empty($userData['token']) && !empty($userData['session_hash']))
                                    {

                                                if($userData['session_hash'] != null && $userData['session_hash'] != "0")
                                                {
                                                    if($userData['token'] != null && $userData['token'] != "0")
                                                    {
                                                        if($userData['token'] === $_SESSION['token'])
                                                        {
                                                            $session_hash = $_SESSION['user']."yE6uuPNza52VwD43J4ABXrSq";
                                                            if(password_verify($session_hash, $userData['session_hash']) && password_verify($session_hash, $_SESSION['session_hash']))
                                                            {


                                                                $now = time();

                                                                $stmt = $db->prepare("UPDATE users SET last_click=:last_click WHERE user=:user");
                                                                $stmt->bindParam(":last_click", $now);
                                                                $stmt->bindParam(":user", $username);
                                                                $stmt->execute();

                                                                $stmt = $db->prepare("UPDATE sessions SET expires=:expires WHERE id=:id");
                                                                $stmt->bindParam(":expires", $expires);
                                                                $stmt->bindParam(":id", $userData['id']);
                                                                $stmt->execute();

                                                                return true;
                                                            } // session hash failed
                                                            else
                                                            {
                                                                $failCode = "Session hash check failed";
                                                            }
                                                        } // token different
                                                        else
                                                        {
                                                            $failCode = "Session token check failed";
                                                        }
                                                    }
                                                    else
                                                    {
                                                        $failCode = "Failed to retrieve session token";
                                                    }
                                                } // session hash
                                                else
                                                {
                                                    $failCode = "Failed to retrieve session hash";
                                                }


                                    } // user data vars empty
                                    else
                                    {
                                        $failCode = "User data empty";
                                    }
                                } // user data vars not set
                                else
                                {
                                    $failCode = "User data not set";
                                }
                            } // user data === false
                            else
                            {
                                $failCode = "Session with user [$username] not found";
                            }
                        }
                        catch(PDOException $e)
                        {
                            logout();
							echo($e->getMessage());
							$failCode = "DB error: ".$e->getMessage();
                            die();
                        }


            } // session vars
            else
            {
                $failCode = "Session vars not set";
            }
        } // session array

        logout();

        if(isset($expired))
        {
            session_name($session_name);
            session_set_cookie_params(0, "/", $_SERVER['SERVER_NAME'], false, true);
            session_start();
            $_SESSION['message'] = "Your session expired.";
        }

        // todo: log message with failCode
        logMessage($db, "Fail msg: ".$failCode, "Login Debug");
        return false;
    }

    function logout()
    {
        session_name("STK_SESSION");
        session_set_cookie_params(0, "/", $_SERVER['SERVER_NAME'], false, true);
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION = array();
        session_destroy();
    }
?>
