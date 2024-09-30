const startScanButton = document.getElementById("startScan");
const stopScanButton = document.getElementById("stopScan");
const barcodeInput = document.getElementById("barcodeInput");
const scannerContainer = document.getElementById("scanner-container");
const searchBar = $("#search-products");

startScanButton.addEventListener("click", () => {
  startScanner();
});

stopScanButton.addEventListener("click", () => {
  stopScanner();
});

function startScanner() {
  Quagga.init(
    {
      inputStream: {
        name: "Live",
        type: "LiveStream",
        target: scannerContainer, // The video element for displaying the camera feed
        constraints: {
          facingMode: "environment", // Use the back camera if available
        },
      },
      decoder: {
        readers: [
          "code_128_reader", // Include other barcode types as needed
          "ean_reader",
          "ean_8_reader",
          "upc_reader",
          "upc_e_reader",
        ],
      },
    },
    (err) => {
      if (err) {
        console.error(err);
        return;
      }
      Quagga.start();
    }
  );

  Quagga.onDetected((result) => {
    const code = result.codeResult.code;
    if (barcodeInput) barcodeInput.value = code;
    if (searchBar) {
      searchBar.val(code);
      setTimeout(() => {
        lookup(searchBar[0]);
      }, 100);
    }
    stopScanner(); // Stop scanner after a successful read
    $("#stopScan").trigger("click");
  });
}

function stopScanner() {
  Quagga.stop();
}
