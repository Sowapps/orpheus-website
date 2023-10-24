<?php
/**
 * @author Florent HAZARD <f.hazard@sowapps.com>
 */

namespace unit;

use Exception;
use Orpheus\EntityDescriptor\Generator\Sql\SqlGeneratorMySql;
use Orpheus\Exception\UserException;
use Orpheus\SqlAdapter\AbstractSqlAdapter;
use App\Test\TestEntity;
use PHPUnit\Framework\TestCase;

final class EntityTest extends TestCase {
	
	public static function tearDownAfterClass(): void {
		self::ensureEntityTableDeleted();
	}
	
	/**
	 * @throws Exception
	 */
	public function testCreateEntityTable(): void {
		// Assert create query
		$entityQuery = $this->getEntityTableCreateQuery();
		//		echo $entityQuery."\n\n";
		$expectedQuery = <<<QUERY

CREATE TABLE IF NOT EXISTS `test-entity` (
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`create_date` DATETIME NOT NULL,
	`create_ip` VARCHAR(40) NOT NULL,
	`create_user_id` INT(10) UNSIGNED NOT NULL,
	`name` VARCHAR(20) NOT NULL,
	UNIQUE (`name`)
) ENGINE=MYISAM CHARACTER SET `utf8`;
QUERY;
		$this->assertSame($entityQuery, $expectedQuery);
	}
	
	/**
	 * @throws Exception
	 */
	public function testInsertEntityFailure() {
		$this->ensureEntityTableCreated();
		// Invalid insert
		$this->expectException(UserException::class);
		TestEntity::createAndGet();
	}
	
	/**
	 * @throws Exception
	 */
	public function testInsertEntity() {
		$this->ensureEntityTableCreated();
		// Valid insert
		$input = ['name' => 'Test PHPUnit'];
		$entity = TestEntity::createAndGet($input);
		$this->assertIsNumeric($entity->id());
		$this->assertSame($entity->name, $input['name']);
	}
	
	public function testUpdateEntity() {
		$this->ensureEntityInstance();
		$entity = $this->getEntity();
		$input = ['name' => 'Other name'];
		$entity->update($input, ['name']);
		$this->assertSame($entity->name, $input['name']);
	}
	
	public function testDeleteEntity() {
		$this->ensureEntityInstance();
		$entity = $this->getEntity();
		$entity->remove();
		$this->assertSame($entity->isDeleted(), true);
	}
	
	public function testRemoveEntityTable() {
		// Assert drop query
		$entityQuery = $this->getEntityTableDeleteQuery();
		//		echo $entityQuery."\n\n";
		$expectedQuery = <<<QUERY

DROP TABLE IF EXISTS `test-entity`;
QUERY;
		$this->assertSame($entityQuery, $expectedQuery);
		// Drop entity table
		$this->ensureEntityTableDeleted();
	}
	
	/**
	 * @throws Exception
	 */
	protected static function ensureEntityTableCreated(): void {
		$entityQuery = self::getEntityTableCreateQuery();
		$sqlAdapter = TestEntity::getSqlAdapter();
		$sqlAdapter->query($entityQuery, AbstractSqlAdapter::PROCESS_EXEC);
	}
	
	/**
	 * @throws Exception
	 */
	protected static function getEntityTableCreateQuery(): string {
		$generator = new SqlGeneratorMySql();
		$sqlAdapter = TestEntity::getSqlAdapter();
		$entityDescriptor = TestEntity::getDescriptor();
		
		return strip_tags($generator->getCreate($entityDescriptor, $sqlAdapter));
	}
	
	protected static function ensureEntityTableDeleted(): void {
		$entityQuery = self::getEntityTableDeleteQuery();
		$sqlAdapter = TestEntity::getSqlAdapter();
		$sqlAdapter->query($entityQuery, AbstractSqlAdapter::PROCESS_EXEC);
	}
	
	protected static function getEntityTableDeleteQuery(): string {
		$generator = new SqlGeneratorMySql();
		$sqlAdapter = TestEntity::getSqlAdapter();
		$entityDescriptor = TestEntity::getDescriptor();
		
		return strip_tags($generator->getDrop($entityDescriptor, $sqlAdapter));
	}
	
	protected static function ensureEntityInstance(): TestEntity {
		return self::getEntity() ?? self::createEntity();
	}
	
	protected static function createEntity(): TestEntity {
		$input = ['name' => 'Test PHPUnit'];
		
		return TestEntity::createAndGet($input);
	}
	
	protected static function getEntity(): ?TestEntity {
		return TestEntity::requestSelect()->asObject()->run();
	}
	
}
