<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_votes extends CI_Migration
{
    public function up()
    {
        $this->dbforge->drop_table('tblvotes', TRUE);

        $this->dbforge->add_field(array(
            'VoID' => array(
                'type' => 'INT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE,
            ),
            'VoParticipantID' => array(
                'type' => 'INT',
                'unsigned' => TRUE,
                'null' => TRUE,
            ),
            'VoQuestionID' => array(
                'type' => 'INT',
                'unsigned' => TRUE,
                'null' => TRUE,
            ),
            'VoOptionID' => array(
                'type' => 'INT',
                'unsigned' => TRUE,
                'null' => TRUE,
            ),
        ));

        $this->dbforge->add_key('VoID', TRUE);
        $this->dbforge->add_key('VoParticipantID');
        $this->dbforge->add_key('VoQuestionID');
        $this->dbforge->add_key('VoOptionID');
        $this->dbforge->create_table('tblvotes');
    }

    public function down()
    {
        $this->dbforge->drop_table('tblvotes', TRUE);
    }
}