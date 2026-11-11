<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_artwork_tracker extends CI_Migration
{
    public function up()
    {
        $this->dbforge->drop_table('tblartworktracker', TRUE);

        $this->dbforge->add_field(array(
            'ATID' => array(
                'type' => 'INT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'ATName' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
            ),
            'ATImage' => array(
               'type' => 'VARCHAR',
               'constraint' => '255',
           ),
            'ATChecklist' => array(
                'type' => 'LONGTEXT',
            ),
            'ATCreatedDate' => array(
                'type' => 'DATE',
            ),
        ));

        $this->dbforge->add_key('ATID', TRUE);
        $this->dbforge->create_table('tblartworktracker');
    }

    public function down()
    {
        $this->dbforge->drop_table('tblartworktracker', TRUE);
    }
}