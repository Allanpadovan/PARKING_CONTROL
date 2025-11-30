# PARKING_CONTROL
Esse projeto foi desenvolvido em php 8.2 utilizando PSR-12 com composer aplicando os principios de Solid e boas praticas de clean code espero que gostem. 

This project was developed in PHP 8.2 using PSR-12 with Composer, applying SOLID principles and good clean code practices. I hope you enjoy it.
_________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________

Integrantes: 

Nome - RA
Ana Karla de Souza Moretão - 1986881
Allan França Padovan - 1986140
Lucas Gimenez - 1996567
_______________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________
Tecnologias e Pré-requisitos

PHP:* Versão 8.2 ou superior.
Composer:* Com autoload PSR-4.
Aplicação:*  PSR-12.

XAMPP / Servidor Web Local:* Para servir a aplicação via http://localhost/PARKING_CONTROL/public/.

Estrutura do Projeto (PSR-4 e Camadas)

A organização segue o padrão de camadas e o autoloading App\ via PSR-4:
_______________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________
Estrutura de Pastas 
src/
├── Application/              
│   └── ParkingService.php
├── Domain/                    
│   ├── Entity/
│   │   ├── Vehicle.php
│   │   └── ParkingRecord.php
│   ├── Interfaces/
│   │   ├── PricingStrategyInterface.php
│   │   └── ParkingRepositoryInterface.php
│   └── Pricing/               → Strategy Pattern
│       ├── BasePricingStrategy.php
│       ├── CarPricingStrategy.php
│       ├── BikePricingStrategy.php
│       └── TruckPricingStrategy.php
└── Infra/                     
    ├── Database/
    │   └── Connection.php
    └── Repository/
        └── SQLiteParkingRepository.php
_______________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________
Requisitos para rodar o projeto:
PHP versão 8.2 o gerenciador de dependências Composer
Xamp Instalado na maquina e estar com o Apache Start
Vá em Este computador , Sistema procure pela pasta XAMPP , clique em HTDOCS e arraste a pasta descompactada para HTDOCS
No seu Navegador com o projeto já baixado e inserido na Pasta Xamp/Htdocs , você deverá colocar http://localhost/PARKING_CONTROL/public/       , para rodar o projeto em seu navegador
_______________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________
Prints do Funcionamento:

Tela Inicial : 

Deve inserir uma placa seja ela de carro , moto ou Caminhão e registrar a entrada do veiculo quando quiser tirar os veiculos deverá colocar a mesma palca que deseja retirar.

<img width="683" height="725" alt="image" src="https://github.com/user-attachments/assets/bd949496-b52c-4f14-82d0-4fbc5b5ae78b" />

Tabela de Relatório Financeiro:

Deverá clicar no botão de  Show Report, após isso tera um detalhamento dos veiculos que já passaram

<img width="531" height="821" alt="image" src="https://github.com/user-attachments/assets/61e7c1e1-c8f8-4fde-90ba-f1ca96f8d134" />


