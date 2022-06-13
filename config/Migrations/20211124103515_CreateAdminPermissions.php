<?php

use Migrations\AbstractMigration;

class CreateAdminPermissions extends AbstractMigration {

    public function change() {
        $table = $this->table('admin_permissions');
        $table->addColumn('admin_role_id', 'integer', [
            'default' => 0,
            'limit' => 11,
            'null' => false,
        ]);
        $table->addColumn('content', 'text', [
            'default' => null,
            'null' => false,
        ]);
        $table->addIndex('admin_role_id');
        $table->create();
    }

}
