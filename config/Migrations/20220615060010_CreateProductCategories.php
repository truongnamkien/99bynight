<?php

use Migrations\AbstractMigration;

class CreateProductCategories extends AbstractMigration {

    public function change() {
        $table = $this->table('product_categories');
        $table->addColumn('status', 'integer', [
            'default' => 0,
            'limit' => 2,
            'null' => false,
        ]);
        $table->addIndex('status');
        $table->addColumn('parent_id', 'integer', [
            'default' => 0,
            'limit' => 11,
            'null' => false,
        ]);
        $table->addIndex('parent_id');
        $table->addColumn('display_order', 'float', [
            'default' => 0,
            'null' => false,
        ]);
        $table->create();
    }

}
