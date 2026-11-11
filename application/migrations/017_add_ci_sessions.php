<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_ci_sessions extends CI_Migration
{ 
    public function up()
    {
        $this->db->query("
            CREATE TABLE IF NOT EXISTS `ci_sessions` (
                `id` varchar(128) NOT NULL,
                `ip_address` varchar(45) NOT NULL DEFAULT '',
                `timestamp` int(11) unsigned NOT NULL DEFAULT 0,
                `data` blob NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ");
    }

    public function down()
    {
    }
}