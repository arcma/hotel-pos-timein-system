var instaScanInit = {};
var cameraStarted = false;
var scanInProgress = false;

function initScanner(ajaxURL, callback, baseURL) {
    instaScanInit.ajaxURL = ajaxURL;
    instaScanInit.scanner = new Instascan.Scanner({
        video: document.getElementById('preview'),
        mirror: false,
        continuous: true,
        backgroundScan: false
    });
    instaScanInit.callback = callback;

    instaScanInit.scanner.addListener('scan', function (content) {
        if(!scanInProgress) {
            scanInProgress = true;

            if(baseURL) {
                new Audio(baseURL + 'assets/sounds/beep.mp3').play()
            } else {
                new Audio('assets/sounds/beep.mp3').play()
            }

            $("#cameraModal").modal('hide');
            $("#scanningDiv,#divWelcome").hide();
            $("#checkingDiv,#divChecking").fadeIn(200);

            $.post(instaScanInit.ajaxURL, {tag: content}, function(data) {
                data = JSON.parse(data);
                if (instaScanInit.callback instanceof Function){
                    instaScanInit.callback(data);
                } else {
                    if(data) {
                        $("#nameDiv").html(data.name);
                        $("#deptDiv").html(data.aux);
                        $("#seatDiv").html(data.aux2);
                        $("#ticketDiv").html(data.aux3);

                        $("#divChecking").fadeOut(200, function(){
                            $("#divDetails").fadeIn(200);
                        });
                    } else {
                        $("#divChecking").fadeOut(200, function(){
                            $("#divError").fadeIn(200);
                        });
                    }

                    setTimeout(function() {
                        $("#divDetails").fadeOut(200);
                        $("#divError").fadeOut(200);
                        $("#divWelcome").delay(200).fadeIn(200);
                    }, 3000);
                }
            });
        }
    });

    return instaScanInit.scanner;
}

$('#cameraModal').on('hidden.bs.modal', function () {
    if(autofocus !== false) {
        $('#textInput').focus();
    }
    scanInProgress = true;
});

$('#cameraModal').on('shown.bs.modal', function () {
    startScan();
    scanInProgress = false;
});

$(document).on('click','#camera0',function(e) {
    intCamera = 0;
    restartScan();
});

$(document).on('click','#camera1',function(e) {
    intCamera = 1;
    restartScan();
});

var intCamera = null;

function startScan(camera) {
    if(!cameraStarted) {
        Instascan.Camera.getCameras().then(function (cameras) {
            if (cameras.length > 0) {
                if(intCamera===null){
                    intCamera = 0;
                    // if(cameras[1]) {
                    //     intCamera = 1;
                    // }
                }
                if(camera) {
                    for(var j = 0; j < cameras.length; j++) {
                        if(cameras[j].id == camera.id) {
                            cameraToUse = cameras[j];
                        }
                    }
                } else {
                    cameraToUse = cameras[intCamera];
                }
                var cameraSelect = $('#cameraSelect');
                cameraSelect.html('');
                for(var i = 1; i <= cameras.length; i++) {
                    if(cameras[i-1].id == cameraToUse.id) {
                        var selected = ' selected';
                    } else {
                        var selected = '';
                    }
                    cameraSelect.append("<option value='" + JSON.stringify(cameras[i-1]) + "'" + selected + ">Camera " + i + "</option>")
                }
                instaScanInit.scanner.start(cameraToUse);
                cameraStarted = true;
            } else {
                console.error('No cameras found.');
            }
        }).catch(function (e) {
            console.error(e);
        });
    }
}

function restartScan() {
    instaScanInit.scanner.stop().then(function() {
        cameraStarted = false;
        startScan();
    });
}

$(document).on('change', '#cameraSelect', function() {
    var camera = $(this).val();
    instaScanInit.scanner.stop().then(function() {
        cameraStarted = false;
        startScan(JSON.parse(camera));
    });
});
