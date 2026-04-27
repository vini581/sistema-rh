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

## Como rodar o projeto

Você vai precisar do **PHP 8.3+**, **Composer**, **Node.js** e do **MySQL** rodando na sua máquina.

1. Clone o repositório:
```bash
git clone https://github.com/vini581/sistema-rh.git
cd sistema-rh
```

2. Instale as dependências do PHP e do Node:
```bash
composer install
npm install
```

3. Crie o `.env` e gere a chave do Laravel:
```bash
cp .env.example .env
php artisan key:generate
```
*Não se esqueça de colocar os dados do seu banco de dados no `.env`.*

4. Rode as migrations com os dados de teste e crie o link dos arquivos:
```bash
php artisan migrate --seed
php artisan storage:link
```

5. Suba a aplicação:
```bash
php artisan serve
```
E em outro terminal, rode o Vite para compilar o CSS/JS:
```bash
npm run dev
```

Pronto, o sistema vai estar rodando em `http://localhost:8000`.

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
