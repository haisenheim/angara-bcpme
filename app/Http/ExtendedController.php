<?php

namespace App\Http\Controllers;

use App\Models\Annee;

//use Illuminate\Http\Request;

class ExtendedController extends Controller
{
    //

    

	protected function authExtensions(){
		return array('jpg','png','jpeg','gif','GIF','PNG','JPG','JPEG','pdf','PDF','Pdf','doc','docx','DOC','DOCX');
	}

    protected function docExtensions(){
		return array('pdf','PDF','Pdf','doc','docx','DOC','DOCX');
	}

	protected function entityImgCreate($file,$entity,$name_without_extension){

		$ext = $file->getClientOriginalExtension();
		$arr_ext = $this->authExtensions();
        $dir = 'img/central/'.$entity;


		if (!file_exists(public_path($dir))) {
			mkdir(public_path($dir),0777,true);
		}
		if(in_array($ext,$arr_ext)) {
			$name_with_extension = $name_without_extension . '.' . $ext;

			if (file_exists(public_path($dir) . '/' . $name_with_extension)) {
				unlink(public_path($dir) . '/' . $name_with_extension);
			}
			$imageUri = $dir.'/'.$name_with_extension;
			$file->move(public_path($dir), $name_with_extension);
			return $imageUri;
		}

		return null;
	}

	protected function entityDocumentCreate($file,$entity,$name_without_extension){

		$ext = $file->getClientOriginalExtension();
		$arr_ext = $this->docExtensions();

		if (!file_exists(public_path('files'))) {
			mkdir(public_path('files'));
		}

		if (!file_exists(public_path('files') . '/'.$entity)) {
			mkdir(public_path('files') . '/'.$entity);
		}
		if(in_array($ext,$arr_ext)) {
			$name_with_extension = $name_without_extension . '.' . $ext;

			if (file_exists(public_path('files') . '/' . $entity . '/' . $name_with_extension)) {
				unlink(public_path('files') . '/' . $entity . '/' . $name_with_extension);
			}
			$imageUri = $entity.'/'.$name_with_extension;
			$file->move(public_path('files/' . $entity), $name_with_extension);
			return $imageUri;
		}else{
			//dd('ok');
            return response()->json('impossible de creer cette image',500);
			//request()->session()->flash('warning',' Impossible d\'enregistrer le fichier, le format n\'est pas correct !!!');
		}


		return null;
	}
}
