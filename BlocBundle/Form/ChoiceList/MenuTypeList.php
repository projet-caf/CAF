<?php 

namespace CAF\BlocBundle\Form\ChoiceList;

use Symfony\Component\Form\Extension\Core\ChoiceList\ChoiceListInterface;

class MenuTypeList implements ChoiceListInterface{

	public function getChoices(){
		return array('banner'=>'bannière', 'header'=>'menu haut', 'footer'=>'menu bas');
	}

	public function getIndicesForChoices(array $choices){

	}

	public function getValues(){

	}

	public function getPreferredViews(){

	}

	public function getRemainingViews(){

	}

	public function getChoicesForValues(array $values){

	}

	public function getValuesForChoices(array $choices){

	}

	public function getIndicesForValues(array $values){

	}
}