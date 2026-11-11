
var scanInProgress = false;

function setResult(result, callback, baseURL) {
	if(!scanInProgress) {
		scanInProgress = true;

		if (result.data) {
			if(baseURL) {
				new Audio(baseURL + 'assets/sounds/beep.mp3').play()
			} else {
				new Audio('assets/sounds/beep.mp3').play()
			}

			callback(result.data);
		} else {
			setTimeout(() => {
				scanInProgress = false;
			}, 500);
		}
	}
}

scanner = {};
function initScanner(callback, baseURL) {

	const videoEl = document.getElementById('preview');
	let camSelectEl = document.getElementById('cameraSelect');

	if (!camSelectEl) {
		camSelectEl = document.createElement('select');
		camSelectEl.id = 'cameraSelect';
		camSelectEl.style.cssText = 'position: fixed; bottom: 15px; right: 15px;';
		document.body.appendChild(camSelectEl);
	}

	const addCamSelectOption = (label, value, prepend = false) => {
		const option = document.createElement('option');
		option.text = label;
		option.value = value;

		if (prepend) {
			camSelectEl.prepend(option);
		} else {
			camSelectEl.add(option);
		}
	};

	const saveCameraId = (cameraId) => localStorage.setItem('qrScannerCameraId', cameraId);
	const getSavedCameraId = () => localStorage.getItem('qrScannerCameraId');

	scanner = new QrScanner(videoEl, result => setResult(result, callback, baseURL), {
		preferredCamera: 'environment',
		highlightScanRegion: true,
		highlightCodeOutline: true,
	});

	scanner.start().then(() => {
		// List cameras after the scanner started to avoid listCamera's stream and the scanner's stream being requested
		// at the same time which can result in listCamera's unconstrained stream also being offered to the scanner.
		// Note that we can also start the scanner after listCameras, we just have it this way around in the demo to
		// start the scanner earlier.
		QrScanner.listCameras(true)
			.then(cameraList => {
				cameraList.forEach(camera => addCamSelectOption(camera.label, camera.id));

				const savedCameraId = getSavedCameraId();
				if (savedCameraId && cameraList.find(camera => camera.id === savedCameraId)) {
					scanner.setCamera(savedCameraId);
					camSelectEl.value = savedCameraId
				} else {
					addCamSelectOption('(preferred camera)', '', true);
					camSelectEl.value = '';
				}
			});
	});

	camSelectEl.addEventListener('change', event => {
		scanner.setCamera(event.target.value);
		saveCameraId(event.target.value);

		// remove preferred camera option from camera select
		const preferredOption = document.querySelectorAll('#cameraSelect option[value=""]')[0];
		if (preferredOption) {
			preferredOption.remove();
		}
	});

	return scanner;
}