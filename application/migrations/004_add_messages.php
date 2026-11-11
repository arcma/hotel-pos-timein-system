<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_messages extends CI_Migration
{
    public function up()
    {
        $this->dbforge->drop_table('tblmessages', TRUE);

        $this->dbforge->add_field(array(
            'MsID' => array(
                'type' => 'INT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'MsText' => array(
                'type' => 'TEXT',
                'null' => TRUE,
            ),
            'MsStatus' => array(
                'type' => 'TINYINT',
                'constraint' => '1',
                'null' => TRUE,
            ),
            'MsDateCreated' => array(
                'type' => 'DATETIME',
                'null' => TRUE,
            ),
        ));

        $this->dbforge->add_key('MsID', TRUE);
        $this->dbforge->create_table('tblmessages');
    }

    public function down()
    {
        $this->dbforge->drop_table('tblmessages', TRUE);
    }
}