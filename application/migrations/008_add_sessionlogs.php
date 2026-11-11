<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_sessionlogs extends CI_Migration
{
    public function up()
    {
        $this->dbforge->drop_table('tblsessionlogs', TRUE);

        $this->dbforge->add_field(array(
            'SLID' => array(
                'type' => 'INT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE,
            ),
            'SLParticipantID' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE,
            ),
            'SLType' => array(
                'type' => 'INT',
                'unsigned' => TRUE,
                'null' => TRUE,
            ),
            'SLTime' => array(
                'type' => 'DATETIME',
                'null' => TRUE,
            ),
            'SLSession' => array(
                'type' => 'INT',
                'unsigned' => TRUE,
                'null' => TRUE,
            ),
            'SLName' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE,
            ),
            'SLAux' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE,
            ),
            'SLAux2' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE,
            ),
            'SLAux3' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE,
            ),
            'SLSessionName' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE,
            ),
        ));

        $this->dbforge->add_key('SLID', TRUE);
        $this->dbforge->create_table('tblsessionlogs');
    }

    public function down()
    {
        $this->dbforge->drop_table('tblsessionlogs', TRUE);
    }
}