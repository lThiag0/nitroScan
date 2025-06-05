<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <title>Etiquetas de Preço</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #fff;
            margin: 0;
            padding: 10px;
        }

        .etiqueta {
            display: inline-block;
            vertical-align: top;
            width: 260px;
            height: auto;
            background: #fff;
            border: 1px solid #000;
            border-radius: 6px;
            margin: 5px;
            padding: 10px 14px;
            box-sizing: border-box;
            color: #000;
            page-break-inside: avoid;
            overflow: hidden;
        }

        .etiqueta::after {
            content: "";
            display: table;
            clear: both;
        }

        .produto-nome {
            font-weight: 700;
            font-size: 14px;
            text-align: center;
            word-break: break-word;
            white-space: normal;
            overflow: hidden;
            max-height: 34px;
            line-height: 1.2;
            margin-bottom: 4px;
        }

        .info-row {
            width: 100%;
            clear: both;
        }

        .barcode-container {
            float: left;
            width: 50%;
        }

        .barcode img {
            margin-top: 7px;
            height: 22px;
            max-width: 100%;
        }

        .codigo-ean-num {
            font-family: 'Courier New', Courier, monospace;
            font-size: 11px;
            letter-spacing: 2px;
            margin-top: 2px;
            text-align: center;
            width: 100%;
        }

        .preco-container {
            float: right;
            width: 40%;
            background: #e0e0e0;
            border-radius: 8px;
            padding: 6px 8px;
            box-sizing: border-box;
            text-align: right;
        }

        .valor {
            font-size: 20px;
            font-weight: 900;
            color: #000;
            margin-bottom: 2px;
        }

        .data-etiqueta {
            font-size: 8px;
            color: #444;
            font-style: italic;
        }

        @media print {
            .etiqueta {
                margin: 0 5px 5px 0;
            }
        }
    </style>
</head>
<body>
    @foreach($produtos as $produto)
        <div class="etiqueta">
            <div class="produto-nome" title="{{ $produto->nome }}">{{ $produto->nome }}</div>
            <div class="info-row">
                <div class="barcode-container">
                    @php
                        $generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
                        $barcode = base64_encode($generator->getBarcode($produto->codigo_ean, $generator::TYPE_CODE_128));
                    @endphp
                    <div class="barcode">
                        <img src="data:image/png;base64,{{ $barcode }}" alt="Código de Barras">
                    </div>
                    <div class="codigo-ean-num">{{ $produto->codigo_ean }}</div>
                </div>
                <div class="preco-container">
                    <div class="valor">R$ {{ number_format($produto->valor, 2, ',', '.') }}</div>
                    <div class="data-etiqueta">{{ now()->format('d/m/Y') }}</div>
                </div>
            </div>
        </div>
    @endforeach
</body>
</html>
