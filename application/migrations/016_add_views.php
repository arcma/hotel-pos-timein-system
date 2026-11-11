<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_views extends CI_Migration
{
    public function up()
    {
        $this->db->query("
            CREATE OR REPLACE ALGORITHM=UNDEFINED DEFINER=CURRENT_USER SQL SECURITY DEFINER VIEW `vwmanual` AS SELECT LoText as 'tag', LoDateCreated, LoType FROM tbllogs WHERE LoType='manual marked' ;
        ");

        $this->db->query("
            CREATE OR REPLACE ALGORITHM=UNDEFINED DEFINER=CURRENT_USER SQL SECURITY DEFINER VIEW `vwregisteredorder` AS SELECT
               trim(replace(`tbllogs`.`LoText`,'successful','')) AS `tag`,
               `tbllogs`.`LoDateCreated` AS `LoDateCreated`,
               `tbllogs`.`LoType` AS `LoType`
            FROM `tbllogs` where ((`tbllogs`.`LoType` = 'scan') and (`tbllogs`.`LoText` like '%successful')) ;
        ");

        $this->db->query("    
            CREATE OR REPLACE ALGORITHM=UNDEFINED DEFINER=CURRENT_USER SQL SECURITY DEFINER VIEW `vwallstatus` AS SELECT * FROM vwmanual UNION ALL SELECT * FROM vwregisteredorder WHERE vwregisteredorder.tag NOT In (SELECT tag FROM vwmanual) ORDER BY LoDateCreated ;
        ");

        $this->db->query("
            CREATE OR REPLACE ALGORITHM=UNDEFINED DEFINER=CURRENT_USER SQL SECURITY DEFINER VIEW `vwregisteredname` AS SELECT
               `vwallstatus`.`tag` AS `tag`,
               `vwallstatus`.`LoDateCreated` AS `LoDateCreated`,
               `tblparticipants`.`PaID` AS `PaID`,
               `tblparticipants`.`PaName` AS `PaName`,
               `tblparticipants`.`PaEmail` AS `PaEmail`,
               `tblparticipants`.`PaTag` AS `PaTag`,
               `tblparticipants`.`PaAux` AS `PaAux`,
               `tblparticipants`.`PaStatus` AS `PaStatus`,
               `tblparticipants`.`PaTeam` AS `PaTeam`
            FROM (`vwallstatus` join `tblparticipants`) where (`tblparticipants`.`PaTag` = `vwallstatus`.`tag`) order by `vwallstatus`.`LoDateCreated` ;
        ");
    }

    public function down()
    {
        $this->db->query("
            DROP VIEW IF EXISTS `vwmanual`, `vwregisteredorder`, `vwallstatus`, `vwregisteredname`;
        ");
    }
}