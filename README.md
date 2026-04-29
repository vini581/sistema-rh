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