# Sistema de RH

<div align="center">
  <p><em>Um sistema prático para gestão de recursos humanos e cálculos de folha de pagamento.</em></p>
  
  [![PHP Version](https://img.shields.io/badge/PHP-8.3-777BB4.svg?style=flat-square&logo=php)](https://www.php.net/)
  [![Laravel](https://img.shields.io/badge/Laravel-11.x-FF2D20.svg?style=flat-square&logo=laravel)](https://laravel.com)
  [![Tailwind CSS](https://img.shields.io/badge/Tailwind-CSS-38B2AC.svg?style=flat-square&logo=tailwind-css)](https://tailwindcss.com/)
  [![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg?style=flat-square)](LICENSE)
</div>

---

## O Projeto

O foco desse sistema é tirar o peso da burocracia das costas do RH e automatizar todo o fechamento da folha de pagamento. A verdadeira cereja do bolo é o motor inteligente de regras com viagem no tempo: o sistema não apenas calcula salários, mas entende as mudanças da lei ao longo dos meses. Se o governo mudar a alíquota do INSS hoje, você atualiza a configuração e ele garante que todos os pagamentos antigos fiquem intactos, aplicando a matemática exata e à prova de falhas para cada período.

## Stack Utilizada

- **Backend:** PHP 8.3 com Laravel 11
- **Banco de Dados:** MySQL (Todos os valores financeiros são salvos em centavos como números inteiros, fugindo de problemas com ponto flutuante)
- **Frontend:** Laravel Blade, Alpine.js e Tailwind CSS

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

## Instalação e Configuração

Para executar o sistema em seu ambiente (Windows, Linux ou macOS), é necessário ter instalado o **PHP 8.3**, **Composer**, **Node.js** e um servidor de banco de dados **MySQL**.

### 1. Clone do Repositório
Realize o clone do projeto e acesse o diretório:
```bash
git clone https://github.com/vini581/sistema-rh.git
cd sistema-rh
```

### 2. Instalação de Dependências
Instale as dependências do backend (Composer) e do frontend (NPM):
```bash
composer install
npm install
```

### 3. Configuração de Ambiente
Crie o arquivo de configuração `.env` a partir do modelo e gere a chave única da aplicação:
```bash
cp .env.example .env
php artisan key:generate
```
> **Nota:** Após criar o arquivo, configure as credenciais de acesso ao seu banco de dados no arquivo `.env`.

### 4. Banco de Dados e Storage
Execute as migrations para criar as tabelas (com dados iniciais de teste) e configure o link simbólico para os arquivos de upload:
```bash
php artisan migrate --seed
php artisan storage:link
```

### 5. Execução da Aplicação
Inicie o servidor de desenvolvimento do Laravel e o compilador do Vite em terminais separados:

*   **Servidor Backend:** `php artisan serve`
*   **Servidor Frontend:** `npm run dev`

Após iniciar os serviços, o sistema estará acessível em `http://localhost:8000`.

## Estrutura do Código

Para quem quiser dar uma olhada no código, a estrutura segue o padrão do Laravel, mas vale destacar:

- `/app/Models`: Onde ficam os modelos e a lógica de *Casts* (onde convertemos os centavos do banco para reais na tela).
- `/app/Services`: Extraímos algumas regras de negócio mais complexas dos Controllers para cá.
- `/database/migrations`: Todas as regras de relacionamento e constraints estão bem definidas aqui.

## Próximos Passos (Roadmap)

Algumas ideias e melhorias que estão no radar para o futuro do projeto:

-  **Testes Automatizados:** Implementar testes com PestPHP, principalmente nas regras de cálculo da folha.
-  **Dashboard Gerencial:** Criar uma tela inicial com gráficos resumindo os custos do mês.
-  **Envio de Holerite por E-mail:** Rotina para disparar o PDF automaticamente quando a folha for fechada.
-  **Melhorar o Layout Mobile:** Dar um tapa no responsivo da área do funcionário para ficar 100% no celular.
-  **Logs de Auditoria:** Gravar o histórico de quem alterou salários ou cadastros para ter um controle melhor.

## Licença

Este projeto está licenciado sob a licença MIT - consulte o arquivo [LICENSE](LICENSE) para obter detalhes.
