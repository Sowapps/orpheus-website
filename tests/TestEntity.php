<?php
/**
 * @author Florent HAZARD <f.hazard@sowapps.com>
 */

namespace App\Test;

use Orpheus\EntityDescriptor\Entity\EntityDescriptor;
use Orpheus\EntityDescriptor\Entity\PermanentEntity;

/**
 * Entity for tests, should NOT be persisted in database
 *
 * @property string $name
 */
class TestEntity extends PermanentEntity {
	
}

TestEntity::initializeWithDescriptor(EntityDescriptor::build('test-entity', (object)[
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
