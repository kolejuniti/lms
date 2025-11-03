<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barcode Generator</title>
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
    <style>
        @media print {
            @page {
                size: A4;
                margin: 10mm;
            }

            body {
                margin: 0;
                padding: 0;
            }

            .no-print {
                display: none;
            }

            .page-break {
                page-break-after: always;
            }
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background: #f5f5f5;
        }

        .print-container {
            background: white;
            padding: 10mm;
            margin: 0 auto;
            width: 210mm; /* A4 width */
            min-height: 297mm; /* A4 height */
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .barcode-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 5mm;
            width: 100%;
        }

        .barcode-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 2mm;
            border: 1px solid #ddd;
            background: white;
            break-inside: avoid;
        }

        .barcode-item svg {
            max-width: 100%;
            height: auto;
        }

        .barcode-number {
            font-size: 10pt;
            font-weight: bold;
            margin-top: 2mm;
            font-family: monospace;
        }

        .controls {
            margin-bottom: 20px;
            padding: 15px;
            background: white;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .controls button {
            padding: 10px 20px;
            margin-right: 10px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }

        .controls button:hover {
            background: #0056b3;
        }

        .controls .back-btn {
            background: #6c757d;
        }

        .controls .back-btn:hover {
            background: #545b62;
        }

        h2 {
            margin: 0 0 10px 0;
            color: #333;
        }

        .info {
            color: #666;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="controls no-print">
        <h2>Barcode Generator</h2>
        <p class="info">Total barcodes: <strong>{{ count($numbers) }}</strong> | Pages: <strong>{{ ceil(count($numbers) / 55) }}</strong></p>
        <button onclick="window.print()">
            <i class="fa fa-print"></i> Print Barcodes
        </button>
        <button class="back-btn" onclick="window.close()">
            <i class="fa fa-arrow-left"></i> Close
        </button>
    </div>

    @php
        $chunks = array_chunk($numbers, 55); // 5 columns x 11 rows = 55 items per page
    @endphp

    @foreach($chunks as $pageIndex => $pageNumbers)
        <div class="print-container {{ $pageIndex < count($chunks) - 1 ? 'page-break' : '' }}">
            <div class="barcode-grid">
                @foreach($pageNumbers as $number)
                    <div class="barcode-item">
                        <svg class="barcode" data-number="{{ $number }}"></svg>
                        <div class="barcode-number">{{ $number }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach

    <script>
        // Generate all barcodes after page load
        document.addEventListener('DOMContentLoaded', function() {
            const barcodes = document.querySelectorAll('.barcode');

            barcodes.forEach(function(barcode) {
                const number = barcode.getAttribute('data-number');

                try {
                    JsBarcode(barcode, number, {
                        format: 'CODE128',
                        width: 2,
                        height: 50,
                        displayValue: false,
                        margin: 5,
                        fontSize: 12
                    });
                } catch(e) {
                    console.error('Error generating barcode for:', number, e);
                }
            });

            console.log('All barcodes generated successfully!');
        });

        // Auto-print dialog (optional - can be removed if not desired)
        // setTimeout(function() {
        //     window.print();
        // }, 1000);
    </script>
</body>
</html>
