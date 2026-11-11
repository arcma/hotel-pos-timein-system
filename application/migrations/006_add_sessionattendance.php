<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_sessionattendance extends CI_Migration
{
    public function up()
    {
        $this->dbforge->drop_table('tblsessionattendance', TRUE);

        $this->dbforge->add_field(array(
            'SAID' => array(
                'type' => 'INT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE,
            ),
            'SASessionID' => array(
                'type' => 'INT',
                'unsigned' => TRUE,
                'null' => TRUE,
            ),
            'SAParticipantID' => array(
                'type' => 'INT',
                'unsigned' => TRUE,
                'null' => TRUE,
            ),
            'SACheckInTime' => array(
                'type' => 'DATETIME',
                'null' => TRUE,
            ),
            'SACheckOutTime' => array(
                'type' => 'DATETIME',
                'null' => TRUE,
            ),
        ));

        $this->dbforge->add_key('SAID', TRUE);
        $this->dbforge->add_key('SASessionID');
        $this->dbforge->add_key('SAParticipantID');
        $this->dbforge->create_table('tblsessionattendance');
    }

    public function down()
    {
        $this->dbforge->drop_table('tblsessionattendance', TRUE);
    }
}