<?php 
return [
	''=>[
		'controller'=>'main',
		'action'=> 'index',
	],
	'sendfeedback'=>[
		'controller'=>'main',
		'action'=> 'sendfeedback',
	],
	'article'=>[
		'controller'=>'main',
		'action'=> 'article',
	],
	'about_me'=>[
		'controller'=>'main',
		'action'=> 'aboutme',
	],
	'closest_hospital'=>[
		'controller'=>'main',
		'action'=> 'closest_hospital',
	],
	'donation'=>[
		'controller'=>'main',
		'action'=> 'donation',
	],
	'treatment_plan'=>[
		'controller'=>'main',
		'action'=> 'treatment_plan',
	],
	'agreement'=>[
		'controller'=>'main',
		'action'=> 'agreement',
	],
	'complaints'=>[
		'controller'=>'main',
		'action'=> 'complaints',
	],
	'donators'=>[
		'controller'=>'main',
		'action'=> 'donators',
	],
	'verification'=>[
		'controller'=>'main',
		'action'=> 'verification',
	],
	//Sign's division
	'sign_in'=>[
		'controller'=>'sign',
		'action'=> 'sign_in',
	],
	'sign_up'=>[
		'controller'=>'sign',
		'action'=> 'sign_up',
	],
	'signup'=>[
		'controller'=>'sign',
		'action'=> 'signup',
	],
	'authenticate'=>[
		'controller'=>'sign',
		'action'=> 'authenticate',
	],
	//admin's division
	'admin'=>[
		'controller'=>'admin',
		'action'=> 'home',
	],
	'admin/posts'=>[
		'controller'=>'admin',
		'action'=> 'posts',
	],
	'admin/editp'=>[
		'controller'=>'admin',
		'action'=> 'edit_post',
	],
	'admin/updatep'=>[
		'controller'=>'admin',
		'action'=> 'update_post',
	],
	'admin/deletep'=>[
		'controller'=>'admin',
		'action'=> 'delete_post',
	],
	'admin/publishp'=>[
		'controller'=>'admin',
		'action'=> 'publish_post',
	],
	'admin/createp'=>[
		'controller'=>'admin',
		'action'=> 'create_post',
	],
	'admin/hospitals'=>[
		'controller'=>'admin',
		'action'=> 'hospitals',
	],
	'admin/viewh'=>[
		'controller'=>'admin',
		'action'=> 'view_hospital',
	],
	'admin/edith'=>[
		'controller'=>'admin',
		'action'=> 'edit_hospital',
	],
	'admin/updateh'=>[
		'controller'=>'admin',
		'action'=> 'update_hospital',
	],
	'admin/approveh'=>[
		'controller'=>'admin',
		'action'=> 'approve_hospital',
	],
	'admin/deleteh'=>[
		'controller'=>'admin',
		'action'=> 'delete_hospital',
	],
	'admin/deletepatient'=>[
		'controller'=>'admin',
		'action'=> 'delete_patient',
	],
	'admin/users'=>[
		'controller'=>'admin',
		'action'=> 'users',
	],
	'admin/blocku'=>[
		'controller'=>'admin',
		'action'=> 'block_user',
	],
	'admin/deleteu'=>[
		'controller'=>'admin',
		'action'=> 'delete_user',
	],
	'admin/createu'=>[
		'controller'=>'admin',
		'action'=> 'create_user',
	],
	'admin/search'=>[
		'controller'=>'admin',
		'action'=> 'search',
	],
	'admin/inbox'=>[
		'controller'=>'admin',
		'action'=> 'inbox',
	],
	'admin/logout'=>[
		'controller'=>'admin',
		'action'=> 'logout',
	],
	//hospital's division
	'hospital'=>[
		'controller'=>'hospital',
		'action'=> 'home',
	],
	'hospital/chat'=>[
		'controller'=>'hospital',
		'action'=> 'chat',
	],
	'hospital/getm'=>[
		'controller'=>'hospital',
		'action'=> 'get_messages',
	],
	'hospital/fillph'=>[
		'controller'=>'hospital',
		'action'=> 'fill_pharamacy',
	],
	'hospital/addw'=>[
		'controller'=>'hospital',
		'action'=> 'add_worker',
	],
	'hospital/patients'=>[
		'controller'=>'hospital',
		'action'=> 'patients',
	],
	'hospital/confirmp'=>[
		'controller'=>'hospital',
		'action'=> 'confirm_patient',
	],
	'hospital/viewp'=>[
		'controller'=>'hospital',
		'action'=> 'view_patient',
	],
	'hospital/editp'=>[
		'controller'=>'hospital',
		'action'=> 'edit_patient',
	],
	'hospital/updatep'=>[
		'controller'=>'hospital',
		'action'=> 'update_patient',
	],
	'hospital/savetreatment'=>[
		'controller'=>'hospital',
		'action'=> 'save_treatment',
	],
	'hospital/profile'=>[
		'controller'=>'hospital',
		'action'=> 'my_profile',
	],
	'hospital/editprofile'=>[
		'controller'=>'hospital',
		'action'=> 'edit_profile',
	],
	'hospital/pharmacy'=>[
		'controller'=>'hospital',
		'action'=> 'pharmacy',
	],
	'hospital/searchmedicine'=>[
		'controller'=>'hospital',
		'action'=> 'search_medicine',
	],
	'hospital/deleteu'=>[
		'controller'=>'hospital',
		'action'=> 'delete_unit',
	],
	'hospital/setstatus'=>[
		'controller'=>'hospital',
		'action'=> 'set_status',
	],
	'hospital/units'=>[
		'controller'=>'hospital',
		'action'=> 'units',
	],
	'hospital/viewd'=>[
		'controller'=>'hospital',
		'action'=> 'view_doctor',
	],
	'hospital/doctors'=>[
		'controller'=>'hospital',
		'action'=> 'doctors',
	],
	'hospital/logout'=>[
		'controller'=>'hospital',
		'action'=> 'logout',
	],
	//doctor's division
	'doctor'=>[
		'controller'=>'doctor',
		'action'=> 'home',
	],
	'doctor/fillph'=>[
		'controller'=>'doctor',
		'action'=> 'fill_pharamacy',
	],
	'doctor/addp'=>[
		'controller'=>'doctor',
		'action'=> 'add_patient',
	],
	'doctor/addpatient'=>[
		'controller'=>'doctor',
		'action'=> 'addpatient',
	],
	'doctor/chat'=>[
		'controller'=>'doctor',
		'action'=> 'chat',
	],
	'doctor/profile'=>[
		'controller'=>'doctor',
		'action'=> 'profile',
	],
	'doctor/editprofile'=>[
		'controller'=>'doctor',
		'action'=> 'edit_profile',
	],
	'doctor/getm'=>[
		'controller'=>'doctor',
		'action'=> 'get_messages',
	],
	'doctor/diagnostic'=>[
		'controller'=>'doctor',
		'action'=> 'diagnostic',
	],
	'doctor/treatment'=>[
		'controller'=>'doctor',
		'action'=> 'treatment',
	],
	'doctor/searchmedicine'=>[
		'controller'=>'doctor',
		'action'=> 'search_medicine',
	],
	'doctor/updatet'=>[
		'controller'=>'doctor',
		'action'=> 'update_treatment',
	],
	'doctor/updated'=>[
		'controller'=>'doctor',
		'action'=> 'update_diagnostic',
	],
	'doctor/patients'=>[
		'controller'=>'doctor',
		'action'=> 'patients',
	],
	'doctor/pharmacy'=>[
		'controller'=>'doctor',
		'action'=> 'pharmacy',
	],
	'doctor/units'=>[
		'controller'=>'doctor',
		'action'=> 'units',
	],
	'doctor/viewp'=>[
		'controller'=>'doctor',
		'action'=> 'view_patient',
	],
	'doctor/logout'=>[
		'controller'=>'doctor',
		'action'=> 'logout',
	],
];
 ?>