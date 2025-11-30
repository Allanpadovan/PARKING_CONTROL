<?php
require __DIR__ . '/../vendor/autoload.php';

use App\Application\ParkingService;
use App\Infra\Repository\SQLiteParkingRepository;
use App\Domain\Pricing\CarPricingStrategy;
use App\Domain\Pricing\BikePricingStrategy;
use App\Domain\Pricing\TruckPricingStrategy;

// Iniciando o serviço
$repository = new SQLiteParkingRepository();
$strategies = [
    'carro'    => new CarPricingStrategy(),
    'moto'     => new BikePricingStrategy(),
    'caminhao' => new TruckPricingStrategy()
];
$service = new ParkingService($repository, $strategies);

$mensagem = $erro = '';
$exibirRelatorio = false;

if ($_POST) {
    $placa = strtoupper(trim($_POST['placa'] ?? ''));
    $tipo  = $_POST['tipo'] ?? '';
    $acao  = $_POST['acao'] ?? '';

    try {
        if ($acao === 'entrada') {
            $service->registerEntry($placa, $tipo);
            $mensagem = "Entrada registrada: <strong>$placa</strong> ($tipo)";
        }

        if ($acao === 'saida') {
            $valor = $service->registerExit($placa);
            $mensagem = "Saída registrada: <strong>$placa</strong><br>Valor a pagar: R$ " . number_format($valor, 2, ',', '.');
        }

        if ($acao === 'relatorio') {
            $exibirRelatorio = true;
        }
    } catch (Exception $e) {
        $erro = $e->getMessage();
    }
}

$relatorio = $service->getFinancialReport();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Controle de Estacionamento Inteligente</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .card {
            background: white;
            border-radius: 30px;
            padding: 50px 60px;
            width: 100%;
            max-width: 500px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.3);
        }
        h1 { font-size: 2.8rem; color: #4c1d95; margin-bottom: 40px; }
        h2 { font-size: 1.8rem; color: #6b46c1; margin: 30px 0 20px; }
        label { display: block; margin: 15px 0 8px; font-weight: 600; color: #553c9a; }
        input, select {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 16px;
            transition: all 0.3s;
        }
        input:focus, select:focus {
            outline: none;
            border-color: #9f7aea;
            box-shadow: 0 0 0 3px rgba(159, 122, 234, 0.2);
        }
        button {
            margin-top: 20px;
            padding: 14px 24px;
            border: none;
            border-radius: 12px;
            font-weight: bold;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s;
            width: 100%;
        }
        .btn-entrada { background: #48bb78; color: white; }
        .btn-entrada:hover { background: #38a169; transform: translateY(-2px); }
        .btn-saida { background: #f56565; color: white; }
        .btn-saida:hover { background: #e53e3e; transform: translateY(-2px); }
        .btn-relatorio {
            background: transparent;
            color: #805ad5;
            border: 2px solid #d6bcfa;
            margin-top: 40px;
        }
        .btn-relatorio:hover {
            background: #d6bcfa;
            color: #553c9a;
        }
        .relatorio {
            background: #faf5ff;
            padding: 25px;
            border-radius: 16px;
            margin-top: 30px;
            border: 1px solid #e9d8fd;
        }
    </style>
</head>
<body>
    <div class="card text-center">
        <h1>Smart Parking</h1>

        <!-- ENTRADA -->
        <div class="text-left">
            <h2>Entry</h2>
            <form method="post">
                <label>Plate:</label>
                <input type="text" name="placa" required placeholder="ABC-1234" maxlength="8">

                <label>Type:</label>
                <select name="tipo" required>
                    <option value="carro">Carro</option>
                    <option value="moto">Moto</option>
                    <option value="caminhao">Caminhão</option>
                </select>

                <button type="submit" name="acao" value="entrada" class="btn-entrada">
                    Register Entry
                </button>
            </form>
        </div>

        <hr style="margin: 40px 0; border: 1px solid #e2e8f0;">

        <!-- SAÍDA -->
        <div class="text-left">
            <h2>Exit</h2>
            <form method="post">
                <label>Plate:</label>
                <input type="text" name="placa" required placeholder="ABC-1234" maxlength="8">

                <button type="submit" name="acao" value="saida" class="btn-saida">
                    Register Exit
                </button>
            </form>
        </div>

        <!-- RELATÓRIO -->
        <form method="post">
            <button type="submit" name="acao" value="relatorio" class="btn-relatorio">
                Show report
            </button>
        </form>

        <?php if ($exibirRelatorio): ?>
        <div class="relatorio text-left">
            <h3 style="color: #6b46c1; margin-bottom: 15px;">Financial Report</h3>
            <p><strong>Total:</strong> R$ <?= number_format($relatorio['total'], 2, ',', '.') ?></p>
            <p><strong>Carros:</strong> <?= $relatorio['carro']['count'] ?> (R$ <?= number_format($relatorio['carro']['revenue'], 2, ',', '.') ?>)</p>
            <p><strong>Motos:</strong> <?= $relatorio['moto']['count'] ?> (R$ <?= number_format($relatorio['moto']['revenue'], 2, ',', '.') ?>)</p>
            <p><strong>Caminhões:</strong> <?= $relatorio['caminhao']['count'] ?> (R$ <?= number_format($relatorio['caminhao']['revenue'], 2, ',', '.') ?>)</p>
        </div>
        <?php endif; ?>
    </div>

    <?php if ($mensagem): ?>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Tudo certo!',
            html: '<?= $mensagem ?>',
            confirmButtonColor: '#805ad5'
        });
    </script>
    <?php endif; ?>

    <?php if ($erro): ?>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Atenção',
            text: '<?= $erro ?>',
            confirmButtonColor: '#f56565'
        });
    </script>
    <?php endif; ?>
</body>
</html>