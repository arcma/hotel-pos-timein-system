<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_votingquestions extends CI_Migration
{
    public function up()
    {
        $this->dbforge->drop_table('tblvotingquestions', TRUE);

        $this->dbforge->add_field(array(
            'VQID' => array(
                'type' => 'INT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE,
            ),
            'VQQuestion' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE,
            ),
            'VQOrder' => array(
                'type' => 'INT',
                'unsigned' => TRUE,
                'null' => TRUE,
            ),
        ));

        $this->dbforge->add_key('VQID', TRUE);
        $this->dbforge->create_table('tblvotingquestions');
    }

    public function down()
    {
        $this->dbforge->drop_table('tblvotingquestions', TRUE);
    }
}