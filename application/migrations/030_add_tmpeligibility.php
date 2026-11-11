<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_tmpeligibility extends CI_Migration
{
    public function up()
    {
        $this->dbforge->drop_table('tbltmpeligibility', TRUE);

        $this->dbforge->add_field(array(
            'ELID' => array(
                'type' => 'INT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE,
            ),
            'ELParticipantID' => array(
                'type' => 'INT',
                'unsigned' => TRUE,
                'null' => TRUE,
            ),
            'ELParticipantTag' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE,
            ),
            'ELWeighting' => array(
                'type' => 'INT',
                'unsigned' => TRUE,
                'null' => TRUE,
            ),
            'ELCount' => array(
                'type' => 'INT',
                'unsigned' => TRUE,
                'null' => TRUE,
            ),
        ));

        $this->dbforge->add_key('ELID', TRUE);
        $this->dbforge->add_key('ELParticipantID');
        $this->dbforge->create_table('tbltmpeligibility');
    }

    public function down()
    {
        $this->dbforge->drop_table('tbltmpeligibility', TRUE);
    }
}