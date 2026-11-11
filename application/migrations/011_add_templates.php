<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_templates extends CI_Migration
{
    public function up()
    {
        $this->dbforge->drop_table('tbltemplates', TRUE);

        $this->dbforge->add_field(array(
            'TeID' => array(
                'type' => 'INT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'TeName' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE,
            ),
            'TeSubject' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE,
            ),
            'TeText' => array(
                'type' => 'LONGTEXT',
            ),
        ));

        $this->dbforge->add_key('TeID', TRUE);
        $this->dbforge->create_table('tbltemplates');
    }

    public function down()
    {
        $this->dbforge->drop_table('tbltemplates', TRUE);
    }
}