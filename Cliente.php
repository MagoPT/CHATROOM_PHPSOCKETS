<?php
error_reporting(E_ALL);
set_time_limit(0);

$cls = chr(27).chr(91).'H'.chr(27).chr(91).'J';

echo $cls;
echo ('Bem-Vindo a ChatRoom "OS LANEIROS"');                        //Mensagem de boas vindas
echo ("\n");

$User = readline("Nome de Utilizador: "); //UserName
$port = readline("Porta: \n"); //Porta
$ip = readline("IP: \n"); //IP
$protocolo = null;
echo $cls;
$sair = false;
$num = 0;
$counter = 0;
$send=true;
$client="Bem-vindo ao servidor dos Laneiros";
$out=[];
$ticker = "Joined the server";
$left="Left the server";
$input="";
function protocolo()
{
    echo("Por favor escolha o protocolo");
    echo("\nProtocolo UDP - 1");
    echo("\nProtocolo TCP - 2");
    echo("\nAjuda         - 3");
    echo("\nSair          - 4\n");
    $protocolo = readline("Protocolo: \n");
    return $protocolo;

}

while ($protocolo != 4) {
    echo $cls;
    switch ($protocolo) {

        case 1:
            while(1)
            {
                if($input==$left){exit;}
                $socket = socket_create(AF_INET,SOCK_DGRAM,SOL_UDP);
                if($socket < 0){
                    echo  "socket_create() ERRP: ".socket_strerror($socket)."\n";
                } else{
                    echo "SOCKET UDP create OK!\n";
                }
                echo "A ligar ao servidor'$ip' na porta'$port'...\n";

                while (1){
                    $recv = socket_recvfrom($socket,$buf,90800,0,$ip,$port);
                    echo $buf;
                    if($counter==0){
                        $send="[".date("H:i:s")."]".$User.":".$ticker;
                        $counter++;
                    }
                    elseif ($input==$left){exit;}
                    else {
                        $input = readline("Tu: ");
                        if ($input == 'q') {
                            $input = $left;
                        }
                        if ($input=="r"){
                            $send="r";
                        }
                        if ($input=="close"){
                            $send="close";
                        }
                        else {
                            $send = "[" . date("H:i:s") . "]" . $User . ": " . $input;
                        }
                    }
                    if(socket_sendto($socket,$send,strlen($send),0,$ip,$port)<0)
                    {
                        $errorcode = socket_last_error();
                        echo "socket_write() - SENDTO - failed. reason: ".socket_strerror($errorcode)."\n";
                    }

                }
            }

            echo "Turn Off Socket...\n";
            socket_close($socket);
            //socket_shutdown($socket);
            echo "Turn Off OK\n";
            break;

        case 2:
            echo "Para sair click q\n";
            $ticker = "Joined the server";
            $left="Left the server";
            while(1)
            {

                $socket= socket_create(AF_INET,SOCK_STREAM,SOL_TCP);
                if($socket===false)
                {
                    echo "Socket creation failed!";
                }
                $result = socket_connect($socket,$ip,$port);
                if($result===false)
                {
                    echo "Socket connection failed!";
                }
                else {
                    if($ticker=="close"){
                        socket_write($socket,"close",1024);
                    }
                    elseif ($ticker=="q"){
                        socket_write($socket, "[" . date("H:i:s") . "]$User : $left", 1024);
                        $sair=true;
                    }
                    elseif ($ticker=="r"){
                        socket_write($socket,"r",1024);
                    }
                    else{
                        socket_write($socket, "[" . date("H:i:s") . "]$User : $ticker", 1024);
                    }

                    $c=false;
                    $output = socket_read($socket, 1024);
                    echo $output;

                    if($sair) { exit; }
                    $a = strlen($User);
                    $ticker = readline("Tu: ");

                    //if ($ticker=='r'){$send=false;}

                }
            }

            echo "Turn Off Socket...\n";
            socket_close($socket);
            //socket_shutdown($socket);
            echo "Turn Off OK\n";
            break;
        case 3:
            echo $cls;
            echo "Controles para a chatroom 'Os Laneiros': \n";
            echo "      -q: desliga o cliente\n";
            echo "  -close: desliga o servidor\n";
            echo " -reload: recarrega o chat\n";
            readline("Prima enter:");
            echo $cls;
            $protocolo = protocolo();
            break;
        case 4:
            break;
        default:
            $protocolo = protocolo();
            break;
    }
}
?>
