<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateLayersTable extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change(): void
    {
        $table = $this->table('layers', ['id' => false, 'primary_key' => 'id']);
        $table
            ->addColumn('id', 'string', ['null' => false])
            ->addColumn('parent_id', 'string', ['null' => true])
            ->addColumn('code', 'string', ['null' => false])
            ->addIndex('code', ['unique' => true])
            ->addColumn('type', 'string', ['null' => false])
            ->addColumn('discount_type', 'string', ['null' => true])
            ->addColumn('discount_value', 'integer', ['null' => true, 'signed' => true])
            ->addTimestampsWithTimezone()
            ->create();
    }
}
