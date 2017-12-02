<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/ 
 use App\Url;

 	/**
 	 *  this function generate de short url that can be use to redirect in our original url
 	 * @param void
 	 * @return string
 	 * 
 	 * */

	function generateShortUrl(){ 

		$exist ;
		$shortened ;
		do{ 
			$shortened = str_random(5);
			$exist = Url::whereShortened($shortened)->first();
			
		}while($exist!=null);

		return $shortened;
		
	}


Route::get('/', function () {
		
   	 return view('welcome');
});

Route::post('/',function(){ 
	
	 $urlRecu =  request('url');

 // valider l'url recu
 	Validator::make( 
 		['url'=>$urlRecu],
 		['url'=>'required|url'],
 		['required'=>'ce champ est requis','url'=>'l\'url entrée n\'est pas valide']

 	)->validate();
 	/*
 		on pouvait faire ceci 
 		$validation = Validator::make($data,$rules);

 		$validation->fails() // la methode fails verifie si la validation échouer
 		$validation->passes()// la methode fails verifie si la validation a reussi
	
		--REMARQUE : nous on a utiliser la methode $validation->validate() qui en cas d'echec redirige vers la page precedente
		--------------------------------------------------------------------------------------------------------
 	 */
 // verifier si lurl recu avait ete raccourci et la retourner
	 $url = Url::whereUrl($urlRecu)->first();
	 if($url){ 

	 	$originalUrl = $url->url;
	 	$shortened = $url->shortened;
	 	$infos = compact('originalUrl','shortened');
	 	return view('welcome')->withInfos($infos);
	 }
 // sinon creer une nouvelle url raccourci grace a celle qui est valide
	 else{ 

	 	$shortened = generateShortUrl();
	 	$newUrl = Url::create([ 
	 			'url'=>$urlRecu,
	 			'shortened'=>$shortened
	 		]);

	 	$originalUrl = $urlRecu; 
	 	
	 	// si l'enregistrement reussi
	 	
	 	if($newUrl){ 
	 			$infos = compact('originalUrl','shortened');
	 			return view('welcome')->withInfos($infos);
	 	}
	 }
});

Route::get('/{shortner}',function($short){ 

	
	
// on verifier si le shotner exist ds LA BD
	 $originalUrl = Url::whereShortened($short)->first();
// si oui on prend lurl dorigine on redirige vers cette url
// 
	
	 if($originalUrl){ 
	 	return redirect()->to($originalUrl->url);
	 }
//sinon on redige vers la page d'accueil
	else{ 
		return redirect( '/');
	}
});