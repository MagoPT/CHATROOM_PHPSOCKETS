<?php
set_time_limit(0);

$cls = chr(27).chr(91).'H'.chr(27).chr(91).'J';
echo $cls;
$ip = getHostByName(getHostName());
$port=42069;
$msg="Bem-vindo ao servidor dos laneiros\n";
$protocolo = null;
$sair = false;
$buf="";
$i=0;
$client="Bem-vindo ao servidor dos Laneiros";
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

//Comunicação simplificada com o cliente
            echo "\nA agurdar por transmissão ... \n";
            while (1)
            {


                //Receber Dadods
                $r = socket_recvfrom($sock,$buf,512,0,$ip,$port);
                echo $buf."\n";

                //Enviar dados de volta ao cliente
                socket_sendto($sock,$msg,100,0,$ip,$port);
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
            while(1)
            {
                $spawn[++$i] = socket_accept($sock) or die("Could not accept incoming
	connection\n");
                socket_write($spawn[$i],$client,9080);
                $input = socket_read($spawn[$i],1024);
                //$client = $input;

                echo $input ."\n";

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