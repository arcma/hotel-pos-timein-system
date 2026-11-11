<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_luckydipdraws extends CI_Migration
{
    public function up()
    {
        $this->dbforge->drop_table('tblluckydipdraws', TRUE);

        $this->dbforge->add_field(array(
            'LDDID' => array(
                'type' => 'INT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE,
            ),
            'LDDParticipantID' => array(
                'type' => 'INT',
                'unsigned' => TRUE,
                'null' => TRUE,
            ),
            'LDDPrizeID' => array(
                'type' => 'INT',
                'unsigned' => TRUE,
                'null' => TRUE,
            ),
            'LDDDateCreated' => array(
                'type' => 'DATETIME',
                'null' => TRUE,
            ),
        ));

        $this->dbforge->add_key('LDDID', TRUE);
        $this->dbforge->add_key('LDDParticipantID');
        $this->dbforge->add_key('LDDPrizeID');
        $this->dbforge->create_table('tblluckydipdraws');
    }

    public function down()
    {
        $this->dbforge->drop_table('tblluckydipdraws', TRUE);
    }
}