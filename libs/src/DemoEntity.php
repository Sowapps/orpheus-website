<?php
use Orpheus\EntityDescriptor\PermanentEntity;

/** A sample Demo Entity class

	Example of how to use the permanent entity.
*/
class DemoEntity extends PermanentEntity {
	
	//Attributes
	protected static $table		= 'demoentity';
	
	// Final attributes
	protected static $fields	= null;
	protected static $validator	= null;
	protected static $domain	= null;
	
}

DemoEntity::init();
