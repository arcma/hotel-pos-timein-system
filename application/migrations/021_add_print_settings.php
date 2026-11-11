<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_print_settings extends CI_Migration
{
    public function up()
    {
        $this->dbforge->drop_table('tblprintsettings', TRUE);

        $this->dbforge->add_field(array(
            'PSKey' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
            ),
            'PSValue' => array(
                'type' => 'LONGTEXT',
            ),
        ));

        $this->dbforge->add_key('PSKey', TRUE);
        $this->dbforge->create_table('tblprintsettings');
    }

    public function down()
    {
        $this->dbforge->drop_table('tblprintsettings', TRUE);
    }
}