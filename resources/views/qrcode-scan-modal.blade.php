<div id="scanner-container" style="position: relative;">
    <video id="qr-video" style="width: 100%; height: auto;" autoplay></video>
    <canvas id="qr-canvas" style="display: none;"></canvas>
    <button id="start-scan" class="btn btn-primary">QRコードをスキャン</button>
</div>

<script>
    document.getElementById('start-scan').addEventListener('click', function() {
        const videoElement = document.getElementById('qr-video');
        const canvasElement = document.getElementById('qr-canvas');
        const canvas = canvasElement.getContext('2d');
        
        // QR Scannerライブラリの初期化
        const qrScanner = new QrScanner(videoElement, result => {
            alert("QRコードが読み取られました: " + result);
            qrScanner.stop();
        });

        // カメラの起動
        qrScanner.start().then(() => {
            console.log("カメラが起動しました。QRコードのスキャンを開始できます。");
        }).catch(e => {
            console.error("カメラの起動に失敗しました: ", e);
        });
    });
</script>
