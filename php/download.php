<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 18/02/2018
 * Time: 18:58
 */
include "./service.php";
$name = $_GET["name"];
$file = $_GET["path"].$name.'/';
      $zip = new ZipArchive();
      
      if(is_dir($file))
      {
        // On teste si le dossier existe, car sans ça le script risque de provoquer des erreurs.
	
        if($zip->open($name.'.zip', ZipArchive::CREATE) == TRUE)
	{
	  // Ouverture de l’archive réussie.

	  // Récupération des fichiers.
      $fichiers = scandir($file);
	  // On enlève . et .. qui représentent le dossier courant et le dossier parent.
	  unset($fichiers[0], $fichiers[1]);
	  
	  foreach($fichiers as $f)
	  {
	    // On ajoute chaque fichier à l’archive en spécifiant l’argument optionnel.
	    // Pour ne pas créer de dossier dans l’archive.
	    if(!$zip->addFile($file.$f, $f))
	    {
	      //echo 'Impossible d&#039;ajouter &quot;'.$f.'&quot;.<br/>';
	    }
	  }
	
	  // On ferme l’archive.
	  $zip->close();
	
	  // On peut ensuite, comme dans le tuto de DHKold, proposer le téléchargement.
	  header('Content-Transfer-Encoding: binary'); //Transfert en binaire (fichier).
	  header('Content-Disposition: attachment; filename="'.$name.'"'.".zip"); //Nom du fichier.
	  header('Content-Length: '.filesize($name.'.zip')); //Taille du fichier.
	  
			$test = readfile($name.'.zip');
			if($test != false){
				unlink($name.'.zip');
			}
	}
	else
	{
	  // Erreur lors de l’ouverture.
	  // On peut ajouter du code ici pour gérer les différentes erreurs.
	  header('Location: ../html/error404.html');
	}
      }
      else
      {
        // Possibilité de créer le dossier avec mkdir().
        header('Location: ../html/error404.html');
      } 




