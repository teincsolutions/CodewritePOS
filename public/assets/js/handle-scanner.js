const startScanButton = document.getElementById('startScan');
const stopScanButton = document.getElementById('stopScan');
const barcodeInput = document.getElementById('barcodeInput');
const scannerContainer = document.getElementById('scanner-container');

startScanButton.addEventListener('click', () => {
    startScanner();
});

stopScanButton.addEventListener('click', () => {
    stopScanner();
});

function startScanner() {
  $("#scanner-container,#stopScan").show(100);
  $("#startScan").hide(100);

    Quagga.init({
        inputStream: {
            name: "Live",
            type: "LiveStream",
            target: scannerContainer, // The video element for displaying the camera feed
            constraints: {
                facingMode: "environment" // Use the back camera if available
            }
        },
        decoder: {
            readers: [
                "code_128_reader", // Include other barcode types as needed
                "ean_reader",
                "ean_8_reader",
                "upc_reader",
                "upc_e_reader"
            ]
        }
    }, err => {
        if (err) {
            console.error(err);
            return;
        }
        Quagga.start();
    });

    Quagga.onDetected(result => {
        const code = result.codeResult.code;
        barcodeInput.value = code;
        stopScanner(); // Stop scanner after a successful read
    });
}

function stopScanner() {
    Quagga.stop();
    $("#scanner-container,#stopScan").hide(100);
    $("#startScan").show(100);
}