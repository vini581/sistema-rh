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
 
O ambiente padrão do projeto roda sobre Docker, simplificando as dependências.
 
### Via Docker
 
**1. Instale o Docker e o Docker Compose na sua máquina.**  
 
**2. Clone o repositório:**
 
```bash
git clone https://github.com/vini581/sistema-rh.git
cd sistema-rh
```
 
**3. Suba os containers:**
 
```bash
docker compose up --build
```
 
No primeiro build, o Composer e o NPM instalarão as dependências, e um *entrypoint script* criará a `APP_KEY`, rodará as *migrations* e populará o banco de dados.

**4. Acesso Padrão:**
Acesse `http://localhost:8000`
- E-mail: `admin@gmail.com`
- Senha: `654321`
 
### Comandos úteis (Docker)
 
```bash
# Rodar em background
docker compose up --build -d
 
# Visualizar logs
docker compose logs -f
 
# Derrubar os containers
docker compose down
 
# Derrubar e resetar volumes (apaga o banco)
docker compose down -v && docker compose up --build
 
# Rodar comandos do Artisan dentro do container
docker compose exec app php artisan migrate:fresh --seed
```
 
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

## Roadmap

- **Filas (Background Jobs):** Mover o fechamento da folha e envio de e-mails para processamento assíncrono (Redis/RabbitMQ).
- **Componentização com Livewire:** Refatorar formulários complexos para validação reativa e busca de CEP assíncrona.
- **Cofre Digital de Documentos:** Integração de storage cloud (S3) para retenção segura de documentos de admissão.
- **Logs de Auditoria:** Implementar event listeners para registrar mutações no salário base ou parâmetros do RH.
- **Testes Automatizados:** Cobertura de testes de unidade e feature (PestPHP) para garantir a estabilidade do motor de cálculo.

---

## Licença

Este projeto é open-source licenciado sob os termos da [Licença MIT](LICENSE).
