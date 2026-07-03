<?php
$f = new IntlDateFormatter("id_ID@calendar=islamic-umalqura", 0, -1, "Asia/Jakarta", 0, "d MMMM yyyy");
echo $f->format(new DateTime('2026-07-03'));
