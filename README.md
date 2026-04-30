# Sistema de RH

<div align="center">
  <p><em>Um sistema prático para gestão de recursos humanos e cálculos de folha de pagamento.</em></p>
  
  [![PHP Version](https://img.shields.io/badge/PHP-8.3-777BB4.svg?style=flat-square&logo=php)](https://www.php.net/)
  [![Laravel](https://img.shields.io/badge/Laravel-11.x-FF2D20.svg?style=flat-square&logo=laravel)](https://laravel.com)
  [![Tailwind CSS](https://img.shields.io/badge/Tailwind-CSS-38B2AC.svg?style=flat-square&logo=tailwind-css)](https://tailwindcss.com/)
  [![Docker](https://img.shields.io/badge/Docker-ready-2496ED.svg?style=flat-square&logo=docker)](https://www.docker.com/)
  [![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg?style=flat-square)](LICENSE)
</div>

---

## O Projeto

O foco desse sistema é tirar o peso da burocracia das costas do RH e automatizar todo o fechamento da folha de pagamento. A verdadeira cereja do bolo é o motor inteligente de regras com viagem no tempo: o sistema não apenas calcula salários, mas entende as mudanças da lei ao longo dos meses. Se o governo mudar a alíquota do INSS hoje, você atualiza a configuração e ele garante que todos os pagamentos antigos fiquem intactos, aplicando a matemática exata e à prova de falhas para cada período.

## Stack Utilizada

- **Backend:** PHP 8.3 com Laravel 11
- **Banco de Dados:** MySQL (Todos os valores financeiros são salvos em centavos como números inteiros, fugindo de problemas com ponto flutuante)
- **Frontend:** Laravel Blade, Alpine.js e Tailwind CSS
- **Infraestrutura:** Docker + Nginx + PHP-FPM

## O que o sistema faz?

### Para quem é do RH (Gestores)

* **Organizar a Equipe:** Cadastrar a galera, definir carga horária, salário base e subir a foto de perfil. Se não tiver foto, o sistema gera um avatar com as iniciais do nome.
* **Manter as Leis em Dia:** Tem uma área só para configurar impostos (INSS, FGTS, etc). Tudo é salvo com data de início (versionamento), garantindo que os cálculos de meses anteriores fiquem intocados.
* **Controlar Férias e Atestados:** Na hora de lançar uma falta, o backend valida se as datas não estão batendo. O sistema não deixa ninguém lançar um atestado médico no meio das férias de um funcionário, por exemplo.
* **Rodar a Folha:** Processar os pagamentos do mês cruzando o salário base com horas extras, DSR e descontos de forma automatizada.
* **Fechamentos Seguros:** Na hora que o RH clica para fechar a folha, o sistema usa *locks* no banco de dados. Isso impede que duas pessoas processem o mesmo pagamento ao mesmo tempo e evitem falhas ou duplicações.

### Para os Funcionários

* **Painel de Holerites:** Cada colaborador tem seu próprio login para listar todos os contracheques que já recebeu. Dá para ver detalhado o que é bônus, o que é hora extra e o que é desconto.
* **Histórico de Ausências:** Dá para conferir os atestados que já foram entregues e ver quando serão as próximas férias programadas pelo RH.
* **Perfil Próprio:** Uma área para conferir seus dados de contrato e trocar a própria foto de perfil para manter a conta atualizada.

---

## Como usar o sistema

### Passo a passo para o Gestor (RH / Admin)

Ao acessar o sistema com a conta de administrador, o gestor é direcionado ao **Dashboard Gerencial**, que apresenta um resumo das informações do mês — funcionários ativos, folhas processadas e ocorrências pendentes. A partir dali, o fluxo de trabalho segue esta ordem:

**1. Cadastrar e configurar funcionários:** Acesse o menu **Funcionários** e clique em "Novo Funcionário". Preencha os dados pessoais (nome, e-mail, CPF, data de admissão), defina o salário base e faça o upload da foto de perfil, caso queira — se nenhuma foto for enviada, o sistema gera automaticamente um avatar com as iniciais do nome. Com o funcionário criado, acesse a aba **Jornada** dentro do perfil dele para definir a carga horária semanal (dias e horários de entrada/saída). Em seguida, acesse a aba **Configurações Financeiras** para registrar verbas e descontos individuais, como benefícios, adiantamentos ou descontos contratuais específicos; cada configuração recebe uma data de início para que o histórico financeiro do colaborador nunca seja sobrescrito.

**2. Configurar os parâmetros do RH:** No menu **Configurações RH**, cadastre e mantenha atualizadas as regras tributárias e trabalhistas que se aplicam a toda a empresa — tabelas de INSS, alíquotas de FGTS, percentual de DSR, percentual de hora extra e outros parâmetros. Cada registro é salvo com uma data de vigência, o que garante que, se uma alíquota mudar no meio do ano, os fechamentos de meses anteriores continuem calculados com os valores que vigoravam naquele período (versionamento com viagem no tempo).

**3. Lançar feriados:** Acesse o menu **Feriados** e cadastre as datas não úteis do calendário — nacionais, estaduais ou municipais. O motor de cálculo da folha usa esses registros para distinguir dias trabalhados normais de dias que geram pagamento em dobro ou DSR.

**4. Gerenciar atestados médicos:** Vá em **Atestados** para ver todos os atestados enviados pelos funcionários. O gestor pode aprovar ou reprovar cada um; o sistema valida automaticamente se o período do atestado conflita com férias já agendadas do colaborador, impedindo lançamentos inconsistentes. Atestados aprovados são descontados da folha como faltas justificadas, sem impactar o DSR.

**5. Processar a folha de pagamento:** Acesse o menu **Folha de Pagamento** e selecione o mês de referência. Clique em **Calcular** — o sistema cruza o salário base de cada funcionário com as horas extras registradas, aplica os descontos de INSS, FGTS e outras verbas configuradas, e gera o holerite individual de cada colaborador. Antes de fechar, é possível revisar e editar os valores calculados de qualquer funcionário. Quando tudo estiver conferido, clique em **Fechar Folha**: o sistema usa bloqueios (*locks*) no banco de dados para garantir que nenhuma outra sessão possa processar o mesmo período ao mesmo tempo, evitando duplicações ou falhas. Após o fechamento, a folha fica imutável e visível para cada funcionário no próprio painel deles.

**6. Consultar relatórios:** Na seção **Relatórios**, o gestor tem uma visão consolidada dos dados — custos totais de folha por mês, histórico de ausências da equipe e demais informações gerenciais para apoiar a tomada de decisão.

---

### Passo a passo para o Funcionário

O funcionário acessa o sistema com o e-mail e a senha cadastrados pelo RH e é direcionado ao seu **Painel Pessoal**, onde vê um resumo do próprio mês: últimas batidas de ponto, saldo de horas e status das solicitações pendentes. A partir dali, pode fazer tudo isso:

**1. Registrar ponto:** No menu **Ponto**, o funcionário encontra o botão de **Bater Ponto** para registrar entrada, saída e intervalos. O sistema guarda o horário exato de cada registro e disponibiliza o **Histórico de Ponto**, onde é possível consultar todos os lançamentos do mês com os totais de horas trabalhadas, horas extras acumuladas e eventuais divergências.

**2. Consultar holerites:** Em **Meus Holerites**, o funcionário vê a lista de todos os contracheques já fechados pelo RH. Ao clicar em qualquer mês, o holerite é exibido de forma detalhada — com discriminação de cada verba (salário base, horas extras, DSR, bônus) e cada desconto (INSS, FGTS, adiantamentos), tornando completamente transparente como o valor líquido foi calculado.

**3. Solicitar e acompanhar férias:** No menu **Minhas Férias**, o funcionário pode solicitar o período de férias desejado informando a data de início e o número de dias. A solicitação fica registrada e visível para o RH aprovar ou ajustar. Também é possível consultar o histórico de férias já tiradas e o saldo de dias disponíveis.

**4. Enviar atestados médicos:** Em **Meus Atestados**, o funcionário pode registrar um atestado médico informando o período de afastamento e fazendo o upload do documento digitalizado. O atestado fica com status "Pendente" até que o gestor o aprove ou reprove. O histórico de todos os atestados enviados fica disponível nessa mesma tela, com o status atualizado de cada um.

**5. Gerenciar o perfil:** No canto superior da tela, ao clicar no avatar ou nome, o funcionário acessa a área de **Perfil**, onde pode visualizar seus dados contratuais (cargo, data de admissão, carga horária) e atualizar sua foto de perfil para personalizar a conta.

---

## Instalação e Configuração

O projeto usa **Docker**, o que significa que você não precisa instalar PHP, MySQL ou Node.js na sua máquina. O único requisito é o Docker.

### Pré-requisito: Docker

| Sistema | Instalação |
|---|---|
| Windows / macOS | Baixe em [docker.com/products/docker-desktop](https://www.docker.com/products/docker-desktop/) e instale normalmente |
| Ubuntu / Debian | `sudo apt install docker.io docker-compose-plugin -y` |
| Fedora / RHEL | `sudo dnf install docker docker-compose-plugin -y` |

> **Windows:** após instalar o Docker Desktop, ative o WSL 2 quando solicitado e reinicie o computador.

---

### 1. Clone do Repositório

```bash
git clone https://github.com/vini581/sistema-rh.git
cd sistema-rh
```

### 2. Suba o projeto

Abra o terminal dentro da pasta `docker/` e execute:

```bash
docker compose up --build
```

Na primeira vez o processo leva entre 3 e 10 minutos — o Docker vai baixar as imagens, instalar as dependências PHP e Node.js, compilar os assets e rodar as migrations automaticamente.

Quando aparecer a mensagem abaixo no terminal, o sistema está pronto:

```
✅ Sistema pronto em http://localhost:8000
   📧 admin@gmail.com  |  🔑 654321
```

### 3. Acesse o sistema

Abra o navegador e acesse **http://localhost:8000**.

Faça login com as credenciais do administrador criadas automaticamente pelo seeder:

| Campo | Valor |
|---|---|
| E-mail | `admin@gmail.com` |
| Senha | `654321` |

---

### Comandos úteis

```bash
# Subir em segundo plano (sem travar o terminal)
docker compose up --build -d

# Ver logs em tempo real
docker compose logs -f

# Parar os containers
docker compose down

# Reset completo (apaga e recria o banco de dados)
docker compose down -v && docker compose up --build

# Acessar o terminal do container
docker compose exec app bash

# Rodar comandos artisan
docker compose exec app php artisan migrate:fresh --seed
docker compose exec app php artisan tinker
```

---

### Acessar o banco de dados

Conecte com qualquer cliente MySQL (TablePlus, DBeaver, MySQL Workbench):

| Campo | Valor |
|---|---|
| Host | `127.0.0.1` |
| Porta | `3306` |
| Banco | `sistema_rh` |
| Usuário | `laravel` |
| Senha | `secret` |

---

### Instalação manual (sem Docker)

Se preferir rodar sem Docker, é necessário ter **PHP 8.3**, **Composer**, **Node.js 20** e **MySQL 8** instalados na máquina. Consulte o arquivo [REQUIREMENTS.md](REQUIREMENTS.md) para a lista completa de extensões e dependências necessárias.

```bash
composer install
npm install
cp .env.example .env
# Configure as credenciais do banco no .env
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
php artisan serve    # terminal 1
npm run dev          # terminal 2
```

---

## Estrutura do Código

Para quem quiser dar uma olhada no código, a estrutura segue o padrão do Laravel, mas vale destacar:

- `/app/Models`: Onde ficam os modelos e a lógica de *Casts* (onde convertemos os centavos do banco para reais na tela).
- `/app/Services`: Extraímos algumas regras de negócio mais complexas dos Controllers para cá.
- `/database/migrations`: Todas as regras de relacionamento e constraints estão bem definidas aqui.
- `/docker`: Arquivos de configuração do Nginx e entrypoint do container.

---

## Próximos Passos (Roadmap)

Algumas ideias e melhorias que estão no radar para o futuro do projeto:

-  **Testes Automatizados:** Implementar testes com PestPHP, principalmente nas regras de cálculo da folha.
-  **Dashboard Gerencial:** Criar uma tela inicial com gráficos resumindo os custos do mês.
-  **Envio de Holerite por E-mail:** Rotina para disparar o PDF automaticamente quando a folha for fechada.
-  **Melhorar o Layout Mobile:** Dar um tapa no responsivo da área do funcionário para ficar 100% no celular.
-  **Logs de Auditoria:** Gravar o histórico de quem alterou salários ou cadastros para ter um controle melhor.

---

## Licença

Este projeto está licenciado sob a licença MIT - consulte o arquivo [LICENSE](LICENSE) para obter detalhes.
