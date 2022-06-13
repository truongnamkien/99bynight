<?php

use Migrations\AbstractMigration;

class CreateMultiPhotos extends AbstractMigration {

    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     * @return void
     */
    public function change() {
        $table = $this->table('multi_photos');
        $table->addColumn('field', 'string', [
            'default' => null,
            'limit' => 100,
            'null' => false,
        ]);
        $table->addColumn('target_id', 'integer', [
            'default' => 0,
            'limit' => 11,
            'null' => false,
        ]);
        $table->addColumn('target_type', 'string', [
            'default' => '',
            'limit' => 30,
            'null' => false,
        ]);
        $table->addColumn('path', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
        ]);
        $table->addIndex(['target_id', 'target_type']);
        $table->addIndex('field');
        $table->create();
    }

}
