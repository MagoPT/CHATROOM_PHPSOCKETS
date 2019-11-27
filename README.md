**CHATROOM PHP SOCKETS**

O ChatRoom PHP é um projeto Open Source desenvolvido no âmbito académico com o objetivo final de implementar uma comunicação cliente-servidor em PHP quer utilizando o protocolo TCP ou UDP, comportamento geral, deveria se adaptar a um chat tradicional feito em WebSockets

**Limitações**

Não faz a gestão de vários clientes e não tem sincronização assíncrona

**Comandos Reconhecidos**

    -r -- Reload de tela
    -b -- Volta 5 mensagens atrás
    -q -- desliga o cliente
    -close -- desliga o servidor

**Objetivos Iniciais**

Projeto de implementação - comunicação cliente - servidor(sockets)
-> Implementar em PHP uma sala de chat segundo as seguintes especificações:

	Especificações do projeto
	
    - O servidor deve permitir a escolha do protocolo a utilizar bem como a porta de comunicação
    Objetivo não concluido : O servidor deve aceitar um máximo de 10 conexões em simultâneo, rejeitando as seguintes
    - O servidor deve, para cada mensagem recebida, mostrar na sua janela a atualização do estado das
    mensagens no chat, bem como enviar a todos os utilizadores registados a nova msg
    - Todos os clientes devem receber uma mensagem de boas vindas
    - Cada msg deve estar identificada com <hora><user>: msg
    - O cliente deve permitir a escolha do IP, Protocolo e Porta do servidor a ligar
    * Projeto a ser desenvolvido 2 a 2
    - O grafismo (servidor e cliente) é da responsabilidade do Grupo -> ASCII Art
    - A gestão de erros de comunicação deve ser contemplada quer no servidor, quer no cliente

Opcional:

	- Reconhecimento de emojis [... -> :smile: --> :-)]
	- História de com. (e navegação no mesmo)
	- Filtro 'Bad words'                    
	
**Realização**

Orientador:    
        
    Professor Miguel Sequeira

Desenvolvedores:
    
    Duarte Cruz
    Bernardo Generoso
                    
