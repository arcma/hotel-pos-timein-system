<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_print_templates extends CI_Migration
{
    public function up()
    {
        $this->dbforge->drop_table('tblprinttemplates', TRUE);

        $this->dbforge->add_field(array(
            'PTID' => array(
                'type' => 'INT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'PTName' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
            ),
            'PTLayers' => array(
                'type' => 'LONGTEXT',
            ),
            'PTConfig' => array(
                'type' => 'LONGTEXT',
            ),
            'PTActive' => array(
                'type' => 'TINYINT',
                'constraint' => '1',
                'default' => '0',
            ),
        ));

        $this->dbforge->add_key('PTID', TRUE);
        $this->dbforge->create_table('tblprinttemplates');
    }

    public function down()
    {
        $this->dbforge->drop_table('tblprinttemplates', TRUE);
    }
}