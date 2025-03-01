<div id="qr-reader" style="width: 100%; height: 400px; border: 1px solid #ccc;"></div>
<div id="result" style="margin-top: 20px; font-size: 18px;"></div>

<!-- HTML5 QRコードライブラリ -->
<script src="https://unpkg.com/html5-qrcode/minified/html5-qrcode.min.js"></script>

<script>
    // QRコード読み取り成功時のコールバック関数
    function onScanSuccess(decodedText, decodedResult) {
        document.getElementById('result').innerHTML = `<b>読み取った結果:</b> ${decodedText}`;
    }

    // QRコード読み取りエラー時のコールバック関数
    function onScanError(errorMessage) {
        console.warn(`QR読み取りエラー: ${errorMessage}`);
    }

    // モーダル表示後にカメラを初期化する
    window.addEventListener('openModal', function () {
        const html5QrcodeScanner = new Html5QrcodeScanner(
            "qr-reader", 
            { 
                fps: 10, 
                qrbox: 250 
            },
            false
        );

        // QRコードスキャン開始
        html5QrcodeScanner.render(onScanSuccess, onScanError);
    });
</script>
