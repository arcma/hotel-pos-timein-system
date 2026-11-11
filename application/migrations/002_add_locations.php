<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_locations extends CI_Migration
{
    public function up()
    {
        $this->dbforge->drop_table('tbllocations', TRUE);

        $this->dbforge->add_field(array(
            'LocID' => array(
                'type' => 'INT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE,
            ),
            'LocName' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE,
            ),
        ));

        $this->dbforge->add_key('LocID', TRUE);
        $this->dbforge->create_table('tbllocations');
    }

    public function down()
    {
        $this->dbforge->drop_table('tbllocations', TRUE);
    }
}