<?php
set_time_limit(0);

$ip='127.0.0.1';
$port=42069;
$msg="Mensagem UDP do servidor";

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
?>