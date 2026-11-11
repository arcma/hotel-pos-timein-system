<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_luckydipprizes extends CI_Migration
{
    public function up()
    {
        $this->dbforge->drop_table('tblluckydipprizes', TRUE);

        $this->dbforge->add_field(array(
            'LDPID' => array(
                'type' => 'INT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE,
            ),
            'LDPName' => array(
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => TRUE,
            ),
            'LDPQuantity' => array(
                'type' => 'INT',
                'unsigned' => TRUE,
                'null' => TRUE,
            ),
        ));

        $this->dbforge->add_key('LDPID', TRUE);
        $this->dbforge->create_table('tblluckydipprizes');
    }

    public function down()
    {
        $this->dbforge->drop_table('tblluckydipprizes', TRUE);
    }
}