<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_settings extends CI_Migration
{
    public function up()
    {
        $this->dbforge->drop_table('tblsettings', TRUE);

        $this->dbforge->add_field(array(
            'SeID' => array(
                'type' => 'INT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'SeName' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE,
            ),
            'SeMachineName' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE,
            ),
            'SeValue' => array(
                'type' => 'TEXT',
                'null' => TRUE,
            ),
        ));

        $this->dbforge->add_key('SeID', TRUE);
        $this->dbforge->create_table('tblsettings');
    }

    public function down()
    {
        $this->dbforge->drop_table('tblsettings', TRUE);
    }
}