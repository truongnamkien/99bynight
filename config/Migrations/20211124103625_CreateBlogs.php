<?php

use Migrations\AbstractMigration;

class CreateBlogs extends AbstractMigration {

    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     * @return void
     */
    public function change() {
        $table = $this->table('blogs');
        $table->addColumn('category_id', 'integer', [
            'default' => 0,
            'limit' => 11,
            'null' => false,
        ]);
        $table->addColumn('status', 'integer', [
            'default' => 0,
            'limit' => 2,
            'null' => false,
        ]);
        $table->addColumn('featured', 'integer', [
            'default' => 0,
            'limit' => 2,
            'null' => false,
        ]);
        $table->addColumn('published_date', 'datetime', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('created', 'datetime', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('modified', 'datetime', [
            'default' => null,
            'null' => false,
        ]);
        $table->addIndex('category_id');
        $table->addIndex('status');
        $table->addIndex('featured');
        $table->create();
    }

}
