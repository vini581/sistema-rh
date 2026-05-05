# Sistema de RH

<div align="center">
  <p><em>Um sistema open-source para gestão de recursos humanos e cálculos de folha de pagamento.</em></p>
  
  [![PHP Version](https://img.shields.io/badge/PHP-8.3-777BB4.svg?style=flat-square&logo=php)](https://www.php.net/)
  [![Laravel](https://img.shields.io/badge/Laravel-11.x-FF2D20.svg?style=flat-square&logo=laravel)](https://laravel.com)
  [![Tailwind CSS](https://img.shields.io/badge/Tailwind-CSS-38B2AC.svg?style=flat-square&logo=tailwind-css)](https://tailwindcss.com/)
  [![Docker](https://img.shields.io/badge/Docker-ready-2496ED.svg?style=flat-square&logo=docker)](https://www.docker.com/)
  [![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg?style=flat-square)](LICENSE)
</div>

---

## O Projeto

O Sistema de RH foi desenvolvido para resolver um gargalo clássico: a burocracia de fechar a folha de pagamento todo mês sem depender de planilhas desorganizadas ou de softwares legados engessados. A ideia do projeto é dar controle total ao gestor, unindo uma interface limpa e moderna a um backend construído para não falhar. 

O principal diferencial da arquitetura é o motor de regras versionado por data. Na prática, isso significa que você pode atualizar taxas de impostos, regras sindicais ou percentuais de hora extra a qualquer momento, e o sistema entende a linha do tempo. Isso garante que recalcular uma folha antiga sempre usará a matemática exata daquela época, sem corromper o histórico financeiro da empresa.

## Stack Utilizada

- **Backend:** PHP 8.3 com Laravel 11
- **Banco de Dados:** MySQL (valores monetários armazenados como inteiros/centavos para evitar problemas de ponto flutuante)
- **Frontend:** Laravel Blade, Alpine.js, Livewire e Tailwind CSS
- **Infraestrutura:** Docker + Nginx + PHP-FPM

## Funcionalidades Principais

### Administração (Gestores de RH)

* **Motor de Cálculos Customizável:** Permite a configuração de descontos fixos e verbas adicionais por funcionário. 
* **Gestão de Banco de Horas e DSR:** Processamento em lote de dias úteis, cruzando registros de ponto com a agenda de feriados e dias de descanso semanal remunerado. Faltas não justificadas (sem atestado ou férias) geram deduções automaticamente.
* **Múltiplos Tipos de Folha:** Suporta a geração de adiantamentos quinzenais e o fechamento da folha mensal, integrando as deduções de forma sequencial.
* **Busca Global (Command Palette):** Atalho (`Ctrl + K` / `Cmd + K`) para busca rápida de funcionários, holerites e atestados, otimizando a navegação.
* **Otimização de Queries:** Os dashboards e processamentos em lote utilizam Eager Loading e subqueries nativas para evitar o problema de N+1 consultas ao banco, mantendo o tempo de resposta baixo mesmo com alto volume de dados.

### Portal do Funcionário

* **Autoatendimento:** Funcionários possuem uma aba de Perfil onde podem atualizar seus próprios dados cadastrais e de contato. Alterações disparam notificações para a equipe de RH.
* **Painel de Holerites:** Acesso individual para consulta e download do demonstrativo de pagamento detalhado.
* **Gestão de Ausências:** Histórico de atestados enviados e visualização de férias agendadas.
* **Suporte a Temas:** Interface moderna construída com Tailwind CSS, incluindo suporte nativo aos modos Claro e Escuro.

---

## Como usar o sistema

### Fluxo de Uso Administrativo

1. **Cadastros Iniciais:** No menu "Funcionários", crie um novo registro preenchendo os dados pessoais. Opcionalmente, pode-se enviar uma foto de perfil. Em seguida, na aba "Configurações Financeiras" do perfil, defina o salário base e eventuais regras individuais.
2. **Parâmetros do RH:** No menu "Configurações RH", cadastre as alíquotas tributárias gerais (INSS, FGTS, etc.). Elas são atreladas a uma data de vigência.
3. **Feriados e Escalas:** Mantenha o calendário de feriados atualizado para o correto cálculo de horas extras e DSR.
4. **Validação de Atestados:** Os atestados enviados pelos funcionários ficam pendentes até aprovação da gestão, que automaticamente abonará as faltas correspondentes no fechamento do ponto. O sistema também valida bloqueios de sobreposição com férias.
5. **Processamento da Folha:** Ao final do período, selecione o mês e rode o cálculo. Os valores podem ser ajustados manualmente antes do fechamento definitivo. O fechamento utiliza *database locks* para evitar concorrência.

### Fluxo do Funcionário

1. **Ponto e Frequência:** O funcionário registra os horários de entrada, almoço e saída.
2. **Atestados:** Permite o envio de arquivos digitalizados (PDF/Imagens) informando os dias de afastamento.
3. **Férias:** O colaborador pode sugerir o período de férias, que entrará em uma fila para aprovação do gestor.
4. **Perfil:** O próprio usuário pode modificar sua senha e atualizar informações complementares (endereço, telefone de emergência).

---

## Instalação e Configuração
 
O projeto foi estruturado para rodar via **Docker**, que é a forma mais simples e rápida de subir o ambiente completo (PHP, MySQL, Nginx e dependências) sem precisar configurar nada na sua máquina local.

Se você preferir rodar de forma nativa (com PHP e MySQL instalados no seu Windows/Mac/Linux), existe um guia para isso logo abaixo.
 
### Instalação Recomendada (Docker)
 
O Docker cuida de todo o "trabalho sujo". No primeiro build, ele já instala o Composer, as dependências do Node, compila o CSS e o JS, gera a chave de segurança e até cria as tabelas do banco de dados com dados de teste.

#### Passo 0: Pré-requisitos (Docker)

Se você ainda não tem o Docker instalado, aqui está o caminho mais rápido:

*   **Windows:** Baixe o [Docker Desktop](https://www.docker.com/products/docker-desktop/). Durante a instalação, ele vai pedir para ativar o WSL 2 — pode aceitar sem medo. Lembre de reiniciar o PC depois.
*   **macOS:** Baixe o [Docker Desktop](https://www.docker.com/products/docker-desktop/) (escolha a versão correta se o seu chip for Intel ou Apple M1/M2/M3).
*   **Linux (Ubuntu/Debian):** Execute os comandos abaixo para instalar o Docker Engine oficial (docker-ce) e o plugin Docker Compose:

```bash
# 1. Atualizar a lista de pacotes
sudo apt update

# 2. Instalar dependências
sudo apt install -y apt-transport-https ca-certificates curl software-properties-common

# 3. Adicionar a chave GPG do Docker
curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo gpg --dearmor -o /usr/share/keyrings/docker-archive-keyring.gpg

# 4. Adicionar o repositório estável do Docker
echo "deb [arch=$(dpkg --print-architecture) signed-by=/usr/share/keyrings/docker-archive-keyring.gpg] https://download.docker.com/linux/ubuntu $(lsb_release -cs) stable" | sudo tee /etc/apt/sources.list.d/docker.list > /dev/null

# 5. Atualizar a lista de pacotes novamente
sudo apt update

# 6. Instalar o Docker Engine e o Docker Compose Plugin
sudo apt install -y docker-ce docker-ce-cli containerd.io docker-compose-plugin

# 7. Adicionar seu usuário ao grupo docker (substitua $USER se necessário)
sudo usermod -aG docker $USER

# 8. Aplicar as mudanças de grupo no terminal atual
newgrp docker
```

**1. Clone o repositório:**
```bash
git clone https://github.com/vini581/sistema-rh.git
```
```bash
cd sistema-rh
```
 
**2. Suba os containers:**
Na primeira vez, use o comando abaixo para construir as imagens (isso pode demorar alguns minutos dependendo da sua internet):
```bash
docker compose up --build
```
 
**3. Acesso:**
Quando o terminal parar de rodar mensagens e mostrar que o banco está pronto, você já pode acessar:
* **URL:** `http://localhost:8000`
* **Usuário Admin:** `admin@gmail.com`
* **Senha:** `654321`

---

### Uso no Dia a Dia (Docker)

Depois que você já fez a primeira instalação, não precisa mais do comando `--build`. Para ligar o sistema sempre que for trabalhar, basta rodar:

```bash
docker compose up -d
```
O `-d` serve para o terminal não ficar preso. Você roda o comando, fecha a janela e o sistema continua rodando no seu navegador. Para desligar tudo, use `docker compose down`.

---
 
### Instalação Manual (Sem Docker)
 
Requisitos:
- PHP 8.3 (extensões: `pdo_mysql`, `mbstring`, `gd`, `zip`, etc.)
- Composer e Node.js
- MySQL 8+
 
**1. Instale as dependências:**
```bash
composer install
npm install
```
 
**2. Configure o banco de dados no `.env`:**
```bash
cp .env.example .env
```
Edite as variáveis `DB_DATABASE`, `DB_USERNAME` e `DB_PASSWORD`.
 
**3. Inicialize a aplicação:**
```bash
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
```
 
**4. Inicie os servidores:**
```bash
php artisan serve
npm run dev
```
 
---
 
## Estrutura do Código
 
- `/app/Models`: Modelos do Eloquent e *Casts* (conversão de centavos do BD para decimais em tela).
- `/app/Services`: Serviços injetados que concentram as lógicas pesadas (ex: `PayrollCalculator`, `CalendarService`).
- `/database/migrations`: Definição de schemas e foreign keys.
- `/docker`: Configuração do Nginx, FPM e script de inicialização do container.

## Roadmap de Evolução

O projeto está em constante evolução. Abaixo estão as grandes frentes de desenvolvimento que planejei para elevar o sistema ao nível de uma solução de mercado completa:

*   **Escalabilidade com Background Jobs (Filas):** Para suportar empresas com milhares de funcionários, o objetivo é migrar o processamento pesado da folha e o disparo de comunicações para o segundo plano (usando Redis/Horizon). Isso garante que o sistema nunca sofra com *timeouts*, processando grandes volumes de dados de forma assíncrona.
*   **Módulo de Business Intelligence (BI):** Transformar dados em estratégia. O plano é construir um dashboard analítico avançado que mostre gráficos de evolução de custos, tendências de horas extras e indicadores de turnover (rotatividade), ajudando o RH a tomar decisões baseadas em números reais.
*   **Cofre Digital (Gestão Paperless):** Implementar uma central de armazenamento seguro para documentos escaneados (RG, CPF, CNH, Comprovantes). Integrando com serviços de nuvem (AWS S3), a ideia é extinguir as pastas físicas e deixar toda a documentação oficial a um clique de distância.
*   **Comunicação Inteligente e Integrações:** Automatizar o envio de holerites em PDF e avisos de férias diretamente para o e-mail ou WhatsApp do funcionário. Além disso, expor uma API RESTful para que o sistema consiga conversar com softwares contábeis ou de BI externos.

---

## Licença

Este projeto é open-source licenciado sob os termos da [Licença MIT](LICENSE).
