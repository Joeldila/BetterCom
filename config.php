<?php
session_start();
function envv($k,$d=''){ return $_ENV[$k] ?? getenv($k) ?: $d; }
define('APP_NAME', envv('APP_NAME','BetterCom'));
define('APP_URL', rtrim(envv('APP_URL',''),'/'));
$host=envv('DB_HOST','127.0.0.1'); $port=(int)envv('DB_PORT','3306');
$db=envv('DB_NAME','bettercom'); $user=envv('DB_USER','root'); $pass=envv('DB_PASS','');
$conn=@new mysqli($host,$user,$pass,$db,$port);
if($conn->connect_errno){ http_response_code(503); die('Database connection unavailable. Check Railway MySQL environment variables and import schema.sql.');}
$conn->set_charset('utf8mb4');
function e($v){return htmlspecialchars((string)$v,ENT_QUOTES,'UTF-8');}
function me(){return $_SESSION['user']??null;}
function require_login(){if(!me()){header('Location: index.php?page=login');exit;}}
function can($roles){return me() && in_array(me()['role'],(array)$roles,true);}
function require_role($roles){require_login();if(!can($roles)){http_response_code(403);die('Access denied.');}}
function audit($action,$details=''){global $conn;if(me()){$s=$conn->prepare('INSERT INTO audit_logs(user_id,action,details) VALUES(?,?,?)');$s->bind_param('iss',$_SESSION['user']['id'],$action,$details);$s->execute();}}
function notify_user($uid,$title,$body,$link=''){global $conn;$s=$conn->prepare('INSERT INTO notifications(user_id,title,body,link) VALUES(?,?,?,?)');$s->bind_param('isss',$uid,$title,$body,$link);$s->execute();}
function csrf(){if(empty($_SESSION['csrf']))$_SESSION['csrf']=bin2hex(random_bytes(32));return $_SESSION['csrf'];}
function verify_csrf(){if($_SERVER['REQUEST_METHOD']==='POST' && !hash_equals(csrf(),$_POST['csrf']??'')){http_response_code(419);die('Invalid session token. Refresh and try again.');}}
function redirect($p){header('Location: index.php?page='.urlencode($p));exit;}
function is_staff(){return can(['admin','it_admin','hod','lecturer','class_rep','technician']);}
