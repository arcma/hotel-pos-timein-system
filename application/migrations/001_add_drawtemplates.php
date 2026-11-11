<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_drawtemplates extends CI_Migration
{
    public function up()
    {
        $this->dbforge->drop_table('tbldrawtemplates', TRUE);

        $this->dbforge->add_field(array(
            'DTID' => array(
                'type' => 'INT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE,
            ),
            'DTName' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE,
            ),
            'DTText' => array(
                'type' => 'LONGTEXT',
            ),
        ));

        $this->dbforge->add_key('DTID', TRUE);
        $this->dbforge->create_table('tbldrawtemplates');
    }

    public function down()
    {
        $this->dbforge->drop_table('tbldrawtemplates', TRUE);
    }
}