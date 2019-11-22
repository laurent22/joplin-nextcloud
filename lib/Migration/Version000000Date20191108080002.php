<?php

namespace OCA\Joplin\Migration;

use Closure;
use OCP\DB\ISchemaWrapper;
use OCP\Migration\SimpleMigrationStep;
use OCP\Migration\IOutput;

class Version000000Date20191108080002 extends SimpleMigrationStep {

	public function changeSchema(IOutput $output, Closure $schemaClosure, array $options) {
		$schema = $schemaClosure();

		if (!$schema->hasTable('joplin_sync_targets')) {
			$table = $schema->createTable('joplin_sync_targets');

			$table->addColumn('id', 'integer', [
				'autoincrement' => true,
				'notnull' => true,
			]);

			$table->addColumn('uuid', 'string', [
				'notnull' => true,
				'length' => 22,
			]);

			$table->addColumn('user_id', 'string', [
				'notnull' => true,
				'length' => 64,
			]);

			$table->addColumn('path', 'string', [
				'notnull' => true,
				'length' => 2048,
			]);

			$table->setPrimaryKey(['id']);
		}

		return $schema;
	}
}
