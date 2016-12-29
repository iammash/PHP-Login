<?php
function installDb($i, $dbhost, $dbname, $dbuser, $dbpw, $tblprefix, $superadmin, $saemail, $said, $sapw, $settingsArr = '')
{
    $status = '';
    $failure = 0;
    try {
        switch ($i) {
            case 0:
                require "confgen.php";
                //$status = "Generating config.php file";
                //$i++;
                break 1;
            case 1:
                $conn = new PDO("mysql:host={$dbhost}", $dbuser, $dbpw);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $status = "Creating database <span class='dbtable'>{$dbname}</span>";
                $sqlcreate = "CREATE DATABASE {$dbname};";
                $c = $conn->exec($sqlcreate);
                if ($c) {
                    unset($c);
                    break 1;
                    sleep(0.5);
                } else {
                    throw new Exception("Failed to create database");
                    $failure = 1;
                    break 1;
                }
            case 2:
                $conn = new PDO("mysql:host={$dbhost};dbname={$dbname}", $dbuser, $dbpw);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $status = "Creating <span class='dbtable'>" . $tblprefix . "members</span> table";
                $sqlmembers = "CREATE TABLE {$dbname}.{$tblprefix}members (`id` char(23) NOT NULL, `username` varchar(65) NOT NULL DEFAULT '', `password` varchar(255) NOT NULL DEFAULT '', `email` varchar(65) NOT NULL, `verified` tinyint(1) NOT NULL DEFAULT '0', `admin` tinyint(1) NOT NULL DEFAULT '0', `mod_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, PRIMARY KEY (`id`), UNIQUE KEY `username_UNIQUE` (`username`), UNIQUE KEY `id_UNIQUE` (`id`), UNIQUE KEY `email_UNIQUE` (`email`) ) ENGINE=InnoDB DEFAULT CHARSET=latin1; GO;";
                $m = $conn->exec($sqlmembers);
                if ($m) {
                    unset($m);
                    break 1;
                    sleep(0.5);
                } else {
                    throw new Exception("Failed to create <span class='dbtable'>" . $tblprefix . "members</span> table");
                    $failure = 1;
                    break 1;
                }
            case 3:
                $conn = new PDO("mysql:host={$dbhost};dbname={$dbname}", $dbuser, $dbpw);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $status = "Creating <span class='dbtable'>" . $tblprefix . "admins</span> table";
                $sqladmins = "CREATE TABLE {$dbname}.{$tblprefix}admins ( `adminid` char(23) NOT NULL DEFAULT 'uuid_short();', `userid` char(23) NOT NULL, `active` bit(1) NOT NULL DEFAULT b'0', `superadmin` bit(1) NOT NULL DEFAULT b'0', PRIMARY KEY (`adminid`,`userid`), UNIQUE KEY `adminid_UNIQUE` (`adminid`), UNIQUE KEY `userid_UNIQUE` (`userid`), CONSTRAINT `fk_userid_admins` FOREIGN KEY (`userid`) REFERENCES {$dbname}.`{$tblprefix}members` (`id`) ON UPDATE CASCADE ) ENGINE=InnoDB DEFAULT CHARSET=latin1; GO;";
                $a = $conn->exec($sqladmins);
                if ($a) {
                    unset($a);
                    break 1;
                    sleep(0.5);
                } else {
                    throw new Exception("Failed to create <span class='dbtable'>" . $tblprefix . "admins</span> table");
                    $failure = 1;
                    break 1;
                }
            case 4:
                $conn = new PDO("mysql:host={$dbhost};dbname={$dbname}", $dbuser, $dbpw);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $status = "Creating <span class='dbtable'>" . $tblprefix . "deletedMembers</span> table";
                $sqldeletedMembers = "CREATE TABLE {$dbname}.{$tblprefix}deletedMembers ( `id` char(23) NOT NULL, `username` varchar(65) NOT NULL DEFAULT '', `password` varchar(65) NOT NULL DEFAULT '', `email` varchar(65) NOT NULL, `verified` tinyint(1) NOT NULL DEFAULT '0', `admin` tinyint(1) NOT NULL DEFAULT '0', `mod_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, PRIMARY KEY (`id`), UNIQUE KEY `id_UNIQUE` (`id`) ) ENGINE=InnoDB DEFAULT CHARSET=latin1; GO;";
                $dm = $conn->exec($sqldeletedMembers);
                if ($dm) {
                    unset($dm);
                    break 1;
                    sleep(0.5);
                } else {
                    throw new Exception("Failed to create <span class='dbtable'>" . $tblprefix . "deletedMembers</span> table");
                    $failure = 1;
                    break 1;
                }
            case 5:
                $conn = new PDO("mysql:host={$dbhost};dbname={$dbname}", $dbuser, $dbpw);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $status = "Creating <span class='dbtable'>" . $tblprefix . "loginAttempts</span> table";
                $sqlloginAttempts = "CREATE TABLE {$dbname}.{$tblprefix}loginAttempts ( `ID` int(11) NOT NULL AUTO_INCREMENT, `Username` varchar(65) DEFAULT NULL, `IP` varchar(20) NOT NULL, `Attempts` int(11) NOT NULL, `LastLogin` datetime NOT NULL, PRIMARY KEY (`ID`) ) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8; GO;";
                $la = $conn->exec($sqlloginAttempts);
                if ($la) {
                    unset($la);
                    break 1;
                    sleep(0.5);
                } else {
                    throw new Exception("Failed to create <span class='dbtable'>" . $tblprefix . "loginAttempts</span> table");
                    $failure = 1;
                    break 1;
                }
            case 6:
                $conn = new PDO("mysql:host={$dbhost};dbname={$dbname}", $dbuser, $dbpw);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $status = "Creating <span class='dbtable'>" . $tblprefix . "memberInfo</span> table";
                $sqlmemberInfo = "CREATE TABLE {$dbname}.{$tblprefix}memberInfo ( `userid` char(23) NOT NULL, `firstname` varchar(45) NOT NULL, `lastname` varchar(55) DEFAULT NULL, `phone` varchar(20) DEFAULT NULL, `address1` varchar(45) DEFAULT NULL, `address2` varchar(45) DEFAULT NULL, `city` varchar(45) DEFAULT NULL, `state` varchar(30) DEFAULT NULL, `country` varchar(45) DEFAULT NULL, `bio` varchar(60000) DEFAULT NULL, `userimage` varchar(255) DEFAULT NULL, UNIQUE KEY `userid_UNIQUE` (`userid`), KEY `fk_userid_idx` (`userid`), CONSTRAINT `fk_userid` FOREIGN KEY (`userid`) REFERENCES {$dbname}.`{$tblprefix}members` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION ) ENGINE=InnoDB DEFAULT CHARSET=latin1; GO;";
                $mi = $conn->exec($sqlmemberInfo);
                if ($mi) {
                    unset($mi);
                    break 1;
                    sleep(0.5);
                } else {
                    throw new Exception("Failed to create <span class='dbtable'>" . $tblprefix . "memberInfo</span> table");
                    $failure = 1;
                    break 1;
                }
            case 7:
                $conn = new PDO("mysql:host={$dbhost};dbname={$dbname}", $dbuser, $dbpw);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $status = "Creating <span class='dbtable'>" . $tblprefix . "cookies</span> table";
                $sqlcookies = "CREATE TABLE {$dbname}.{$tblprefix}cookies ( `cookieid` char(23) NOT NULL, `userid` char(23) NOT NULL, `tokenid` char(25) NOT NULL, `expired` tinyint(1) NOT NULL DEFAULT '0', `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, PRIMARY KEY (`userid`), CONSTRAINT `userid` FOREIGN KEY (`userid`) REFERENCES {$dbname}.`{$tblprefix}members` (`id`) ON DELETE CASCADE ON UPDATE CASCADE ) ENGINE=InnoDB DEFAULT CHARSET=latin1; GO;";
                $cook = $conn->exec($sqlcookies);
                if ($cook) {
                    unset($cook);
                    break 1;
                    sleep(0.5);
                } else {
                    throw new Exception("Failed to create <span class='dbtable'>" . $tblprefix . "cookies</span> table");
                    $failure = 1;
                    break 1;
                }
            case 8:
                $conn = new PDO("mysql:host={$dbhost};dbname={$dbname}", $dbuser, $dbpw);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $status = "Creating <span class='dbtable'>" . $tblprefix . "tokens</span> table";
                $sqltokens = "CREATE TABLE {$dbname}.{$tblprefix}tokens ( `tokenid` char(25) NOT NULL, `userid` char(23) NOT NULL, `expired` tinyint(1) NOT NULL DEFAULT '0', `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, PRIMARY KEY (`tokenid`), UNIQUE KEY `tokenid_UNIQUE` (`tokenid`), UNIQUE KEY `userid_UNIQUE` (`userid`), CONSTRAINT `userid_t` FOREIGN KEY (`userid`) REFERENCES {$dbname}.`{$tblprefix}members` (`id`) ON DELETE CASCADE ON UPDATE CASCADE ) ENGINE=InnoDB DEFAULT CHARSET=latin1; GO;";
                $tkn = $conn->exec($sqltokens);
                if ($tkn) {
                    unset($tkn);
                    break 1;
                    sleep(0.5);
                } else {
                    throw new Exception("Failed to create <span class='dbtable'>" . $tblprefix . "tokens</span> table");
                    $failure = 1;
                    break 1;
                }
            case 9:
                $conn = new PDO("mysql:host={$dbhost};dbname={$dbname}", $dbuser, $dbpw);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $status = "Creating <span class='dbtable'>" . $tblprefix . "appConfig</span> table";
                $sqlconfig = "CREATE TABLE {$dbname}.{$tblprefix}appConfig (`setting` char(26) NOT NULL, `value` varchar(12000) NOT NULL, `sortorder` int(5), `category` varchar(25) NOT NULL, `type` varchar(15) NOT NULL, `description` varchar(140), `required` tinyint(1) NOT NULL DEFAULT '0', PRIMARY KEY (`setting`), UNIQUE KEY `setting_UNIQUE` (`setting`) ) ENGINE=InnoDB DEFAULT CHARSET=latin1; GO;";
                $cnf = $conn->exec($sqlconfig);
                if ($cnf) {
                    unset($cnf);
                    break 1;
                    sleep(0.5);
                } else {
                    throw new Exception("Failed to create <span class='dbtable'>" . $tblprefix . "appConfig</span> table");
                    $failure = 1;
                    break 1;
                }
            case 10:
                $conn = new PDO("mysql:host={$dbhost};dbname={$dbname}", $dbuser, $dbpw);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $status = "Creating triggers";
                $sqldeletedMembersTrigger = "CREATE TRIGGER {$dbname}.move_to_deletedMembers AFTER DELETE ON {$dbname}.{$tblprefix}members FOR EACH ROW BEGIN  DELETE FROM {$dbname}.{$tblprefix}deletedMembers WHERE {$dbname}.{$tblprefix}deletedMembers.id = OLD.id; UPDATE {$dbname}.{$tblprefix}admins SET active = '0' where {$dbname}.{$tblprefix}admins.userid = OLD.id;  INSERT INTO {$dbname}.{$tblprefix}deletedMembers ( id, username, password, email, verified, admin) VALUES ( OLD.id, OLD.username, OLD.password, OLD.email, OLD.verified, OLD.admin ); END; GO;";
                $dmt = $conn->exec($sqldeletedMembersTrigger);
                if ($dmt) {
                    unset($dmt);
                    break 1;
                    sleep(0.5);
                } else {
                    throw new Exception("Failed to create <span class='dbtable'>" . $tblprefix . "move_to_deletedMembers</span> trigger");
                    $failure = 1;
                    break 1;
                }
            case 11:
                $conn = new PDO("mysql:host={$dbhost};dbname={$dbname}", $dbuser, $dbpw);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $status = "Creating triggers";
                $sqladdAdminTrigger = "CREATE TRIGGER {$dbname}.add_admin AFTER INSERT ON {$dbname}.{$tblprefix}members FOR EACH ROW BEGIN IF (NEW.admin = 1) THEN  INSERT INTO {$dbname}.{$tblprefix}admins (adminid, userid, active, superadmin ) VALUES (uuid_short(), NEW.id, 1, 0 ); END IF; END; GO;";
                $aat = $conn->exec($sqladdAdminTrigger);

                if ($aat) {
                    unset($aat);
                    break 1;
                    sleep(0.5);
                } else {
                    throw new Exception("Failed to create <span class='dbtable'>add_admin</span> trigger");
                    $failure = 1;
                    break 1;
                }
            case 12:
                $conn = new PDO("mysql:host={$dbhost};dbname={$dbname}", $dbuser, $dbpw);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $status = "Creating triggers";
                $sqladdAdminBeforeUpdateTrigger = "CREATE TRIGGER  {$dbname}.add_admin_beforeUpdate BEFORE UPDATE ON {$dbname}.{$tblprefix}members FOR EACH ROW BEGIN set @s = (SELECT superadmin from {$dbname}.{$tblprefix}admins where userid = NEW.id); set @a = (SELECT adminid from {$dbname}.{$tblprefix}admins where userid = NEW.id); IF (NEW.admin = 1 && isnull(@a)) THEN INSERT INTO {$dbname}.{$tblprefix}admins ( adminid, userid, active, superadmin ) VALUES ( uuid_short(), NEW.id, 1, 0 ); ELSEIF (NEW.admin = 0) THEN IF (@s = 0) THEN DELETE FROM {$dbname}.{$tblprefix}admins WHERE userid = NEW.id and superadmin = 0; ELSEIF (@s = 1) THEN SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT='Cannot delete superadmin'; END IF; END IF; END; GO;";
                $aaaut = $conn->exec($sqladdAdminBeforeUpdateTrigger);
                if ($aaaut) {
                    unset($aaaut);
                    break 1;
                    sleep(0.5);
                } else {
                    throw new Exception("Failed to create <span class='dbtable'>add_admin_beforeUpdate</span> trigger");
                    $failure = 1;
                    break 1;
                }

            case 13:
                $conn = new PDO("mysql:host={$dbhost};dbname={$dbname}", $dbuser, $dbpw);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $status = "Creating triggers";
                $sqlStopDeleteTrigger = "CREATE TRIGGER  {$dbname}.stop_delete_required BEFORE DELETE ON {$dbname}.{$tblprefix}appConfig FOR EACH ROW BEGIN IF OLD.required = 1 THEN SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Cannot delete required settings'; END IF; END; GO;";
                $sdt = $conn->exec($sqlStopDeleteTrigger);
                if ($sdt) {
                    unset($sdt);
                    break 1;
                    sleep(0.5);
                } else {
                    throw new Exception("Failed to create <span class='dbtable'>stop_delete_required</span> trigger");
                    $failure = 1;
                    break 1;
                }
            case 14:
                $conn = new PDO("mysql:host={$dbhost};dbname={$dbname}", $dbuser, $dbpw);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $status = "Creating <span class='dbtable'>cleanupOldDeleted</span> event";
                $sqlcleanupOldDeletedEvent = "SET GLOBAL event_scheduler = ON;  CREATE EVENT IF NOT EXISTS {$dbname}.cleanupOldDeleted ON SCHEDULE EVERY 1 DAY COMMENT 'Removes deleted records older than 30 days.' DO BEGIN DELETE FROM {$dbname}.{$tblprefix}deletedMembers WHERE mod_timestamp < DATE_SUB(NOW(), INTERVAL 30 DAY); END; GO;";
                $code = $conn->exec($sqlcleanupOldDeletedEvent);
                if ($code) {
                    unset($code);
                    break 1;
                    sleep(0.5);
                } else {
                    throw new Exception("Failed to create <span class='dbtable'>cleanupOldDeleted<span> event");
                    $failure = 1;
                    break 1;
                }
            case 15:
                $conn = new PDO("mysql:host={$dbhost};dbname={$dbname}", $dbuser, $dbpw);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $status = "Creating superadmin user";
                $sqlAddSuperAdmin = $conn->prepare("INSERT INTO {$dbname}.{$tblprefix}members (id, username, password, email, verified, admin) values(:said, :superadmin, :sapw, :saemail, 1, 1)");
                $sqlAddSuperAdmin->bindParam(':said', $said);
                $sqlAddSuperAdmin->bindParam(':superadmin', $superadmin);
                $sqlAddSuperAdmin->bindParam(':sapw', $sapw);
                $sqlAddSuperAdmin->bindParam(':saemail', $saemail);
                $asa = $sqlAddSuperAdmin->execute();
                if ($asa) {
                    unset($asa);
                    break 1;
                    sleep(0.5);
                } else {
                    throw new Exception("Failed to create superadmin");
                    $failure = 1;
                    break 1;
                }
            case 16:
                $conn = new PDO("mysql:host={$dbhost};dbname={$dbname}", $dbuser, $dbpw);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $status = "Creating superadmin user";
                $sqlEscalateSuperAdmin = $conn->prepare("UPDATE {$dbname}.{$tblprefix}admins set superadmin = 1 where userid = :said");
                $sqlEscalateSuperAdmin->bindParam(':said', $said);
                $esa = $sqlEscalateSuperAdmin->execute();
                if ($esa) {
                    unset($esa);
                    break 1;
                    sleep(0.5);
                } else {
                    throw new Exception("Failed to create superadmin");
                    $failure = 1;
                    break 1;
                }

            case 17:
                //INSERT APP SETTINGS
                $conn = new PDO("mysql:host={$dbhost};dbname={$dbname}", $dbuser, $dbpw);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $status = "Creating application configuration settings";

                $status = "Creating application settings";
                $values = "";
                foreach ($settingsArr as $key=>$value){
                    $values = $values . "('{$key}', '{$value}', 1),";
                }
                $valinsert = rtrim($values, ',');

                $sqlappsettings = "INSERT INTO {$dbname}.{$tblprefix}appConfig (`sortorder`,`setting`,`category`,`value`,`type`,`description`,`required`) VALUES (27,'active_email','Messages','Your new account is now active! Click this link to log in!','text','Email message when account is verified',1), (26,'active_msg','Messages','Your account has been verified!','text','Display message when account is verified',1), (21,'admin_verify','Security','false','boolean','Require admin verification',1), (6,'avatar_dir','Website','/user/avatars','text','Directory where user avatars should be stored inside of base site directory. Do not include base_dir path.',1), (2,'base_dir','Website','".$settingsArr['base_dir']."','hidden','Base directory of website in filesystem. \"C:\\...\" in windows, \"/...\" in unix systems',1), (3,'base_url','Website','".$settingsArr['base_url']."','url','Base URL of website. Example: \"http://sitename.com\"',1), (19,'cookie_expire_seconds','Security','2592000','number','Cookie expiration (in seconds)',1), (13,'from_email','Mailer','','email','From email address. Should typically be the same as \"mail_user\" email.',1), (14,'from_name','Mailer','Test Website','text','Name that shows up in \"from\" field of emails',1), (4,'htmlhead','Website','<!DOCTYPE html><html lang=\'en\'><head><meta charset=\'utf-8\'><meta name=\'viewport\' content-width=\'device-width\', initial-scale=\'1\', shrink-to-fit=\'no\'><link rel=\'shortcut icon\' href=\'http://1x1px.me/NFFFFFF-1.png\'>','textarea','Main HTML header of website (without login-specific includes and script tags). Do not close <html> tag! Will break application functionality',1), (20,'jwt_secret','Security','php-login','text','Secret for JWT for tokens (Can be anything)',1), (18,'login_timeout','Security','300','number','Cooloff time for too many failed logins (in seconds)',1), (12,'mail_port','Mailer','587','number','Mail port. Common settings are 465 for ssl, 587 for tls, 25 for other',1), (10,'mail_pw','Mailer','','password','Email password to authenticate mailer',1), (11,'mail_security','Mailer','tls','text','Mail security type. Possible values are \"ssl\", \"tls\" or leave blank',1), (8,'mail_server','Mailer','smtp.email.com','text','Mail server address. Example: \"smtp.email.com\"',1), (7,'mail_server_type','Mailer','smtp','text','Type of email server. SMTP is most typical. Other server types untested.',1), (9,'mail_user','Mailer','','email','Email user',1), (5,'mainlogo','Website','','url','URL of main site logo. Example \"http://sitename.com/logo.jpg\"',1), (17,'max_attempts','Security','5','number','Maximum login attempts',1), (16,'password_min_length','Security','6','number','Minimum password length if \"password_policy_enforce\" is set to true',1), (15,'password_policy_enforce','Security','true','boolean','Require a mixture of upper and lowercase letters and minimum password length (set by \"password_min_length\")',1), (28,'reset_email','Messages','Click the link below to reset your password','text','Email message when user wants to reset their password',0), (23,'signup_requires_admin','Messages','Thank you for signing up! Before you can login, your account needs to be activated by an administrator.','text','Message displayed when user signs up, but requires admin approval',1), (22,'signup_thanks','Messages','Thank you for signing up! You will receive an email shortly confirming the verification of your account.','text','Message displayed wehn user signs up and can verify themselves via email',1), (1,'site_name','Website','".$settingsArr['site_name']."','text','Website name',1), (24,'verify_email_admin','Messages','Thank you for signing up! Your account will be reviewed by an admin shortly','text','Email message when account requires admin verification',1), (25,'verify_email_noadmin','Messages','Click this link to verify your new account!','text','Email message when user can verify themselves',1);";

                $dm = $conn->exec($sqlappsettings);
                if ($dm) {
                    unset($dm);
                    break 1;
                    sleep(0.5);
                } else {
                    throw new Exception("Failed to create application settings");
                    $failure = 1;
                    break 1;
                }

            default:
                $i++;
                break 1;
        }
    } catch (Exception $e) {
        $status = "An error occurred: " . $e->getMessage();
        $failure = 1;
    }
    $returnArray = array("status" => $status, "failure" => $failure);
    return $returnArray;
}
