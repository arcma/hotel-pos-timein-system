<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_users extends CI_Migration
{
    public function up()
    {
        $this->dbforge->drop_table('tblusers', TRUE);

        $this->dbforge->add_field(array(
            'ID' => array(
                'type' => 'INT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE,
            ),
            'username' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE,
                'unique' => TRUE,
            ),
            'password' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE,
            ),
            // 'active' => array(
            //     'type' => 'INT',
            //     'constraint' => '1',
            //     'null' => TRUE,
            // ),
            'role' => array(
                'type' => 'INT',
                'unsigned' => TRUE,
                'default' => 2,
            ),
            'email' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE,
            ),
            'number' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE,
            ),
            'slack' => array(
                'type' => 'INT',
                'constraint' => '1',
                'default' => 0,
            ),
            'created' => array(
                'type' => 'DATETIME'
            ),
            'last_login' => array(
                'type' => 'DATETIME',
                'null' => TRUE
            ),
        ));

        $this->dbforge->add_key('ID', TRUE);
        $this->dbforge->create_table('tblusers');

    }

    public function down()
    {
        $this->dbforge->drop_table('tblusers', TRUE);
    }
}