Configurado o docker considerando a utilização do Nginx face ao Apache devido a sua capacidade superior de lidar com solicitações simultâneas.

Foi considerado uma arquitetura API-First e acesso público às APIs, com limite de 60 requisições por minuto, além disso, apenas rotas diretamente para as APIs são permitidas. Na solução não foi utilizado banco de dados mas existe a possibilidade de inclusão do BD posteriormente. 

Foi suprimido a utilização de cache (nessa aplicação temos arquivos fixos, cálculo simples e a ausência do bd).

A estrutura do controller e do gerenciador de rotas foi pensada visando a possibilidade de versionamento das apis.

Os  arquivos json forma armazenados em resources/app/private/json.

A lógica da api foi elaborada conforme solicitado.

Não foi realizado testes automatizados devido a simplicidade da aplicação.