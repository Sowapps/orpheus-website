<?php
/**
 * @author Florent HAZARD <f.hazard@sowapps.com>
 */

namespace App\Entity;

use Orpheus\EntityDescriptor\Entity\EntityDescriptor;
use Orpheus\EntityDescriptor\Entity\PermanentEntity;

/**
 * Entity for demo and remote tests
 *
 * @property string $name
 */
class DemoEntity extends PermanentEntity {
	
	public static function getEditableFields(): array {
		return ['name'];
	}
	
}

DemoEntity::initializeWithDescriptor(EntityDescriptor::build('demo-entity', (object)[
	'fields'  => [
		'create_date'    => 'datetime=now()',
		'create_ip'      => 'ip=clientIp()',
		'create_user_id' => 'ref=userId()',
		'name'           => 'string(4, 20)',
	],
	'indexes' => [
		'UNIQUE(name)',
	],
]));
