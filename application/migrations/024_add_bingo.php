<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_bingo extends CI_Migration
{
    public function up()
    {
        $this->dbforge->drop_table('tblbingo', TRUE);

        $this->dbforge->add_field(array(
            'BiID' => array(
                'type' => 'INT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE,
            ),
            'BiNumber' => array(
                'type' => 'INT',
                'null' => TRUE,
            ),
            'BiDateCreated' => array(
                'type' => 'DATETIME',
                'null' => TRUE,
            ),
        ));

        $this->dbforge->add_key('BiID', TRUE);
        $this->dbforge->create_table('tblbingo');
    }

    public function down()
    {
        $this->dbforge->drop_table('tblbingo', TRUE);
    }
}