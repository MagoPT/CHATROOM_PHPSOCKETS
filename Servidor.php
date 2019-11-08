<?php
set_time_limit(0);

$cls = chr(27).chr(91).'H'.chr(27).chr(91).'J';
echo $cls;
$ip='127.0.0.1';
$port=42069;
$msg="Mensagem UDP do servidor";
$protocolo = null;
$sair = false;
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


//Comunicação simplificada com o cliente
while ($sair !=true) {
    echo $cls;
    switch ($protocolo) {
        case 1:
            $sock = socket_CreateBind($protocolo, $ip, $port);
            while (1)
            {
                echo "\nA agurdar por transmissão ... \n";

                //Receber Dadods
                $r = socket_recvfrom($sock,$buf,512,0,$remote_ip,$remote_port);
                echo "Mensagem UDP Recebida de: ".$remote_ip.$remote_port."-->".$buf;

                //Enviar dados de volta ao cliente
                socket_sendto($sock,$msg,$buf,100,0,$remote_ip,$remote_port);
            }
            socket_close($sock);
            break;

        case 2:
            $sock = socket_CreateBind($protocolo,$ip,$port);

            if(($ret = socket_listen($sock,4))<0){
                echo "socket_listen() ERRO :".socket_strerror($ret)."\n";
            } else{
                echo "socket listen OK\n";
            }

            echo "«« Servidor à espera de ligações »» \n";
            $count = 0;
            do{
                if (($msgsock = socket_accept($sock))<0){
                    echo "socket_accept() Falhou: Motivo: ".socket_strerror($msgsock)."\n";
                    break;
                } else{
                    //$msg = "Mensagem recebida com sucesso do cliente\n";
                    socket_write($msgsock,$msg,strlen($msg));

                    //echo "Conexão bem sucedida\n";
                    $buf = socket_read($msgsock,8192);

                    $talkback = "$buf";
                    echo  $talkback;
                }
                //echo $buf;
                socket_close($msgsock);
            } while (true);
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