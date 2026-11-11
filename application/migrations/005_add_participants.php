<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_participants extends CI_Migration
{
    public function up()
    {
        $this->dbforge->drop_table('tblparticipants', TRUE);

        $this->dbforge->add_field(array(
            'PaID' => array(
                'type' => 'INT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE,
            ),
            'PaName' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE,
            ),
            'PaEmail' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE,
            ),
            'PaTag' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE,
            ),
            'PaAltTag' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE,
            ),
            'PaAux' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE,
            ),
            'PaAux2' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE,
            ),
            'PaAux3' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE,
            ),
            'PaAux4' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE,
            ),
            'PaAux5' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE,
            ),
            'PaAux6' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE,
            ),
            'PaAux7' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE,
            ),
            'PaAux8' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE,
            ),
            'PaAux9' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE,
            ),
            'PaAux10' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE,
            ),
            'PaAux11' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE,
            ),
            'PaAux12' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE,
            ),
            'PaAux13' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE,
            ),
            'PaAux14' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE,
            ),
            'PaAux15' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE,
            ),
            'PaAux16' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE,
            ),
            'PaAux17' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE,
            ),
            'PaAux18' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE,
            ),
            'PaAux19' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE,
            ),
            'PaAux20' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE,
            ),
            'PaAux21' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE,
            ),
            'PaAux22' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE,
            ),
            'PaAux23' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE,
            ),
            'PaAux24' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE,
            ),
            'PaAux25' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE,
            ),
            'PaTeam' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => TRUE,
            ),
            'PaStatus' => array(
                'type' => 'TINYINT',
                'constraint' => '1',
                'null' => TRUE,
            ),
            'PaLocation' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE,
            ),
            'PaCurrentPoints' => array(
                'type' => 'INT',
                'unsigned' => TRUE,
                'default' => '0',
            ),
            'PaLastSessionAttended' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE,
            ),
            'PaDateLastSessionAttended' => array(
                'type' => 'DATETIME',
                'null' => TRUE,
            ),
            'PaTotalAttendance' => array(
                'type' => 'INT',
                'unsigned' => TRUE,
                'default' => '0',
            ),
            'PaSessionsAttended' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE,
            ),
            'PaIsWalkIn' => array(
                'type' => 'TINYINT',
                'constraint' => '1',
                'default' => '0',
            ),
            'PaWeighting' => array(
                'type' => 'INT',
                'unsigned' => TRUE,
                'default' => '1',
            ),
            'PaDateLastPresent' => array(
                'type' => 'DATETIME',
                'null' => TRUE,
            ),
            'PaDateLastPrint' => array(
                'type' => 'DATETIME',
                'null' => TRUE,
            ),
            'PaDateLastScan' => array(
                'type' => 'DATETIME',
                'null' => TRUE,
            ),
			'PaSignature' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE,
            ),
            'PaConsent' => array(
                'type' => 'VARCHAR',
                'constraint' => '3',
                'null' => TRUE,
            ),
        ));

        $this->dbforge->add_key('PaID', TRUE);
        $this->dbforge->add_key('PaStatus');
        $this->dbforge->create_table('tblparticipants');
    }

    public function down()
    {
        $this->dbforge->drop_table('tblparticipants', TRUE);
    }
}