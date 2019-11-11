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
function protocolo()
{
    echo("Por favor escolha o protocolo");
    echo("\nProtocolo UDP - 1");
    echo("\nProtocolo TCP - 2");
    echo("\nSair          - 3\n");
    $protocolo = readline("Protocolo: \n");
    return $protocolo;

}

while ($protocolo != 3) {
    echo $cls;
    switch ($protocolo) {

        case 1:

            $socket = socket_create(AF_INET,SOCK_DGRAM,SOL_UDP);
            if($socket < 0){
                echo  "socket_create() ERRP: ".socket_strerror($socket)."\n";
            } else{
                echo "SOCKET UDP create OK!\n";
            }
            echo "A ligar ao servidor'$ip' na porta'$port'...\n";

            while (1)
            {
                if($counter==0){
                    //$resposta=socket_recvfrom($socket,$input,512,0,$ip,$port)<0;
                    //echo "Resposta do servidor: ".$resposta;
                    echo $client."\n";
                    echo "Para sair click q\n";
                    $counter++;
                }
                $input =readline("Tu: ");
                if($input=='q') { exit; }
                $send="[".date("H:i:s")."]".$User.":".$input;
                if(socket_sendto($socket,$send,strlen($send),0,$ip,$port)<0)
                {
                    $errorcode = socket_last_error();
                    echo "socket_write() - SENDTO - failed. reason: ".socket_strerror($errorcode)."\n";
                }
            }

            echo "Turn Off socket...\n";
            socket_close($socket);
            echo "Turn Off OK\n";
            break;

        case 2:
            echo "Para sair click q\n";
            $ticker = "Joined the server";
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
                    if($send==true) {
                        socket_write($socket, "\n[" . date("H:i:s") . "]$User : $ticker", 1024);
                    }
                    else{
                        $send=true;
                        socket_write($socket,"r",1024);
                    }
                    $out = socket_read($socket,1024);
                    echo $out."\n";

                    $a = strlen($User);
                    $ticker = readline("Tu: ");
                    if($ticker=='q') { exit; }
                    elseif ($ticker=='r'){$send=false;}

                }
            }

            echo "Turn Off Socket...\n";
            socket_close($socket);
            //socket_shutdown($socket);
            echo "Turn Off OK\n";
            break;

        case 3:
            break;
        default:
            $protocolo = protocolo();
            break;
    }
}
?>
