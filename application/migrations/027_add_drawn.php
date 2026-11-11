<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_drawn extends CI_Migration
{
    public function up()
    {
        $this->dbforge->drop_table('tbldrawn', TRUE);

        $this->dbforge->add_field(array(
            'DrID' => array(
                'type' => 'INT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE,
            ),
            'DrParticipantID' => array(
                'type' => 'INT',
                'unsigned' => TRUE,
                'null' => TRUE,
            ),
            'DrContestID' => array(
                'type' => 'INT',
                'unsigned' => TRUE,
                'null' => TRUE,
            ),
        ));

        $this->dbforge->add_key('DrID', TRUE);
        $this->dbforge->add_key('DrParticipantID');
        $this->dbforge->add_key('DrContestID');
        $this->dbforge->create_table('tbldrawn');
    }

    public function down()
    {
        $this->dbforge->drop_table('tbldrawn', TRUE);
    }
}