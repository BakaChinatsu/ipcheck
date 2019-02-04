<?php

require '../vendor/autoload.php';
$loader = new Twig_Loader_Filesystem( __DIR__ . '/../views' );
$twig = new Twig_Environment( $loader, [ 'debug' => true ] );
$twig->addExtension(new Twig_Extension_Debug());

$currentver = substr( file_get_contents( __DIR__. '/../.git/refs/heads/master' ), 0, 7 );

$ts_pw = posix_getpwuid(posix_getuid());
$ts_mycnf = parse_ini_file($ts_pw['dir'] . "/replica.my.cnf");

$mysqli = new mysqli('meta.web.db.svc.eqiad.wmflabs', $ts_mycnf['user'], $ts_mycnf['password'], 'meta_p');

$query = 'select lang, family, dbname from wiki order by dbname asc;';

$res = mysqli_query( $mysqli, $query );
$opt = array();
while( $row = mysqli_fetch_assoc( $res ) ) {
	array_push( $opt, $row );
}
echo $twig->render( 'base.html.twig', [
	'splash' => '1',
	'options' => $opt
] );
?>