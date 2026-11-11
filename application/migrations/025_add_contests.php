<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_contests extends CI_Migration
{
    public function up()
    {
        $this->dbforge->drop_table('tblcontests', TRUE);

        $this->dbforge->add_field(array(
            'CoID' => array(
                'type' => 'INT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE,
            ),
            'CoName' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'default' => '0',
            ),
            'CoDigits' => array(
                'type' => 'INT',
                'unsigned' => TRUE,
                'default' => '0',
            ),
            'CoDuration' => array(
                'type' => 'INT',
                'unsigned' => TRUE,
                'default' => '0',
            ),
            'CoStop' => array(
                'type' => 'INT',
                'unsigned' => TRUE,
                'default' => '0',
            ),
            'CoBackground' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
                'default' => '',
            ),
            'CoStatus' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
                'default' => '',
            ),
        ));

        $this->dbforge->add_key('CoID', TRUE);
        $this->dbforge->create_table('tblcontests');
    }

    public function down()
    {
        $this->dbforge->drop_table('tblcontests', TRUE);
    }
}