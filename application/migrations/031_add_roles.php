<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_roles extends CI_Migration
{
    public function up()
    {
        $this->dbforge->drop_table('tblroles', TRUE);

        $this->dbforge->add_field(array(
            'RoID' => array(
                'type' => 'INT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'RoName' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
            ),
            'RoLabel' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
            ),
        ));

        $this->dbforge->add_key('RoID', TRUE);
        $this->dbforge->add_key('RoName');
        $this->dbforge->create_table('tblroles');
    }

    public function down()
    {
        $this->dbforge->drop_table('tblroles', TRUE);
    }
}