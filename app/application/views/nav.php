<?php
    $ent_url = site_url('entry_list');
    $ed_url = site_url('entry/edit');
    $inq_url = site_url('inquisitor');
    $rep_url = site_url('replicate');
    echo "<a style='padding-right: 15px;' href='$ent_url'>See entries</a>";
    echo "<a style='padding-right: 15px;' href='$ed_url'>Make entry</a>";
    echo "<a style='padding-right: 15px;' href='$inq_url'>Inquisitor</a>";
    echo "<a style='padding-right: 15px;' href='$rep_url'>Replicator</a>";
?>
<hr/>