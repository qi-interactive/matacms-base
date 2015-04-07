<?php 

namespace matacms\interfaces;

interface HumanInterface {

	/**
	 * Get's a name which describes the entity
	 */
	public function getLabel();

	/**
	 * Returns a URI to a media element -- image or video -- that
	 * can serve as a visual representation of the entity
	 */ 
	public function getVisualRepresentation();


}