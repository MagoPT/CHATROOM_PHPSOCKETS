<?php
error_reporting(E_ALL);
set_time_limit(0);

$cls = chr(27).chr(91).'H'.chr(27).chr(91).'J'; //codigo equivalente ao comando cls

echo $cls; //limpar a tela
$art = '   
================================================.
     .-.   .-.     .--.                         |
    | OO| | OO|   / _.-\' .-.   .-.  .-.   .\'\'.  |
    |   | |   |   \  \'-. \'-\'   \'-\'  \'-\'   \'..\'  |
    \'^^^\' \'^^^\'    \'--\'                         |
===============.  .-.  .================.  .-.  |
 Bem-Vindo a   | |   | |BY:             |  \'-\'  |
   CHATROOM    | |   | |   Duarte Cruz  |       |
      OS       | \':-:\' |      &         |  .-.  |
   Laneiros    |  \'-\'  |   Bernardo G.  |  \'-\'  |
===============\'       \'================\'       |
         '; //arte ASCII

echo $art."\n\n";//Mensagem de boas vindas

$User = readline("Nome de Utilizador: "); //UserName
$port = readline("Porta: \n"); //Porta
$ip = readline("IP: \n"); //IP
//Declaração de Variaveis
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
//Declaração de Funções
function protocolo() //Menu para escolher o Protocolo
{
    echo("Por favor escolha o protocolo");
    echo("\nProtocolo UDP - 1");
    echo("\nProtocolo TCP - 2");
    echo("\nAjuda         - 3");
    echo("\nSair          - 4\n");
    $protocolo = readline("Protocolo: \n");
    return $protocolo;

}

while ($protocolo != 4) { //Caso o utilizador não queira sair
    echo $cls; //limpar tela
    switch ($protocolo) { //Escolha da opção inserida

        case 1: //caso o utilizador escolha o protocolo UDP
            while(1)
            {
                if($input==$left){exit;} //caso o utilizador queira sair (comando q)
                $socket = socket_create(AF_INET,SOCK_DGRAM,SOL_UDP); //Conexão ao servidor
                if($socket < 0){ //Caso a ligação seja bem sucedida
                    echo  "socket_create() ERRP: ".socket_strerror($socket)."\n";
                } else{
                    echo "SOCKET UDP create OK!\n";
                }
                echo "A ligar ao servidor'$ip' na porta'$port'...\n";

                while (1){
                    $recv = socket_recvfrom($socket,$buf,90800,0,$ip,$port); //Recebe os dados do servidor
                    echo $buf; //Echo da mensagem respondida do servidor
                    if($counter==0){ //Caso seja a primeira vez do ciclo UDP
                        $send="[".date("H:i:s")."]".$User.":".$ticker; //Mesagem a dizer que o cliente deu join no server
                        $counter++;
                    }
                    elseif ($input==$left){exit;} //caso o utilizador queira sair
                    else {
                        $input = readline("Tu: "); //Mensagem que será enviada ao servidor
                        if ($input == 'q') { //Comando para sair
                            $input = $left; //Mensagem que o cliente deixou o servidor
                        }
                        elseif ($input=="r"){ //Comando a pedir uma atualização de tela
                            $send="r";
                        }
                        elseif ($input=="b"){ //Comando para ver o historico
                            $send="b";
                        }
                        elseif ($input=="close"){ //Comando para fechar o servidor
                            $send="close";
                        }
                        else {
                            $send = "[" . date("H:i:s") . "]" . $User . ": " . $input; //Mensagem a ser enviada em caso normal
                        }
                    }
                    if(socket_sendto($socket,$send,strlen($send),0,$ip,$port)<0) //Validação se a mensagem foi enviada com sucesso
                    {
                        $errorcode = socket_last_error();//mensagem caso desja erro
                        echo "socket_write() - SENDTO - failed. reason: ".socket_strerror($errorcode)."\n"; //echo do erro
                    }

                }
            }

            echo "Turn Off Socket...\n";
            socket_close($socket);//Close do scoket
            //socket_shutdown($socket);
            echo "Turn Off OK\n";
            break;//Fim do ciclo UDP

        case 2://Caso o cliente escolha o protocolo TCP
            echo "Para sair click q\n";
            while(1)
            {

                $socket= socket_create(AF_INET,SOCK_STREAM,SOL_TCP);  //Criação do Socket
                if($socket===false) //Caso não seja possivel criar o socket
                {
                    echo "Socket creation failed!";
                }
                $result = socket_connect($socket,$ip,$port); //Conexão ao servidor
                if($result===false)//caso a conexão falhe
                {
                    echo "Socket connection failed!";
                }
                else { //caso a conexão seja bem sucedida
                    if($ticker=="close"){ //Comando para fechar o servidor
                        socket_write($socket,"close",1024);//Envio do pedido para fechar o servidor
                    }
                    elseif ($ticker=="q"){ //Comando para o utilizador sair
                        socket_write($socket, "[" . date("H:i:s") . "]$User : $left", 1024);//Envio da mensagem de saida ao servidor
                        $sair=true;
                    }
                    elseif ($ticker=="r"){ //Comando para o servidor atualizar a tela do cliente
                        socket_write($socket,"r",1024);//Envio do pedido de atualização ao servidor
                    }
                    elseif ($ticker=="b"){ //comando para verificar o historico
                        socket_write($socket,"b",1024);//Envio do pedido de historico ao servidor
                    }
                    else{ //Caso seja uma mensagem normal
                        socket_write($socket, "[" . date("H:i:s") . "]$User : $ticker", 1024); //Envio da mensagem ao servidor
                    }

                    $c=false;
                    $output = socket_read($socket, 1024); //Receção da Resposta do servidor
                    echo $output; //echo da resposta do servidor

                    if($sair) { exit; } //Caso o utilizador queira sair
                    $a = strlen($User);
                    $ticker = readline("Tu: "); //Mensagem a ser enviada


                }
            }

            echo "Turn Off Socket...\n";
            socket_close($socket);//Fechar o socket
            echo "Turn Off OK\n";
            break;//Fim do ciclo TCP

        case 3://Caso o utilizador queira consultar o menu de ajuda
            echo $cls;
            echo "Controles para a chatroom 'Os Laneiros': \n";
            echo "      -q: desliga o cliente\n";
            echo "  -close: desliga o servidor\n";
            echo " -reload: recarrega o chat\n";
            readline("Prima enter:");
            echo $cls;
            $protocolo = protocolo();
            break;
        case 4://Caso o utilizador queira sair
            break;
        default://caso o utilizador tenha escolhido uma opção inválida
            $protocolo = protocolo();
            break;
    }
}
?>
