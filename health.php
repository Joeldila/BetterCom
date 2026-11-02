<?php
header('Content-Type: application/json');
echo json_encode(['status'=>'ok','app'=>'BetterCom','time'=>date(DATE_ATOM)]);
