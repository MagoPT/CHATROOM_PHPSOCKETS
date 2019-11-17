<?php
set_time_limit(0);

$cls = chr(27).chr(91).'H'.chr(27).chr(91).'J'; //codigo equivalente ao cls
echo $cls;
$art = '  
      _=====_                               _=====_
     / _____ \                             / _____ \
   +.-\'_____\'-.---------------------------.-\'_____\'-.+
  /   |     |  \'.        LANEIROS       .\'  |  _  |   \
 / ___| /|\ |___ \                     / ___| /_\ |___ \
/ |      |      | ;  __           _   ; | _         _ | ;
| | <---   ---> | | |__|         |_:> | ||_|       (_)| |
| |___   |   ___| ; CHAT         ROOM ; |___       ___| ;
|\    | \|/ |    /  _     ___      _   \    | (X) |    /|
| \   |_____|  .\',\'" "\', |___|  ,\'" "\', \'.  |_____|  .\' |
|  \'-.______.-\' /       \SERVER/       \  \'-._____.-\'   |
|               |       |------|       |                |
|              /\       /      \       /\               |
|             /  \'.___.\'        \'.___.\'  \              |
|            /                            \             |
 \          /                              \           /
  \________/                                \_________/
                   '; //Desenho ASCII servidor
echo $art."\n\n";
$ip = getHostByName(getHostName()); //Automaticamente atribui o IP do servidor
$msg="Bem-vindo ao servidor dos laneiros\n"; //Mensagem de boas vindas
$port=readline("Porta: ");  //Inpout de porta
//Declaração de variaveos
$protocolo = null;
$sair = false;
$buf="";
$i=0;
$sv="a";
$client="Bem-vindo ao servidor dos Laneiros";
$conversa=[];

//Criação de Funções
function protocolo()    //Menu Inicial
{
    echo("Por favor escolha o protocolo");
    echo("\nProtocolo UDP - 1");
    echo("\nProtocolo TCP - 2");
    echo("\nSair          - 3\n");
    $protocolo = readline("Protocolo: \n");
    return $protocolo;
}

function socket_CreateBind($protocolo,$ip,$port) //socketBind
{
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

function emoji_badwords($str) //Filtro BadWords
{
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
        else if($str_explode[$i] == "fds"){
            $str_explode[$i] = "f.s(BadWord)";
        }
        else if($str_explode[$i] == "fodasse"){
            $str_explode[$i] = "f.....e(BadWord)";
        }

        $output=$output.$str_explode[$i]." ";
    }
    return $output."\n";
}


while ($sair !=true) { //Inicio do Ciclo do Programa
    echo $cls;  //reseta a tela
    switch ($protocolo) { //Verifica o valor do Protocolo
        case 1: //caso o porotocolo seja UDP

            //conexão ao servidor via UDP
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
                try{
                    $input = socket_recvfrom($sock,$buf,512,0,$ip,$port); //Receber pacotes do cliente
                    if($buf=="r"){ //caso o cliente queira dar refresh a sua tela
                        $send = $cls . "|------------------------------|\n"; //Inicio da sting da mensagem
                        $ze = array_reverse($conversa); //reverse ao array conversa
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

                    if($buf=="b"){ //Caso o cliente queira voltar atras na conversa
                        $send = $cls . "|------------------------------|\n";
                        $ze = array_reverse($conversa);
                        if (sizeof($conversa) < 25) {
                            for ($a = 0; $a < sizeof($conversa);$a++) {
                                $send = $send . $conversa[$a];
                            }
                        }else{
                            for ($a = 25; $a >= 5;$a--) {
                                $send = $send . $ze[$a];
                            }
                        }
                    }
                    elseif($buf=="close"){ //Caso o cliente queira fechar o servidor
                        $sv="b";
                        $sair=true;
                        socket_sendto($sock,"servidor encerrado\n", 100,0,$ip,$port);
                    }
                    else { //Caso o cliente envie uma mensagem
                        $final = emoji_badwords($buf); //filtrar mensagem
                        echo $final; //echo da mensagem

                        array_push($conversa, $final); //Junção da mensagem ao historico

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
                    $send= $send."|______________________________|\n"; //finalização da mensagem
                    socket_sendto($sock,$send, strlen($send),0,$ip,$port); //Envio da mensagem final para o cliente
                } catch (Exception $ex){
                    echo $ex;
                }
            }
            socket_close($sock); //Fechar o spcket
            //socket_shutdown($sock);
            break;//acaba o ciclo UDP

        case 2: //Caso o Protocolo seja TCP

            try{

                $sock = socket_CreateBind($protocolo,$ip,$port); //Criação do bind

                if(($ret = socket_listen($sock,4))<0){ //verificação da ligação
                    echo "socket_listen() ERRO :".socket_strerror($ret)."\n";
                } else{
                    echo "socket listen OK\n";
                }
                echo "\nIP: ".$ip;
                echo "\nPorta: ".$port;
                echo "\n\n«« Servidor à espera de ligações »» \n";
            } catch(Exception $ex){
                echo $ex;
            }
            while($sv=="a")
            {
                try{
                    $spawn[++$i] = socket_accept($sock) or die("Could not accept incoming connection\n"); //Verificação da conexão ao client
                    $input = socket_read($spawn[$i],1024); //Recebimento da mensagem do utilizador
                    if($input=="r"){ //caso o utilizador queira somente dar refresh a sua tela
                        $send = $cls . "|------------------------------|\n"; //Inicio da sting que sera enviada ao cliente
                        $ze = array_reverse($conversa);
                        if (sizeof($conversa) < 20) {
                            for ($a = 0; $a < sizeof($conversa);$a++) {
                                $send = $send . $conversa[$a]; //concactenação da sting que sera enviada ao cliente
                            }


                        }else{
                            for ($a = 20; $a >= 0;$a--) {
                                $send = $send . $ze[$a];
                            }
                        }
                    }
                    elseif($input=="b"){ //caso o utilizador queira verificar o historico da conversa
                        $send = $cls . "|------------------------------|\n";//Inicio da sting que sera enviada ao cliente
                        $ze = array_reverse($conversa);
                        if (sizeof($conversa) < 25) {
                            for ($a = 0; $a < sizeof($conversa);$a++) {
                                $send = $send . $conversa[$a];//concactenação da sting que sera enviada ao cliente
                            }


                        }else{
                            for ($a = 25; $a >= 5;$a--) {
                                $send = $send . $ze[$a];
                            }
                        }
                    }
                    elseif($input=="close"){ //caso o cliente queira fechar o cliente
                        $sv="b";
                        $sair=true;
                        socket_write($spawn[$i], "servidor encerrado\n", 9080);
                    }

                    else { //caso o utilizador envie uma mensgaem a ser registada
                        $final = emoji_badwords($input); //filtrar mensagem
                        echo $final; //echo da mensagem processada

                        array_push($conversa, $final); //junção da mensagem ao array existente

                        $send = $cls . "|------------------------------|\n"; //Inicio da string a ser enviada ao cliente
                        $ze = array_reverse($conversa); //reverso do array
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
                $send= $send."|______________________________|\n"; //fim da mensagem
                socket_write($spawn[$i], $send, 9080); //envio da mensagem ao cliente
                socket_close($spawn[$i]); //fechar socke
                } catch(Exception $ex){
                    echo $ex;
                }      
            }
            socket_close($sock);
            //socket_shutdown($sock);
            break; //Fim do ciclo TCP

        case 3: //Caso o utilizador queira sair
            $sair = true;
            break;

        default: //Escolha default
            $protocolo = protocolo();
            break;
    }
}
?>