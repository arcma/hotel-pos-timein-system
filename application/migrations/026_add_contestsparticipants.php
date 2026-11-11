<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_contestsparticipants extends CI_Migration
{
    public function up()
    {
        $this->dbforge->drop_table('tblcontestsparticipants', TRUE);

        $this->dbforge->add_field(array(
            'CPID' => array(
                'type' => 'INT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE,
            ),
            'CPParticipantID' => array(
                'type' => 'INT',
                'unsigned' => TRUE,
                'default' => '0',
            ),
            'CPContestID' => array(
                'type' => 'INT',
                'unsigned' => TRUE,
                'default' => '0',
            ),
            'CPRelation' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
                'default' => '0',
            ),
        ));

        $this->dbforge->add_key('CPID', TRUE);
        $this->dbforge->add_key('CPParticipantID');
        $this->dbforge->add_key('CPContestID');
        $this->dbforge->add_key('CPRelation');
        $this->dbforge->add_key(['CPContestID', 'CPParticipantID']);
        $this->dbforge->create_table('tblcontestsparticipants');
    }

    public function down()
    {
        $this->dbforge->drop_table('tblcontestsparticipants', TRUE);
    }
}