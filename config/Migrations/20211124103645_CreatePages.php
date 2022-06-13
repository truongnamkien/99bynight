<?php

use Migrations\AbstractMigration;

class CreatePages extends AbstractMigration {

    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     * @return void
     */
    public function change() {
        $table = $this->table('pages');
        $table->addColumn('status', 'integer', [
            'default' => 0,
            'limit' => 2,
            'null' => false,
        ]);
        $table->addColumn('display_order', 'float', [
            'default' => 0,
            'null' => false,
        ]);
        $table->addIndex('status');
        $table->create();
    }

}
