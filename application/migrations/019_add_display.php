<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_display extends CI_Migration
{
    public function up()
    {
        $this->dbforge->drop_table('tbldisplay', TRUE);

        $this->dbforge->add_field(array(
            'DsID' => array(
                'type' => 'INT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'DsText' => array(
                'type' => 'TEXT',
                'null' => TRUE,
            ),
            'DsAux' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE,
            ),
            'DsAux2' => array(
                'type' => 'TEXT',
                'constraint' => '255',
                'null' => TRUE,
            ),
            'DsStatus' => array(
                'type' => 'TINYINT',
                'constraint' => '1',
                'null' => TRUE,
            ),
            'DsDateCreated' => array(
                'type' => 'DATETIME',
                'null' => TRUE,
            ),
        ));

        $this->dbforge->add_key('DsID', TRUE);
        $this->dbforge->create_table('tbldisplay');
    }

    public function down()
    {
        $this->dbforge->drop_table('tbldisplay', TRUE);
    }
}