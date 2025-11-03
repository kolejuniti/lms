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
                size: A4 portrait;
                margin: 0; /* Remove all default browser margins */
            }

            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            html {
                width: 210mm;
                height: 297mm;
            }

            body {
                width: 210mm;
                height: 297mm;
                margin: 0 !important;
                padding: 0 !important;
                background: white !important;
            }

            .no-print {
                display: none !important;
            }

            .page-break {
                page-break-after: always;
                break-after: page;
                height: 0;
                margin: 0;
                padding: 0;
            }

            .print-container {
                width: 210mm !important;
                height: 297mm !important;
                max-width: 210mm !important;
                max-height: 297mm !important;
                margin: 0 !important;
                padding: 8mm 10mm !important; /* Internal padding instead of @page margin */
                box-shadow: none !important;
                background: white !important;
                page-break-inside: avoid;
                break-inside: avoid;
                box-sizing: border-box;
            }

            .barcode-grid {
                display: grid !important;
                grid-template-columns: repeat(5, 1fr) !important;
                grid-template-rows: repeat(10, 1fr) !important;
                gap: 3mm !important;
                width: 100%;
                height: 100%;
                grid-auto-flow: row;
            }

            .barcode-item {
                border: 1px solid #ccc !important;
                page-break-inside: avoid;
                break-inside: avoid;
                padding: 1.5mm !important;
            }

            .barcode-number {
                font-size: 8pt !important;
                margin-top: 1mm !important;
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
            grid-template-rows: repeat(10, 1fr);
            gap: 5mm;
            width: 100%;
            min-height: 270mm;
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
            page-break-inside: avoid;
        }

        .barcode-item svg {
            max-width: 100%;
            height: auto;
        }

        .barcode-number {
            font-size: 9pt;
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
        <p class="info">Total barcodes: <strong>{{ count($numbers) }}</strong> | Pages: <strong>{{ ceil(count($numbers) / 50) }}</strong></p>
        <button onclick="window.print()">
            <i class="fa fa-print"></i> Print Barcodes
        </button>
        <button class="back-btn" onclick="window.close()">
            <i class="fa fa-arrow-left"></i> Close
        </button>
    </div>

    @php
        $chunks = array_chunk($numbers, 50); // 5 columns x 10 rows = 50 items per page
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
