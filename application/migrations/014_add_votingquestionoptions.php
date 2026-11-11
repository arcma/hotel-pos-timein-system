<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_votingquestionoptions extends CI_Migration
{
    public function up()
    {
        $this->dbforge->drop_table('tblvotingquestionoptions', TRUE);

        $this->dbforge->add_field(array(
            'VQOID' => array(
                'type' => 'INT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE,
            ),
            'VQOQuestionID' => array(
                'type' => 'INT',
                'unsigned' => TRUE,
                'null' => TRUE,
            ),
            'VQOOption' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE,
            ),
            'VQOImagePath' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE,
            ),
            'VQOOrder' => array(
                'type' => 'INT',
                'unsigned' => TRUE,
                'null' => TRUE,
            ),
        ));

        $this->dbforge->add_key('VQOID', TRUE);
        $this->dbforge->add_key('VQOQuestionID');
        $this->dbforge->create_table('tblvotingquestionoptions');
    }

    public function down()
    {
        $this->dbforge->drop_table('tblvotingquestionoptions', TRUE);
    }
}