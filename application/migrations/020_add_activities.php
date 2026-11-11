<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_activities extends CI_Migration
{
    public function up()
    {
        $this->dbforge->drop_table('tblactivities', TRUE);

        $this->dbforge->add_field(array(
            'ActID' => array(
                'type' => 'INT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE,
            ),
            'ActText' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE,
            ),
            'ActUrl' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE,
            ),
            'ActIsStatic' => array(
                'type' => 'INT',
                'constraint' => '1',
                'default' => 0,
            ),
            'ActIsEnabled' => array(
                'type' => 'INT',
                'constraint' => '1',
                'default' => 1,
            ),
            'ActOrder' => array(
                'type' => 'INT',
                'unsigned' => TRUE,
                'null' => TRUE,
            ),
            'ActColor' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE,
            ),
            'ActBgColor' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE,
            ),
        ));

        $this->dbforge->add_key('ActID', TRUE);
        $this->dbforge->create_table('tblactivities');

    }

    public function down()
    {
        $this->dbforge->drop_table('tblactivities', TRUE);
    }
}