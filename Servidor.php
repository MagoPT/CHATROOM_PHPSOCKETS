<?php
set_time_limit(0);

$cls = chr(27).chr(91).'H'.chr(27).chr(91).'J';
echo $cls;
$ip = getHostByName(getHostName());
$msg="Bem-vindo ao servidor dos laneiros\n";
$port=readline("Porta: ");
$protocolo = null;
$sair = false;
$buf="";
$i=0;
$sv="a";
$client="Bem-vindo ao servidor dos Laneiros";
$conversa=[];
function protocolo()
{
    echo("Por favor escolha o protocolo");
    echo("\nProtocolo UDP - 1");
    echo("\nProtocolo TCP - 2");
    echo("\nSair          - 3\n");
    $protocolo = readline("Protocolo: \n");
    return $protocolo;
}

function socket_CreateBind($protocolo,$ip,$port){
    if($protocolo == 1){
        $protocol_type = SOL_UDP;
    }
    else if ($protocolo == 2){
        $protocol_type = SOL_TCP;
    }
    if (($sock = socket_create(AF_INET,SOCK_STREAM,SOL_TCP))<0) {
        echo "socket_create () ERRO :" . socket_strerror($sock) . "\n";
    } else{
        echo "Socket Create OK!\n";
    }

    if (($ret = socket_bind($sock,$ip,$port))<0){
        echo "socket_bind() ERRO :".socket_strerror($ret)."\n";
    } else{
        echo "socket bind OK! \n";
    }
    return $sock;
}

function emoji_badwords($str){
    $str_explode = explode(" ", $str);
    $output="";
    $count = 0;

    for($i = 0; $i < count($str_explode);$i++){
        if($str_explode[$i] == ":smile:"){
            $str_explode[$i] = ":)";
        }
        else if($str_explode[$i] == ":cry:"){
            $str_explode[$i] = ":'(";
        }
        else if($str_explode[$i] == ":sad:"){
            $str_explode[$i] = ":(";
        }
        else if($str_explode[$i] == "caralho"){
            $str_explode[$i] = "c.....o(BadWord)";
        }
        else if($str_explode[$i] == "merda"){
            $str_explode[$i] = "m...a(BadWord)";
        }
        else if($str_explode[$i] == "puta"){
            $str_explode[$i] = "p..a(BadWord)";
        }
        else if($str_explode[$i] == "cabrão"){
            $str_explode[$i] = "c....o(BadWord)";
        }

        $output=$output.$str_explode[$i]." ";
    }
    return $output."\n";
}
//Comunicação simplificada com o cliente
while ($sair !=true) {
    echo $cls;
    switch ($protocolo) {
        case 1:
            if(($sock = socket_create(AF_INET,SOCK_DGRAM,SOL_UDP))<0) {
                echo "Socket_Create() ERRO :" . socket_strerror($sock) . "\n";
            } else{
                echo "Socket UDP create OK!\n";
            }
            if(($ret=socket_bind($sock,$ip,$port))<0){
                echo "Socket_bind() ERRO :".socket_strerror($ret)."\n";
            } else{
                echo "Socket UDP bind OK!\n";
            }

            echo "\nIP: ".$ip;
            echo "\nPorta: ".$port;
//Comunicação simplificada com o cliente
            echo "\n\nA agurdar por transmissão ... \n";
            while (1)
            {
                $input = socket_recvfrom($sock,$buf,512,0,$ip,$port);
                if($buf=="r"){
                    $send = $cls . "|------------------------------|\n";
                    $ze = array_reverse($conversa);
                    if (sizeof($conversa) < 20) {
                        for ($a = 0; $a < sizeof($conversa);$a++) {
                            $send = $send . $conversa[$a];
                        }
                    }else{
                        for ($a = 20; $a >= 0;$a--) {
                            $send = $send . $ze[$a];
                        }
                    }
                }
                elseif($buf=="close"){
                    $sv="b";
                    $sair=true;
                    socket_sendto($sock,"servidor encerrado\n", 100,0,$ip,$port);
                }
                else {
                    $final = emoji_badwords($buf);
                    echo $final;

                    array_push($conversa, $final);

                    //$conversa = $conversa . $final;
                    $send = $cls . "|------------------------------|\n";
                    $ze = array_reverse($conversa);
                    if (sizeof($conversa) <= 20) {
                        for ($a = 0; $a < sizeof($conversa);$a++) {
                            $send = $send . $conversa[$a];
                        }


                    }else{
                        for ($a = 20; $a >= 0;$a--) {
                            $send = $send . $ze[$a];
                        }
                    }
                }
                $send= $send."|______________________________|\n";
                socket_sendto($sock,$send, strlen($send),0,$ip,$port);
            }
            socket_close($sock);
//socket_shutdown($sock);
            break;

        case 2:
            $sock = socket_CreateBind($protocolo,$ip,$port);

            if(($ret = socket_listen($sock,4))<0){
                echo "socket_listen() ERRO :".socket_strerror($ret)."\n";
            } else{
                echo "socket listen OK\n";
            }
            echo "\nIP: ".$ip;
            echo "\nPorta: ".$port;
            echo "\n\n«« Servidor à espera de ligações »» \n";
            while($sv=="a")
            {
                $spawn[++$i] = socket_accept($sock) or die("Could not accept incoming
	connection\n");
                $input = socket_read($spawn[$i],1024);
                //$ip = socket_select();
                //$client = $input;
                if($input=="r"){
                    $send = $cls . "|------------------------------|\n";
                    $ze = array_reverse($conversa);
                    if (sizeof($conversa) < 20) {
                        for ($a = 0; $a < sizeof($conversa);$a++) {
                            $send = $send . $conversa[$a];
                        }


                    }else{
                        for ($a = 20; $a >= 0;$a--) {
                            $send = $send . $ze[$a];
                        }
                    }
                }
                elseif($input=="close"){
                    $sv="b";
                    $sair=true;
                    socket_write($spawn[$i], "servidor encerrado", 9080);
                }
                else {
                    $final = emoji_badwords($input);
                    echo $final;

                    array_push($conversa, $final);

                    //$conversa = $conversa . $final;
                    $send = $cls . "|------------------------------|\n";
                    $ze = array_reverse($conversa);
                    if (sizeof($conversa) <= 20) {
                        for ($a = 0; $a < sizeof($conversa);$a++) {
                            $send = $send . $conversa[$a];
                        }


                    }else{
                        for ($a = 20; $a >= 0;$a--) {
                            $send = $send . $ze[$a];
                        }
                    }
                }
                $send= $send."|______________________________|\n";
                socket_write($spawn[$i], $send, 9080);
                socket_close($spawn[$i]);
            }
            socket_close($sock);
//socket_shutdown($sock);
            break;

        case 3:
            $sair = true;
            break;

        default:
            $protocolo = protocolo();
            break;
    }
}
?>