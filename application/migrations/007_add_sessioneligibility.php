<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_sessioneligibility extends CI_Migration
{
    public function up()
    {
        $this->dbforge->drop_table('tblsessioneligibility', TRUE);

        $this->dbforge->add_field(array(
            'SEID' => array(
                'type' => 'INT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE,
            ),
            'SESessionID' => array(
                'type' => 'INT',
                'unsigned' => TRUE,
                'null' => TRUE,
            ),
            'SEParticipantID' => array(
                'type' => 'INT',
                'unsigned' => TRUE,
                'null' => TRUE,
            ),
        ));

        $this->dbforge->add_key('SEID', TRUE);
        $this->dbforge->add_key('SESessionID');
        $this->dbforge->add_key('SEParticipantID');
        $this->dbforge->create_table('tblsessioneligibility');
    }

    public function down()
    {
        $this->dbforge->drop_table('tblsessioneligibility', TRUE);
    }
}