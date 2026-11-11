<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_generators extends CI_Migration
{
    public function up()
    {
        $this->db->query('
            CREATE OR REPLACE ALGORITHM=UNDEFINED DEFINER=CURRENT_USER SQL SECURITY DEFINER VIEW `generator_16` AS SELECT 0 n UNION ALL SELECT 1  UNION ALL SELECT 2  UNION ALL
               SELECT 3   UNION ALL SELECT 4  UNION ALL SELECT 5  UNION ALL
               SELECT 6   UNION ALL SELECT 7  UNION ALL SELECT 8  UNION ALL
               SELECT 9   UNION ALL SELECT 10 UNION ALL SELECT 11 UNION ALL
               SELECT 12  UNION ALL SELECT 13 UNION ALL SELECT 14 UNION ALL
               SELECT 15 ;
       ');

        $this->db->query('
            CREATE OR REPLACE ALGORITHM=UNDEFINED DEFINER=CURRENT_USER SQL SECURITY DEFINER VIEW `generator_256` AS SELECT ( ( hi.n << 4 ) | lo.n ) AS n
                 FROM generator_16 lo, generator_16 hi;
       ');

        $this->db->query('
            CREATE OR REPLACE ALGORITHM=UNDEFINED DEFINER=CURRENT_USER SQL SECURITY DEFINER VIEW `generator_4k` AS SELECT ( ( hi.n << 8 ) | lo.n ) AS n
                 FROM generator_256 lo, generator_16 hi;
       ');

        $this->db->query('
            CREATE OR REPLACE ALGORITHM=UNDEFINED DEFINER=CURRENT_USER SQL SECURITY DEFINER VIEW `generator_64k` AS SELECT ( ( hi.n << 8 ) | lo.n ) AS n
                 FROM generator_256 lo, generator_256 hi;
       ');

        $this->db->query('            
            CREATE OR REPLACE ALGORITHM=UNDEFINED DEFINER=CURRENT_USER SQL SECURITY DEFINER VIEW `generator_1m` AS SELECT ( ( hi.n << 16 ) | lo.n ) AS n
                 FROM generator_64k lo, generator_16 hi;
        ');
    }

    public function down()
    {
        $this->db->query('
            DROP VIEW IF EXISTS `generator_16`, `generator_256`, `generator_4k`, `generator_64k`, `generator_1m`;
        ');
    }
}