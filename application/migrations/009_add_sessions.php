<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_sessions extends CI_Migration
{
    public function up()
    {
        $this->dbforge->drop_table('tblsessions', TRUE);

        $this->dbforge->add_field(array(
            'SessID' => array(
                'type' => 'INT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE,
            ),
            'SessLocationID' => array(
                'type' => 'INT',
                'unsigned' => TRUE,
                'null' => TRUE,
            ),
            'SessNumber' => array(
                'type' => 'INT',
                'unsigned' => TRUE,
                'null' => TRUE,
            ),
            'SessName' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE,
            ),
            'SessVenue' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE,
            ),
            'SessCapacity' => array(
                'type' => 'INT',
                'unsigned' => TRUE,
                'null' => TRUE,
            ),
            'SessQuota' => array(
                'type' => 'INT',
                'unsigned' => TRUE,
                'null' => TRUE,
            ),
            'SessStartTime' => array(
                'type' => 'DATETIME',
                'null' => TRUE,
            ),
            'SessEndTime' => array(
                'type' => 'DATETIME',
                'null' => TRUE,
            ),
        ));

        $this->dbforge->add_key('SessID', TRUE);
        $this->dbforge->add_key('SessLocationID');
        $this->dbforge->create_table('tblsessions');
    }

    public function down()
    {
        $this->dbforge->drop_table('tblsessions', TRUE);
    }
}