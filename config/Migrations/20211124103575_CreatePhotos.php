<?php

use Migrations\AbstractMigration;

class CreatePhotos extends AbstractMigration {

    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     * @return void
     */
    public function change() {
        $table = $this->table('photos');
        $table->addColumn('target_id', 'integer', [
            'default' => 0,
            'limit' => 11,
            'null' => false,
        ]);
        $table->addIndex('target_id');
        $table->addColumn('target_type', 'string', [
            'default' => '',
            'limit' => 50,
            'null' => false,
        ]);
        $table->addIndex('target_type');
        $table->addColumn('field', 'string', [
            'default' => '',
            'limit' => 50,
            'null' => false,
        ]);
        $table->addIndex('field');
        $table->addColumn('path', 'string', [
            'default' => '',
            'limit' => 255,
            'null' => false,
        ]);
        $table->addColumn('metadata', 'text', [
            'default' => null,
            'null' => true,
        ]);
        $table->create();
    }

}
