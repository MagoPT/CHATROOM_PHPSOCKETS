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
            $socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
            if ($socket < 0) {
                echo "socket_create() ERRP: " . socket_strerror($socket) . "\n";
            } else {
                echo "SOCKET UDP create OK!\n";
            }
            echo "A ligar ao servidor'$ipserver' na porta'$portserver'...\n";

            while (1) {
                //Opção de envio onde as mensagens são pedidas ao user
                //echo "Enter a mensage to send: "
                //$input = fgets(STDIN);
                $num = $num + 1;
                $input = "Mensagem $num UDP do cliente";
                echo "Mensagem enviada: '$input'\n";
                //Enviar mensagem ao servidor
                if (socket_sendto($socket, $input, strlen($input), 0, $ipserver, $portserver) < 0) {
                    $errorcode = socket_last_error();
                    echo "socket_write() - SENDTO - failed. reason: " . socket_strerror($errorcode) . "\n";
                }
                //Para desenvolver: validação de receção...

                $resposta = socket_recvfrom($socket, $input, 512, 0, $ipserver, $portserver) < 0;
                echo "Resposta do servidor: " . $resposta;
            }

            echo "Turn Off socket...\n";
            socket_close($socket);
            echo "Turn Off OK\n";
            break;

        case 2:
            while ($sair!=true) {
                $socket = socket_create(AF_INET,SOCK_STREAM,SOL_TCP);
                if($socket<0){
                    echo  "socket_create() ERRO: ".socket_strerror($socket)."\n";
                } else {
                    //echo "socket create OK.\n";
                }

                //echo  "A ligar ao servidor '$ip' na porta '$port'...\n";
                $result = socket_connect($socket,$ip,$port);
                if ($result <0 ){
                    echo "socket_connect() ERRO: ($result)".socket_strerror($result)."\n";
                } else{
                  //  echo  "Ligação (connect) OK\n";
                }

                $Mensagem = readline("$User: ");

                $in = $User.": $Mensagem\r\n";
                $out='';

                if(!socket_write($socket,$in,strlen($in))){
                    echo "socket_write() failed. reason: ".socket_strerror($socket)."\n";
                }else{
                    //echo "Mensagem enviada ao servidor com sucesso!\n";
                    //echo "Mensagem enviada: $in \n";
                }

                while ($out = socket_read($socket,8192)){
                    //echo "Receive Server Return Message Succesfully!\n:"
                    //echo  "Received Message: ",$out;
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
