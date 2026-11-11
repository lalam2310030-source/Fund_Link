<?php
$request_id = isset($_GET['request_id']) ? intval($_GET['request_id']) : 0;
header("Location: ../Loan Request Details/index.php?id=".$request_id);
exit();
