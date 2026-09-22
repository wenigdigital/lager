<?php

declare(strict_types=1);

namespace OCA\Lager\Migration;

use Closure;
use OCP\DB\ISchemaWrapper;
use OCP\DB\Types;
use OCP\Migration\IOutput;
use OCP\Migration\SimpleMigrationStep;

class Version010100Date20260921000000 extends SimpleMigrationStep {
	public function changeSchema(IOutput $output, Closure $schemaClosure, array $options): ?ISchemaWrapper {
		/** @var ISchemaWrapper $schema */
		$schema = $schemaClosure();

		if ($schema->hasTable('lager_stock')) {
			$table = $schema->getTable('lager_stock');
			if (!$table->hasColumn('ean')) {
				$table->addColumn('ean', Types::STRING, [
					'notnull' => false,
					'length' => 32,
					'comment' => 'EAN/GTIN oder QR-Code-Wert',
				]);
				$table->addUniqueIndex(['ean'], 'lager_stock_ean');
			}
		}

		return $schema;
	}
}
