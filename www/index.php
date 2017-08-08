<?php

if(!session_id())
session_start();

require(sprintf(
	'%s/conf/start.php',
	dirname(dirname(__FILE__))
));

$Router = Nether\Stash::Set('Router',(new Nether\Avenue\Router))
->AddRoute('{@}//index','Routes\Home::Index')
->AddRoute('{@}//about','Routes\Home::About')
->AddRoute('{@}//auth/fb-init','Routes\Auth::FacebookInit')
->AddRoute('{@}//auth/fb-confirm','Routes\Auth::FacebookConfirm')
->AddRoute('{@}//auth/logout','Routes\Auth::Logout')
->AddRoute('{@}//{@}','Routes\Home::NotFound')
->Run();
