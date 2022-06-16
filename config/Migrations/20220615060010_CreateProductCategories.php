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
        $table->addColumn('display_order', 'float', [
            'default' => 0,
            'null' => false,
        ]);
        $table->create();
    }

}
