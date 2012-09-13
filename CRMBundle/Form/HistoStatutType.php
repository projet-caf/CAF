<?php

namespace CAF\CRMBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;

class HistoStatutType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('statutDemande', 'entity', array(
	            		'class' => 'CAFCRMBundle:StatutDemande', 
	            		'query_builder' => function(EntityRepository $er) {return $er->createQueryBuilder('statutDemande')
	            																		->where('statutDemande.published = 1')
	            																		->orderBy('statutDemande.libelle', 'ASC');},
	            		'property' => 'libelle',
	            		'empty_value' => 'Selectionnez un statut',
	            		'label' => "Statut"))
				->add('agence', 'entity', array(
	            		'class' => 'CAFCRMBundle:Agence', 
	            		'query_builder' => function(EntityRepository $er) {return $er->createQueryBuilder('a')
	            																		->where('a.published = 1')
	            																		->orderBy('a.nom', 'ASC');},
	            		'property' => 'nom',
	            		'empty_value' => 'Selectionnez une agence',
	            		'label' => "Agence"))
				->add('daterdv','date',array(
					'label' => "Date du rendez-vous",
					'widget' => 'single_text',
					'input' => 'datetime',
					'format' => 'dd/MM/yyyy',
					'attr' => array('class' => 'date')))
				->add('conseiller', 'entity', array(
	            		'class' => 'CAFCRMBundle:Conseiller', 
	            		'query_builder' => function(EntityRepository $er) {return $er->createQueryBuilder('c')
	            																		->where('c.published = 1')
	            																		->orderBy('c.nom', 'ASC');},
	            		'property' => 'nomPrenom',
	            		'empty_value' => 'Selectionnez un conseiller',
	            		'label' => "Conseiller"))
				->add('dateEnvoi','date',array(
					'label' => "Date d'envoi du dossier",
					'widget' => 'single_text',
					'input' => 'datetime',
					'format' => 'dd/MM/yyyy',
					'attr' => array('class' => 'date')))	
				->add('racineCompte','text',array('label' => "Racine compte"))
				->add('provenanceDemande', 'entity', array(
	            		'class' => 'CAFCRMBundle:ProvenanceDemande', 
	            		'query_builder' => function(EntityRepository $er) {return $er->createQueryBuilder('p')
	            																		->where('p.published = 1');},
	            		'property' => 'libelle',
	            		'label' => "Provenance demande",
	            		'expanded'=>true,
	            		'attr' => array('class' => 'radio'),
	            		'empty_value' => '-'))	
	            ->add('typeRecommandation', 'entity', array(
	            		'class' => 'CAFCRMBundle:TypeRecommandation', 
	            		'query_builder' => function(EntityRepository $er) {return $er->createQueryBuilder('tr')
	            																		->where('tr.published = 1');},
	            		'property' => 'libelle',
	            		'expanded'=>true,
	            		'attr' => array('class' => 'radio'),
	            		'label' => "Type de recommandation",
	            		'empty_value' => '-'))		
				->add('caisseRegionale', 'entity', array(
	            		'class' => 'CAFCRMBundle:CaisseRegionale', 
	            		'query_builder' => function(EntityRepository $er) {return $er->createQueryBuilder('cr')
	            																		->where('cr.published = 1');},
	            		'property' => 'numlabel',
	            		'empty_value' => '-'))	
				->add('agenceCaisseRegionale', 'entity', array(
	            		'class' => 'CAFCRMBundle:AgenceCaisseRegionale', 
	            		'query_builder' => function(EntityRepository $er) {return $er->createQueryBuilder('acr')
	            																		->where('acr.published = 1');},
	            		'property' => 'numville',
	            		'empty_value' => '-'))	
				->add('numeroRecommandation','integer',array('max_length'=>8,'attr' => array('class' =>'input-small'))	)
				->add('commentaire','textarea',array('label' => "Commentaire",'attr' => array('class' =>'input-xlarge')))	
				->add('langue', 'entity', array(
	            		'class' => 'CAFAdminBundle:Language', 
	            		'property' => 'name',
	            		'label' => "Langue"))
				;			
	}

	public function getDefaultOptions(array $options)
    {
        return array('data_class' => 'CAF\CRMBundle\Entity\HistoStatut');
    }
	
	public function getName() {
		return 'HistoStatut';
	}
}
