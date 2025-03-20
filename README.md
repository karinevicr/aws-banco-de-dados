## Introdução 

&nbsp;&nbsp;&nbsp;&nbsp;Nesta atividade, foi desenvolvida uma aplicação web integrada a um banco de dados utilizando **AWS** e **MariaDB**. A aplicação foi criada com base no [tutorial oficial da AWS](https://docs.aws.amazon.com/AmazonRDS/latest/UserGuide/TUT_WebAppWithRDS.html), que fornece um guia detalhado para a construção de uma aplicação web com Amazon RDS.

&nbsp;&nbsp;&nbsp;&nbsp;Durante o desenvolvimento, foram seguidos rigorosamente os passos do tutorial, garantindo a implementação correta das funcionalidades e a adoção das melhores práticas. Isso incluiu a configuração do banco de dados e a integração com a infraestrutura da AWS.

### Configuração AWS

&nbsp;&nbsp;&nbsp;&nbsp;O Amazon EC2 foi utilizado para hospedar a aplicação web desenvolvida em PHP (Codigo encontrado no SamplePage.php). O EC2 é um serviço que permite criar e gerenciar máquinas virtuais (instâncias) na nuvem, oferecendo flexibilidade e escalabilidade para rodar aplicações. Foi criada uma instância EC2 com o sistema operacional Amazon Linux 2. A instância foi configurada com um par de chaves SSH para acesso seguro.

&nbsp;&nbsp;&nbsp;&nbsp;O Amazon RDS foi utilizado para gerenciar o banco de dados MariaDB, que armazena os dados da aplicação. A aplicação PHP foi configurada para se conectar ao banco de dados MariaDB utilizando o endpoint do RDS, nome do banco de dados, usuário e senha (dbinfo.inc).

### Revisão

&nbsp;&nbsp;&nbsp;&nbsp;A revisão dos textos e codigo foi feito usando [Chat gpt](https://chatgpt.com/), [DeepSeek](https://chat.deepseek.com/) e [Claude.ai](https://claude.ai)

### Link do video

O link do video pode ser encontrado aqui: https://youtu.be/cjCkcBZm2Io