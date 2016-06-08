<!DOCTYPE html>
<html >

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="refresh" content="60"/>
<title>Test of Text anywhere</title>
<link href="Main.css" rel="stylesheet" type="text/css" />
</head>

<body>
<?php
    $sc = new SoapClient('http://www.textapp.net/webservice/service.asmx?wsdl');

    $params = new stdClass();
    $params->returnCSVString = false;
    $params->externalLogin = 'SU0729871';
    $params->password = 'peaches';
    $params->clientBillingReference = 'Testing';
    $params->clientMessageReference = 'FirstTest';
    $params->originator = '+447794456606';
    $params->destinations = '+447539311774';
    $params->body = utf8_encode('First test');
    $params->validity = 72;
    $params->characterSetID = 2;
    $params->replyMethodID = 4;
    $params->replyData = '';
    $params->statusNotificationUrl = '';

    $result = $sc->__call('testSendSMS', array($params));

    echo $result->SendtestSMSResult;
    ?>
</body>

</html>
