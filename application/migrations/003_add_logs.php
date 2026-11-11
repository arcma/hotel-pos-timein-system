<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_logs extends CI_Migration
{
    public function up()
    {
        $this->dbforge->drop_table('tbllogs', TRUE);

        $this->dbforge->add_field(array(
            'LoID' => array(
                'type' => 'INT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE,
            ),
            'LoType' => array(
                'type' => 'TEXT',
            ),
            'LoText' => array(
                'type' => 'TEXT',
            ),
            'LoDateCreated' => array(
                'type' => 'DATETIME',
                'null' => TRUE,
            ),
            'LoIP' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => TRUE,
            ),
        ));

        $this->dbforge->add_key('LoID', TRUE);
        $this->dbforge->create_table('tbllogs');
    }

    public function down()
    {
        $this->dbforge->drop_table('tbllogs', TRUE);
    }
}