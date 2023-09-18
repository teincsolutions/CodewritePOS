<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'POS-Receipt' ?></title>
    <style>
        html,
        body {
            font-family: Arial, sans-serif;
            font-size: .8em;
            margin: .25em;
        }

        @media print {
            body {
                font-size: .8em;
            }
        }

        /* Reset default styles */
        * {
            box-sizing: border-box;
            font-size: 1em;
            font-weight: 400;
            padding: 0;
            margin: 0;
        }

        h1,
        h2,
        h3,
        h4,
        p {
            margin: .25em 0 .25em 0;
        }

        h1,
        h2,
        h3,
        h4 {
            font-weight: bold;
        }

        h1 {
            font-size: 3em;
            text-align: center;
        }

        h2 {
            font-size: 2em;
            text-align: center;
        }

        h3 {
            font-size: 1.5em;
            text-align: center;
        }

        h4 {
            font-size: 1.2em;
            text-align: center;
        }

        p {
            font-size: 1em;
        }

        /* Define table styles */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        /* Define table header styles */
        th {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
            font-weight: bold;
            /* Make table headers bold */
        }

        /* Define table row styles */
        td {
            border: 1px dotted #000;
            padding: 8px;
        }

        /* Define table row styles */
        td.no-border {
            border: none;
        }

        /* Define alternating row colors */
        tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        table.raw *,
        table.raw tr:nth-child(even) {
            background-color: unset;
            border: none;
        }

        table.info td,
        table.info th {
            text-align: left;
            font-size: .75em;
            padding: 0;
        }

        table.info th:first-child {
            text-align: right;
            padding-right: 4px;
        }

        /* Align text properly */
        td:first-child,
        td:nth-child(3),
        td:nth-child(4) {
            text-align: right;
            /* Align text to the right for quantity, price, and subtotal */
        }

        /* Align text properly */
        tbody td:first-child {
            text-align: left;
            /* Align text to the right for quantity, price, and subtotal */
        }

        tfoot.sum tr:first-child td {
            padding-top: .5em;
        }

        tfoot th:first-child {
            text-align: right;
        }

        tfoot.sum th,
        tfoot.sum td {
            padding: .1em;
            border: none;
        }

        tfoot.sum tr.total {
            font-weight: bold;
        }

        tfoot.sum.text-sm {
            font-size: .75em;
        }

        footer {
            margin-top: .5em;
        }

        b {
            font-weight: bold;
        }

        small {
            font-size: .5em;
        }

        .text-center {
            text-align: center;
        }
    </style>
</head>

<body>
    <?= $this->renderSection('content') ?>
</body>

</html>