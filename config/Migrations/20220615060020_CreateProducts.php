<?php

use Migrations\AbstractMigration;

class CreateProducts extends AbstractMigration {

    public function change() {
        $table = $this->table('products');
        $table->addColumn('category_id', 'integer', [
            'default' => 0,
            'limit' => 11,
            'null' => false,
        ]);
        $table->addIndex('category_id');
        $table->addColumn('status', 'integer', [
            'default' => 0,
            'limit' => 2,
            'null' => false,
        ]);
        $table->addIndex('status');
        $table->addColumn('price', 'integer', [
            'default' => 0,
            'limit' => 11,
            'null' => false,
        ]);
        $table->addIndex('category_id');
        $table->addColumn('display_order', 'float', [
            'default' => 0,
            'null' => false,
        ]);
        $table->create();
    }

}
