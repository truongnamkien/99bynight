<?php

use Migrations\AbstractMigration;

class CreateBanners extends AbstractMigration {

    public function change() {
        $table = $this->table('banners');
        $table->addColumn('position', 'integer', [
            'default' => 0,
            'limit' => 5,
            'null' => false,
        ]);
        $table->addIndex('position');

        $table->addColumn('text_color', 'string', [
            'default' => null,
            'limit' => 10,
            'null' => true,
        ]);
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
